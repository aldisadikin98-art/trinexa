<?php

namespace App\Http\Controllers;

use App\Models\KareblaProduct;
use App\Models\KareblaRedemption;
use App\Models\RewardPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KareblaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userPoints = $user->total_reward_points;
        $userLevel = $user->loyalty_level;
        $transactionCount = $user->transactions()->where('status', 'selesai')->count();
        $joinDate = $user->created_at->format('M Y');
        
        $totalRedeemed = $user->kareblaRedemptions()->count();
        $activeRedeemed = $user->kareblaRedemptions()->whereIn('status', ['menunggu', 'diproses', 'dikirim'])->count();

        // Target for next level
        $nextLevelTarget = 3;
        $progress = 0;
        if ($userLevel === 'Member') {
            $nextLevelTarget = 3;
            $progress = ($transactionCount / $nextLevelTarget) * 100;
        } elseif ($userLevel === 'Loyal') {
            $nextLevelTarget = 10;
            $progress = (($transactionCount - 3) / 7) * 100;
        } elseif ($userLevel === 'Premium') {
            $nextLevelTarget = 25;
            $progress = (($transactionCount - 10) / 15) * 100;
        } else {
            $progress = 100;
        }
        $progress = min(100, max(0, $progress));

        $filter = $request->query('filter', 'semua');
        $query = KareblaProduct::where('is_active', true);

        if ($filter === 'bisa_ditukar') {
            $query->where('coin_price', '<=', $userPoints)->where('stock', '>', 0);
        } elseif ($filter === 'terbaru') {
            $query->latest();
        } elseif ($filter === 'terendah') {
            $query->orderBy('coin_price', 'asc');
        } elseif ($filter === 'tertinggi') {
            $query->orderBy('coin_price', 'desc');
        }

        $products = $query->get();

        return view('karebla.index', compact('user', 'userPoints', 'userLevel', 'transactionCount', 'joinDate', 'totalRedeemed', 'activeRedeemed', 'progress', 'products', 'filter'));
    }

    public function show(Request $request, KareblaProduct $product)
    {
        if (!$product->is_active) abort(404);

        $user = $request->user();
        $userPoints = $user->total_reward_points;

        return view('karebla.show', compact('product', 'user', 'userPoints'));
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'karebla_product_id' => 'required|exists:karebla_products,id'
        ]);

        $user = $request->user();
        if (empty($user->address)) {
            return response()->json([
                'success' => false,
                'message' => 'Lengkapi alamat pengiriman di profil Anda terlebih dahulu.'
            ], 422);
        }

        try {
            $redemption = DB::transaction(function () use ($request, $user) {
                // Lock for update to prevent race conditions
                $product = KareblaProduct::where('id', $request->karebla_product_id)->lockForUpdate()->first();
                
                if (!$product->is_active) {
                    throw new \Exception('Produk sudah tidak aktif.');
                }
                
                if ($product->stock <= 0) {
                    throw new \Exception('Maaf, stok produk sudah habis.');
                }

                $userPoints = $user->total_reward_points;
                if ($userPoints < $product->coin_price) {
                    throw new \Exception('Koin Anda tidak cukup untuk menukar produk ini.');
                }

                $todayCount = KareblaRedemption::whereDate('created_at', today())->count() + 1;
                $receiptNumber = 'KRB-' . date('Ymd') . '-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

                // Deduct stock
                $product->decrement('stock');

                // Deduct points
                RewardPoint::create([
                    'user_id' => $user->id,
                    'points' => $product->coin_price,
                    'type' => 'redeem',
                    'description' => 'Penukaran ' . $product->name,
                    'reference_id' => $receiptNumber,
                ]);

                // Create redemption
                return KareblaRedemption::create([
                    'user_id' => $user->id,
                    'karebla_product_id' => $product->id,
                    'receipt_number' => $receiptNumber,
                    'coins_used' => $product->coin_price,
                    'shipping_address' => $user->address,
                    'status' => 'menunggu'
                ]);
            });

            return response()->json([
                'success' => true,
                'redirect' => route('karebla.success', $redemption->id)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function success(Request $request, KareblaRedemption $redemption)
    {
        if ($redemption->user_id !== $request->user()->id) abort(403);
        
        $redemption->load('product');
        return view('karebla.success', compact('redemption'));
    }

    public function history(Request $request)
    {
        $query = $request->user()->kareblaRedemptions()->with('product')->latest();
        
        $filter = $request->query('status', 'semua');
        if ($filter !== 'semua') {
            $query->where('status', $filter);
        }

        $redemptions = $query->paginate(10);
        return view('karebla.history', compact('redemptions', 'filter'));
    }

    public function historyDetail(Request $request, KareblaRedemption $redemption)
    {
        if ($redemption->user_id !== $request->user()->id) abort(403);
        
        $redemption->load('product');
        return view('karebla.history_detail', compact('redemption'));
    }
}
