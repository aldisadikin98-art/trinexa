<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->naturea()->with('approvedReviews');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Sort
        match ($request->get('sort', 'terbaru')) {
            'terlaris'       => $query->withCount('transactionItems')->orderByDesc('transaction_items_count'),
            'harga_terendah' => $query->orderBy('price', 'asc'),
            'harga_tertinggi'=> $query->orderBy('price', 'desc'),
            default          => $query->latest(),
        };

        $products = $query->paginate(9)->withQueryString();

        $categories = ['Serum', 'Toner', 'Moisturizer', 'Sunscreen', 'Cleanser', 'Treatment'];

        // Cart count untuk badge navbar
        $cartCount = auth()->user()->cartItems()->count();

        return view('shop.index', compact('products', 'categories', 'cartCount'));
    }

    public function show(Product $product)
    {
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

        $cartCount = auth()->user()->cartItems()->count();

        return view('shop.show', compact('product', 'reviewStats', 'inCart', 'related', 'cartCount'));
    }
}
