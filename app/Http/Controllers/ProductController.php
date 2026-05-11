<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);

        return view('user.shop.catalog', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $relatedProducts = Product::where('brand', $product->brand)
                                  ->where('id', '!=', $product->id)
                                  ->take(4)
                                  ->get();

        return view('user.shop.product-detail', compact('product', 'relatedProducts'));
    }
}
