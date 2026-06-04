<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Kolom yang diambil untuk listing produk.
     * Sengaja EXCLUDE kolom LONGTEXT (images, image_url, description, ingredients,
     * usage_instructions, benefits, skin_type, skin_type_not_suitable)
     * agar MySQL tidak OOM saat ORDER BY pada server Railway yang sort_buffer_size-nya kecil.
     * Kolom image dimuat ulang via fresh() setelah paginate — hanya untuk 9 baris.
     */
    private const LIST_COLUMNS = [
        'id', 'name', 'slug', 'price', 'stock', 'category',
        'is_bundle', 'bundle_discount', 'brand', 'is_active',
        'reward_points', 'coin_price', 'bpom_number', 'created_at', 'updated_at',
    ];

    public function index(Request $request)
    {
        try {
            // Hanya pilih kolom ringan — TIDAK termasuk LONGTEXT (images, image_url, dll)
            // Ini mencegah MySQL OOM saat sorting karena baris terlalu besar.
            $query = Product::active()->naturea()
                ->select(self::LIST_COLUMNS);

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter kategori
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            // Sort
            $sort = $request->get('sort', 'terbaru');
            if ($sort === 'terlaris') {
                $query->withCount('transactionItems')->orderByDesc('transaction_items_count');
            } elseif ($sort === 'harga_terendah') {
                $query->orderBy('price', 'asc');
            } elseif ($sort === 'harga_tertinggi') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }

            // Paginate — LIMIT/OFFSET sudah diterapkan, baru muat data berat
            $products = $query->paginate(9)->withQueryString();

            // Setelah paginate (hanya 9 baris), muat kolom gambar & aggregate review
            // Ini jauh lebih efisien daripada muat semua sebelum LIMIT
            $ids = $products->pluck('id')->all();
            if (!empty($ids)) {
                // Ambil kolom gambar untuk 9 produk saja
                $images = Product::whereIn('id', $ids)
                    ->select(['id', 'image_url', 'images'])
                    ->get()
                    ->keyBy('id');

                // Inject data gambar ke setiap produk di koleksi paginator
                $products->each(function ($product) use ($images) {
                    if (isset($images[$product->id])) {
                        $product->image_url = $images[$product->id]->image_url;
                        $product->images    = $images[$product->id]->images;
                    }
                });
            }

            // Load review aggregate hanya untuk 9 produk di halaman ini
            $products->loadCount('approvedReviews');
            $products->loadAvg('approvedReviews', 'rating');

            $categories = ['Serum', 'Toner', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Treatment'];

            // Cart count untuk badge navbar (null-safe untuk guest)
            $cartCount = auth()->check() ? auth()->user()->cartItems()->count() : 0;

            $view = view('shop.index', compact('products', 'categories', 'cartCount'))->render();
            return response($view);
        } catch (\Throwable $e) {
            return response("<h1>Error pada Shop:</h1><p>" . $e->getMessage() . "</p><p>Line: " . $e->getLine() . "</p>", 500);
        }
    }

    public function show(Product $product)
    {
        try {
            abort_if(!$product->is_active, 404);

            $product->load(['approvedReviews.user', 'approvedReviews.images', 'approvedReviews.helpfuls']);

            // Review statistics
            $reviewStats = [
                'total'   => $product->approvedReviews->count(),
                'average' => round($product->approvedReviews->avg('rating') ?? 0, 1),
                'stars'   => [],
            ];
            for ($i = 5; $i >= 1; $i--) {
                $count = $product->approvedReviews->where('rating', $i)->count();
                $reviewStats['stars'][$i] = [
                    'count'   => $count,
                    'percent' => $reviewStats['total'] > 0
                        ? round(($count / $reviewStats['total']) * 100)
                        : 0,
                ];
            }

            // Apakah user sudah punya item di keranjang untuk produk ini
            $inCart = false;
            if (auth()->check()) {
                $inCart = auth()->user()->cartItems()->where('product_id', $product->id)->exists();
            }

            // Related products
            $related = Product::active()->naturea()
                ->where('id', '!=', $product->id)
                ->where('category', $product->category)
                ->take(4)->get();

            $cartCount = auth()->check() ? auth()->user()->cartItems()->count() : 0;

            $view = view('shop.show', compact('product', 'reviewStats', 'inCart', 'related', 'cartCount'))->render();
            return response($view);
        } catch (\Throwable $e) {
            return response("<h1>Error pada Shop Detail:</h1><p>" . $e->getMessage() . "</p><p>Line: " . $e->getLine() . "</p>", 500);
        }
    }
}
