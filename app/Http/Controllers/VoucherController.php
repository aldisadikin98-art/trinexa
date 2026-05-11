<?php

namespace App\Http\Controllers;

use App\Models\ShopVoucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'code'     => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $voucher = ShopVoucher::where('code', strtoupper($request->code))->first();

        if (!$voucher) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak ditemukan.'], 422);
        }

        $result = $voucher->validate($request->user()->id, $request->subtotal);

        if (!$result['valid']) {
            return response()->json(['valid' => false, 'message' => $result['message']], 422);
        }

        return response()->json([
            'valid'       => true,
            'message'     => 'Voucher berhasil diterapkan!',
            'discount'    => $result['discount'],
            'discount_fmt'=> 'Rp ' . number_format($result['discount'], 0, ',', '.'),
            'voucher_name'=> $voucher->name,
            'type_label'  => $voucher->type_label,
        ]);
    }
}
