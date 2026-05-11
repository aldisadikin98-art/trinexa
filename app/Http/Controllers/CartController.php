<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->with('product')->get();
        $walletBalance = $request->user()->wallet ? $request->user()->wallet->balance : 0;

        $subtotal = 0;
        $estimasiKoin = 0;

        foreach ($cartItems as $item) {
            $itemSubtotal = $item->quantity * $item->product->price;
            $subtotal += $itemSubtotal;
            $estimasiKoin += floor($itemSubtotal / 10000);
        }

        return view('cart.index', compact('cartItems', 'subtotal', 'estimasiKoin', 'walletBalance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product  = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup.',
            ], 422);
        }

        $cartItem = $request->user()->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $quantity;
            if ($newQty > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok tersedia (' . $product->stock . ').',
                ], 422);
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            $request->user()->cartItems()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity,
            ]);
        }

        $cartCount = $request->user()->cartItems()->sum('quantity');

        return response()->json([
            'success'    => true,
            'message'    => 'Produk ditambahkan ke keranjang!',
            'cart_count' => $cartCount,
        ]);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = (int) $request->quantity;

        if ($quantity > $cartItem->product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok (' . $cartItem->product->stock . ').'
            ], 422);
        }

        $cartItem->update(['quantity' => $quantity]);

        $itemSubtotal = $cartItem->quantity * $cartItem->product->price;
        $cartItems = $request->user()->cartItems()->with('product')->get();
        
        $cartTotal = 0;
        $estimasiKoin = 0;
        foreach ($cartItems as $item) {
            $st = $item->quantity * $item->product->price;
            $cartTotal += $st;
            $estimasiKoin += floor($st / 10000);
        }

        return response()->json([
            'success'       => true,
            'subtotal_item' => $itemSubtotal,
            'cart_total'    => $cartTotal,
            'cart_count'    => $cartItems->sum('quantity'),
            'estimasi_koin' => $estimasiKoin,
        ]);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        $cartItems = $request->user()->cartItems()->with('product')->get();
        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $cartTotal += $item->quantity * $item->product->price;
        }

        return response()->json([
            'success'    => true,
            'cart_count' => $cartItems->sum('quantity'),
            'cart_total' => $cartTotal,
        ]);
    }
}
