<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KareblaRedemption;
use Illuminate\Http\Request;

class AdminKareblaRedemptionController extends Controller
{
    public function index(Request $request)
    {
        $query = KareblaRedemption::with(['user', 'product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('receipt_number', 'like', '%' . $request->search . '%');
        }

        $redemptions = $query->paginate(20);
        $statusOptions = [
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'dikirim'  => 'Dikirim',
            'selesai'  => 'Selesai',
        ];

        return view('admin.karebla.redemptions.index', compact('redemptions', 'statusOptions'));
    }

    public function show(KareblaRedemption $redemption)
    {
        $redemption->load(['user', 'product']);
        return view('admin.karebla.redemptions.show', compact('redemption'));
    }

    public function updateStatus(Request $request, KareblaRedemption $redemption)
    {
        $request->validate([
            'status' => 'required|in:diproses,dikirim,selesai',
            'notes' => 'nullable|string'
        ]);

        $redemption->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $redemption->notes
        ]);

        return back()->with('success', 'Status penukaran berhasil diupdate menjadi ' . ucfirst($request->status));
    }
}
