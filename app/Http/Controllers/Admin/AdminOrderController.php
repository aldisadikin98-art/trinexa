<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WalletTransaction;
use App\Models\ShopVoucherUsage;
use App\Models\RewardPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'items.product'])
            ->whereNotNull('receipt_number')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('receipt_number', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->paginate(20);

        $statusOptions = [
            'pending'    => 'Menunggu',
            'diproses'   => 'Diproses',
            'dikirim'    => 'Dikirim',
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        return view('admin.orders.index', compact('transactions', 'statusOptions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.product', 'shopVoucher']);
        return view('admin.orders.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status'          => 'required|in:diproses,dikirim,selesai',
            'tracking_number' => 'nullable|string',
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        try {
            DB::transaction(function () use ($transaction, $oldStatus, $newStatus, $request) {
                $updates = ['status' => $newStatus];
                if ($request->filled('tracking_number')) {
                    $updates['tracking_number'] = $request->tracking_number;
                }

                // Jika status berubah ke Selesai → kredit koin
                if ($newStatus === 'selesai' && $oldStatus !== 'selesai') {
                    $updates['coins_status'] = 'credited';

                    if ($transaction->coins_earned > 0) {
                        $userLevel = $transaction->user->loyalty_level;
                        $coins = $transaction->coins_earned;
                        
                        if ($userLevel === 'VIP') {
                            $coins += floor($coins * 0.20);
                        } elseif ($userLevel === 'Premium') {
                            $coins += floor($coins * 0.10);
                        }

                        RewardPoint::create([
                            'user_id'      => $transaction->user_id,
                            'points'       => $coins,
                            'type'         => 'earn',
                            'description'  => 'Koin dari pembelian ' . $transaction->receipt_number . ($coins > $transaction->coins_earned ? ' (Bonus ' . $userLevel . ')' : ''),
                            'reference_id' => $transaction->receipt_number,
                        ]);
                    }
                }

                $transaction->update($updates);
            });

            return back()->with('success', 'Status pesanan berhasil diperbarui ke: ' . ucfirst($newStatus));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, Transaction $transaction)
    {
        if (!$transaction->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $request->validate(['reason' => 'nullable|string|max:500']);

        try {
            DB::transaction(function () use ($request, $transaction) {
                foreach ($transaction->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                $wallet = $transaction->user->wallet;
                $wallet->increment('balance', $transaction->total_amount);

                WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'type'         => 'reward',
                    'amount'       => $transaction->total_amount,
                    'description'  => 'Refund pesanan ' . $transaction->receipt_number,
                    'reference_id' => $transaction->receipt_number,
                    'status'       => 'success',
                ]);

                if ($transaction->shop_voucher_id) {
                    ShopVoucherUsage::where('transaction_id', $transaction->id)->delete();
                    $transaction->shopVoucher()->decrement('used_count');
                }

                $transaction->update([
                    'status'              => 'dibatalkan',
                    'cancelled_at'        => now(),
                    'cancellation_reason' => $request->reason,
                ]);
            });

            return back()->with('success', 'Pesanan dibatalkan dan saldo dikembalikan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
