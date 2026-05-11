<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopVoucher;
use Illuminate\Http\Request;

class AdminVoucherController extends Controller
{
    public function index()
    {
        $vouchers = ShopVoucher::latest()->paginate(20);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'         => 'required|string|uppercase|unique:shop_vouchers,code',
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:percent,nominal',
            'value'        => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'expired_at'   => 'nullable|date',
            'quota'        => 'nullable|integer|min:1',
            'is_active'    => 'boolean',
        ]);

        ShopVoucher::create([
            'code'         => strtoupper($request->code),
            'name'         => $request->name,
            'type'         => $request->type,
            'value'        => $request->value,
            'min_purchase' => $request->min_purchase ?? 0,
            'max_discount' => $request->max_discount,
            'expired_at'   => $request->expired_at,
            'quota'        => $request->quota,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.voucher.index')
            ->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function edit(ShopVoucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, ShopVoucher $voucher)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:percent,nominal',
            'value'        => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'expired_at'   => 'nullable|date',
            'quota'        => 'nullable|integer|min:1',
        ]);

        $voucher->update($request->only([
            'name', 'type', 'value', 'min_purchase', 'max_discount', 'expired_at', 'quota',
        ]));
        $voucher->update(['is_active' => $request->boolean('is_active', true)]);

        return redirect()->route('admin.voucher.index')
            ->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(ShopVoucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.voucher.index')
            ->with('success', 'Voucher berhasil dihapus.');
    }
}
