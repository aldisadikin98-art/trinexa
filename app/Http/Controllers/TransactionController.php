<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\WalletTransaction;
use App\Models\ShopVoucherUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user   = $request->user();
        $status = $request->get('status', 'semua');

        $query = $user->transactions()->with(['items.product', 'shopVoucher'])->latest();

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(10);

        $tabs = [
            'semua'      => 'Semua',
            'pending'    => 'Menunggu',
            'diproses'   => 'Diproses',
            'dikirim'    => 'Dikirim',
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        return view('pesanan.index', compact('transactions', 'tabs', 'status'));
    }

    public function show(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);
        $transaction->load(['items.product', 'items.review', 'shopVoucher', 'user']);

        return view('pesanan.show', compact('transaction'));
    }

    public function cancel(Request $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);

        if (!$transaction->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request, $transaction) {
                // Kembalikan stok produk
                foreach ($transaction->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                // Kembalikan saldo wallet
                $wallet = $transaction->user->wallet;
                $wallet->increment('balance', $transaction->total_amount);

                // Catat pengembalian saldo
                WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'type'         => 'credit',
                    'amount'       => $transaction->total_amount,
                    'description'  => 'Refund pesanan ' . $transaction->receipt_number,
                    'reference_id' => $transaction->receipt_number,
                    'status'       => 'success',
                ]);

                // Hapus penggunaan voucher (kembalikan kuota)
                if ($transaction->shop_voucher_id) {
                    ShopVoucherUsage::where('transaction_id', $transaction->id)->delete();
                    $transaction->shopVoucher()->increment('quota');
                }

                // Update status transaksi
                $transaction->update([
                    'status'              => 'dibatalkan',
                    'cancelled_at'        => now(),
                    'cancellation_reason' => $request->reason,
                ]);
            });

            return redirect()->route('transaction.index')
                ->with('success', 'Pesanan berhasil dibatalkan. Saldo telah dikembalikan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }
}
