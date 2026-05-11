<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewHelpful;
use App\Models\ReviewImage;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id'      => 'required|exists:transactions,id',
            'transaction_item_id' => 'required|exists:transaction_items,id',
            'product_id'          => 'required|exists:products,id',
            'rating'              => 'required|integer|between:1,5',
            'skin_type'           => 'nullable|string|max:50',
            'body'                => 'required|string|min:20',
            'images.*'            => 'nullable|image|max:2048',
        ]);

        $user        = $request->user();
        $transaction = Transaction::findOrFail($request->transaction_id);

        // Pastikan transaksi milik user
        abort_if($transaction->user_id !== $user->id, 403);

        // Pastikan status selesai
        if ($transaction->status !== 'selesai') {
            return back()->with('error', 'Ulasan hanya bisa ditulis setelah pesanan selesai.');
        }

        // Cek apakah sudah pernah review produk ini di transaksi ini
        $exists = Review::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('transaction_id', $request->transaction_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah pernah menulis ulasan untuk produk ini.');
        }

        DB::transaction(function () use ($request, $user) {
            $review = Review::create([
                'user_id'             => $user->id,
                'product_id'          => $request->product_id,
                'transaction_id'      => $request->transaction_id,
                'transaction_item_id' => $request->transaction_item_id,
                'rating'              => $request->rating,
                'skin_type'           => $request->skin_type,
                'body'                => $request->body,
                'status'              => 'pending',
                'is_verified_purchase'=> true,
            ]);

            // Upload foto
            if ($request->hasFile('images')) {
                foreach (array_slice($request->file('images'), 0, 5) as $image) {
                    $path = $image->store('reviews', 'public');
                    ReviewImage::create([
                        'review_id'  => $review->id,
                        'image_path' => $path,
                    ]);
                }
            }
        });

        return back()->with('success', 'Ulasan kamu sedang menunggu moderasi. Terima kasih!');
    }

    public function helpful(Request $request, Review $review)
    {
        $user = $request->user();

        $existing = ReviewHelpful::where('review_id', $review->id)
            ->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $review->decrement('helpful_count');
            $helpful = false;
        } else {
            ReviewHelpful::create(['review_id' => $review->id, 'user_id' => $user->id]);
            $review->increment('helpful_count');
            $helpful = true;
        }

        return response()->json([
            'helpful'       => $helpful,
            'helpful_count' => $review->fresh()->helpful_count,
        ]);
    }
}
