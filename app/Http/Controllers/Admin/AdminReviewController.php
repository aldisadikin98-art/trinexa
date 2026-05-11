<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $reviews = Review::with(['user', 'product', 'images'])
            ->where('status', $status)
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Ulasan disetujui dan akan tampil di halaman produk.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Ulasan ditolak.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Ulasan dihapus.');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate(['reply' => 'required|string|max:1000']);
        $review->update([
            'admin_reply'      => $request->reply,
            'admin_replied_at' => now(),
        ]);
        return back()->with('success', 'Balasan berhasil disimpan.');
    }
}
