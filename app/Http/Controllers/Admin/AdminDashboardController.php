<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\RewardPoint;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisWeekStart = Carbon::now()->startOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        // ─── Stat Cards Row 1 ───────────────────────────────────────
        $totalOrders = Transaction::whereNotNull('receipt_number')->count();
        $todayOrders = Transaction::whereNotNull('receipt_number')->whereDate('created_at', $today)->count();

        $pendingOrders = Transaction::where('status', 'pending')->count();

        $revenue = Transaction::whereIn('status', ['pending', 'diproses', 'dikirim', 'selesai'])->sum('total_amount');
        $weekRevenue = Transaction::whereIn('status', ['pending', 'diproses', 'dikirim', 'selesai'])
            ->where('created_at', '>=', $thisWeekStart)->sum('total_amount');

        $pendingReviews = Review::where('status', 'pending')->count();

        // ─── Stat Cards Row 2 ───────────────────────────────────────
        $totalUsers = User::where('role', 'user')->count();
        $newUsersThisWeek = User::where('role', 'user')->where('created_at', '>=', $thisWeekStart)->count();

        $activeProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('is_active', true)->where('stock', '<', 5)->where('stock', '>', 0)->count();

        $totalCoins = RewardPoint::where('type', 'earn')->sum('points') - RewardPoint::where('type', 'redeem')->sum('points');

        $totalHarvestly = Wallet::sum('balance');

        // ─── Chart: Pendapatan 7 Hari Terakhir ─────────────────────
        $dailyRevenue = [];
        $dailyLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dailyLabels[] = $date->isoFormat('ddd');
            $dailyRevenue[] = Transaction::whereIn('status', ['pending', 'diproses', 'dikirim', 'selesai'])
                ->whereDate('created_at', $date)
                ->sum('total_amount');
        }

        // ─── Chart: Distribusi Status ───────────────────────────────
        $statusCounts = Transaction::whereNotNull('receipt_number')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ─── Produk Terlaris ────────────────────────────────────────
        $topProducts = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereNotNull('transactions.receipt_number')
            ->select('products.id', 'products.name', 'products.images', 'products.price',
                DB::raw('SUM(transaction_items.quantity) as total_sold'),
                DB::raw('SUM(transaction_items.quantity * transaction_items.price) as total_revenue'))
            ->groupBy('products.id', 'products.name', 'products.images', 'products.price')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // ─── Pesanan Terbaru ────────────────────────────────────────
        $recentOrders = Transaction::with(['user', 'items.product'])
            ->whereNotNull('receipt_number')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'todayOrders',
            'pendingOrders',
            'revenue', 'weekRevenue',
            'pendingReviews',
            'totalUsers', 'newUsersThisWeek',
            'activeProducts', 'lowStockProducts',
            'totalCoins',
            'totalHarvestly',
            'dailyRevenue', 'dailyLabels',
            'statusCounts',
            'topProducts',
            'recentOrders'
        ));
    }
}
