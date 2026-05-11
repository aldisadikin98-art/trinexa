<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        $user   = $request->user();
        $wallet = $user->wallet()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

        $recentTransactions = $wallet->walletTransactions()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Statistik bulan ini
        $monthlyStats = $wallet->walletTransactions()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'success')
            ->get();

        $totalIn  = $monthlyStats->whereIn('type', ['topup', 'reward', 'credit', 'recycle'])->sum('amount');
        $totalOut = $monthlyStats->whereIn('type', ['purchase', 'withdrawal'])->sum('amount');
        $txCount  = $monthlyStats->count();

        return view('user.wallet.show', compact(
            'wallet', 'recentTransactions', 'totalIn', 'totalOut', 'txCount'
        ));
    }

    public function topup()
    {
        $wallet = auth()->user()->wallet()->firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0]
        );
        return view('user.wallet.topup', compact('wallet'));
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:10000000',
        ]);

        $amount = (int) $request->amount;
        $user   = $request->user();

        try {
            DB::transaction(function () use ($user, $amount) {
                $wallet = $user->wallet()->firstOrCreate(
                    ['user_id' => $user->id],
                    ['balance' => 0]
                );

                $orderId = 'TOPUP-' . strtoupper(uniqid());

                WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'type'         => 'topup',
                    'amount'       => $amount,
                    'description'  => 'Top Up Saldo Harvestly',
                    'reference_id' => $orderId,
                    'status'       => 'success',
                ]);

                $wallet->increment('balance', $amount);
            });

            return redirect()->route('user.wallet.show')
                ->with('success', 'Top Up sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan Top Up. Silakan coba lagi.');
        }
    }

    public function withdraw()
    {
        $wallet = auth()->user()->wallet()->firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0]
        );
        return view('user.wallet.withdraw', compact('wallet'));
    }

    public function processWithdraw(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:50000|max:10000000',
            'destination' => 'required|string|in:gopay,ovo,dana,shopeepay,bank',
            'account'     => 'required|string|min:8|max:30',
        ]);

        $amount = (int) $request->amount;
        $user   = $request->user();

        try {
            DB::transaction(function () use ($user, $amount, $request) {
                $wallet = $user->wallet()->firstOrFail();

                if ($wallet->balance < $amount) {
                    throw new \Exception('Saldo tidak mencukupi untuk penarikan ini.');
                }

                $destLabel = match ($request->destination) {
                    'gopay'      => 'GoPay',
                    'ovo'        => 'OVO',
                    'dana'       => 'DANA',
                    'shopeepay'  => 'ShopeePay',
                    'bank'       => 'Transfer Bank',
                    default      => $request->destination,
                };

                $refId = 'WD-' . strtoupper(uniqid());

                WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'type'         => 'withdrawal',
                    'amount'       => $amount,
                    'description'  => 'Tarik Saldo ke ' . $destLabel . ' (' . $request->account . ')',
                    'reference_id' => $refId,
                    'status'       => 'success',
                ]);

                $wallet->decrement('balance', $amount);
            });

            return redirect()->route('user.wallet.show')
                ->with('success', 'Penarikan Rp ' . number_format($amount, 0, ',', '.') . ' berhasil diproses!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $user   = $request->user();
        $wallet = $user->wallet()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

        $filter = $request->query('filter', 'semua');

        $query = $wallet->walletTransactions()->orderBy('created_at', 'desc');

        match ($filter) {
            'masuk'    => $query->whereIn('type', ['topup', 'reward', 'credit', 'recycle']),
            'keluar'   => $query->whereIn('type', ['purchase', 'withdrawal']),
            'belanja'  => $query->where('type', 'purchase'),
            'topup'    => $query->where('type', 'topup'),
            'tarik'    => $query->where('type', 'withdrawal'),
            default    => null,
        };

        $transactions = $query->paginate(15)->appends(['filter' => $filter]);

        return view('user.wallet.history', compact('transactions', 'filter', 'wallet'));
    }
}
