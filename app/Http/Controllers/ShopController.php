<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::active()->naturea();

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter kategori
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            // Sort — pisahkan 'terlaris' karena butuh withCount('transactionItems')
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

            // Paginate dulu TANPA subquery review, lalu load aggregate setelah itu
            // Ini menghindari "Out of sort memory" karena dua correlated subquery berjalan
            // sebelum LIMIT/OFFSET diterapkan oleh MySQL.
            $products = $query->paginate(9)->withQueryString();

            // Load count & avg hanya untuk 9 produk hasil halaman saat ini (bukan seluruh tabel)
            $products->loadCount('approvedReviews');
            $products->loadAvg('approvedReviews', 'rating');

            $categories = ['Serum', 'Toner', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Treatment'];

            // Cart count untuk badge navbar (null-safe untuk guest)
            $cartCount = auth()->check() ? auth()->user()->cartItems()->count() : 0;

            $view = view('shop.index', compact('products', 'categories', 'cartCount'))->render();
            return response($view);
        } catch (\Throwable $e) {
            // Jika terjadi error di production (Railway), tampilkan pesannya sementara untuk debugging
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
