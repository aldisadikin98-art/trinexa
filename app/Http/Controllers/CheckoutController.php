<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ShopVoucher;
use App\Models\ShopVoucherUsage;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // POST /checkout/quick
    public function quickBuy(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup.'
            ], 422);
        }

        session(['quick_buy' => [
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
        ]]);

        return response()->json(['success' => true]);
    }

    // POST /checkout/prepare
    public function prepare(Request $request)
    {
        $request->validate([
            'cart_item_ids'   => 'required|array',
            'cart_item_ids.*' => 'exists:cart_items,id',
        ]);

        session(['checkout_items' => $request->cart_item_ids]);

        return redirect()->route('checkout.index');
    }

    // GET /checkout
    public function index(Request $request)
    {
        $user = $request->user();
        $mode = $request->query('mode');
        
        $items = collect();
        
        if ($mode === 'quick') {
            $quickData = session('quick_buy');
            if (!$quickData) {
                return redirect()->route('shop.index')->with('error', 'Sesi checkout kadaluarsa.');
            }
            
            $product = Product::find($quickData['product_id']);
            if (!$product) return redirect()->route('shop.index');
            
            // Create a fake CartItem object for unified view rendering
            $tempItem = new CartItem([
                'product_id' => $product->id,
                'quantity'   => $quickData['quantity'],
            ]);
            $tempItem->setRelation('product', $product);
            $items->push($tempItem);
            
        } else {
            $selectedIds = session('checkout_items', []);
            if (empty($selectedIds)) {
                return redirect()->route('cart.index')->with('error', 'Tidak ada item dipilih.');
            }
            $items = $user->cartItems()->with('product')->whereIn('id', $selectedIds)->get();
        }

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong.');
        }

        $subtotal = $items->sum(fn($item) => $item->quantity * $item->product->price);
        $shipping_fee = floor($subtotal * 0.05); // 5% ongkir
        $wallet   = $user->wallet()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
        
        // Ambil voucher yang aktif dan tersedia
        $userVouchers = ShopVoucher::where('is_active', true)
            ->where('quota', '>', 0)
            ->where('expired_at', '>', now())
            ->where('min_purchase', '<=', $subtotal)
            ->get()
            ->filter(function($v) use ($user) {
                // Return true if user hasn't used this voucher
                return !ShopVoucherUsage::where('user_id', $user->id)
                    ->where('shop_voucher_id', $v->id)->exists();
            });

        $userLoyaltyPoints = $user->loyalty_points;

        return view('checkout.index', compact('items', 'subtotal', 'shipping_fee', 'wallet', 'userVouchers', 'user', 'mode', 'userLoyaltyPoints'));
    }

    // POST /checkout/voucher (AJAX)
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'subtotal'     => 'required|numeric'
        ]);

        $subtotal = $request->subtotal;
        $voucher = ShopVoucher::where('code', strtoupper($request->voucher_code))->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan.']);
        }

        if (!$voucher->is_active || $voucher->expired_at < now() || $voucher->quota <= 0) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak aktif atau habis.']);
        }

        if ($subtotal < $voucher->min_purchase) {
            return response()->json(['success' => false, 'message' => 'Minimal belanja Rp ' . number_format($voucher->min_purchase, 0, ',', '.') . ' tidak terpenuhi.']);
        }

        $alreadyUsed = ShopVoucherUsage::where('user_id', auth()->id())
            ->where('shop_voucher_id', $voucher->id)->exists();
            
        if ($alreadyUsed) {
            return response()->json(['success' => false, 'message' => 'Voucher sudah pernah Anda gunakan.']);
        }

        $discount = 0;
        if ($voucher->type === 'percent') {
            $discount = ($subtotal * $voucher->value) / 100;
            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
        } else {
            $discount = $voucher->value;
        }

        $discount = min($discount, $subtotal); // Don't discount more than subtotal
        $totalAfter = $subtotal - $discount;

        return response()->json([
            'success'         => true,
            'discount_amount' => $discount,
            'total_after'     => $totalAfter,
            'message'         => 'Voucher berhasil diterapkan!'
        ]);
    }

    // POST /checkout/process
    public function store(Request $request)
    {
        $user = $request->user();

        if (empty($user->address)) {
            return back()->with('error', 'Lengkapi alamat pengiriman terlebih dahulu.');
        }

        try {
            $transaction = DB::transaction(function () use ($request, $user) {
                $mode = $request->mode;
                $items = collect();
                
                // Get Items
                if ($mode === 'quick') {
                    $quickData = session('quick_buy');
                    if (!$quickData) throw new \Exception('Sesi kadaluarsa. Silakan ulang belanja.');
                    
                    $product = Product::findOrFail($quickData['product_id']);
                    $tempItem = new CartItem([
                        'product_id' => $product->id,
                        'quantity'   => $quickData['quantity'],
                    ]);
                    $tempItem->setRelation('product', $product);
                    $items->push($tempItem);
                } else {
                    $itemIds = $request->item_ids ? explode(',', $request->item_ids) : [];
                    $items = $user->cartItems()->with('product')->whereIn('id', $itemIds)->get();
                    if ($items->isEmpty()) throw new \Exception('Tidak ada produk untuk dicheckout.');
                }

                // 1. Validasi Stok
                $subtotal = 0;
                foreach ($items as $item) {
                    if ($item->product->stock < $item->quantity) {
                        throw new \Exception('Stok produk ' . $item->product->name . ' tidak mencukupi.');
                    }
                    $subtotal += ($item->quantity * $item->product->price);
                }

                // 2. Voucher
                $discount = 0;
                $voucherId = null;
                if ($request->filled('voucher_code')) {
                    $voucher = ShopVoucher::where('code', strtoupper($request->voucher_code))->first();
                    if (!$voucher || !$voucher->is_active || $voucher->quota <= 0 || $voucher->expired_at < now()) {
                        throw new \Exception('Voucher tidak valid atau kadaluarsa.');
                    }
                    if ($subtotal < $voucher->min_purchase) {
                        throw new \Exception('Minimal belanja voucher tidak terpenuhi.');
                    }
                    if (ShopVoucherUsage::where('user_id', $user->id)->where('shop_voucher_id', $voucher->id)->exists()) {
                        throw new \Exception('Voucher sudah Anda gunakan.');
                    }
                    
                    if ($voucher->type === 'percent') {
                        $discount = ($subtotal * $voucher->value) / 100;
                        if ($voucher->max_discount) $discount = min($discount, $voucher->max_discount);
                    } else {
                        $discount = $voucher->value;
                    }
                    $discount = min($discount, $subtotal);
                    $voucherId = $voucher->id;
                    
                    // Decrease quota
                    $voucher->decrement('quota');
                    
                    // 11. Tandai voucher
                    ShopVoucherUsage::create([
                        'user_id' => $user->id,
                        'shop_voucher_id' => $voucherId,
                        'transaction_id' => 0 // Temporary, will update below
                    ]);
                }

                $shipping_fee = floor($subtotal * 0.05);
                $totalBayar = $subtotal + $shipping_fee - $discount;

                // 2.5 Loyalty Points
                $coinsUsed = 0;
                if ($request->use_coins == '1' && $user->loyalty_points > 0) {
                    $availableCoins = $user->loyalty_points;
                    // Max coins used is totalBayar
                    $coinsUsed = min($availableCoins, $totalBayar);
                    if ($coinsUsed > 0) {
                        $totalBayar -= $coinsUsed;
                        // Deduct points
                        app(\App\Services\LoyaltyService::class)->addPoints($user, -$coinsUsed, 'checkout_discount', 'Diskon belanja Naturea');
                    }
                }

                // 3. Saldo Wallet
                $wallet = $user->wallet()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
                if ($wallet->balance < $totalBayar) {
                    throw new \Exception('Saldo Harvestly tidak mencukupi.');
                }

                // 5. Generate Receipt
                $todayCount = Transaction::whereDate('created_at', today())->count() + 1;
                $receiptNumber = 'TRX-NATUREA-' . date('Ymd') . '-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

                // 6. Buat Transaction
                $transaction = Transaction::create([
                    'user_id'          => $user->id,
                    'receipt_number'   => $receiptNumber,
                    'total_amount'     => $totalBayar,
                    'status'           => 'pending',
                    'payment_method'   => 'wallet',
                    'shipping_address' => $user->address,
                    'shipping_cost'    => $shipping_fee,
                    'shop_voucher_id'  => $voucherId,
                    'discount_amount'  => $discount,
                    'coins_used'       => $coinsUsed,
                    'coins_earned'     => floor($totalBayar / 10000),
                    'coins_status'     => 'pending',
                ]);

                if ($voucherId) {
                    ShopVoucherUsage::where('user_id', $user->id)
                        ->where('shop_voucher_id', $voucherId)
                        ->where('transaction_id', 0)
                        ->update(['transaction_id' => $transaction->id]);
                }

                // 7. Buat TransactionItems & 10. Kurangi Stok
                foreach ($items as $item) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id'     => $item->product_id,
                        'quantity'       => $item->quantity,
                        'price'          => $item->product->price,
                    ]);
                    $item->product->decrement('stock', $item->quantity);
                }

                // 8. Potong Saldo
                $wallet->decrement('balance', $totalBayar);

                // 9. Wallet Transaction
                WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'type'         => 'purchase',
                    'amount'       => $totalBayar,
                    'description'  => 'Belanja Naturea #' . $receiptNumber,
                    'reference_id' => $receiptNumber,
                    'status'       => 'success',
                ]);

                // 12. Hapus Cart Item (jika normal mode)
                if ($mode !== 'quick') {
                    $itemIds = $items->pluck('id');
                    $user->cartItems()->whereIn('id', $itemIds)->delete();
                } else {
                    // 13. Hapus session quick_buy
                    session()->forget('quick_buy');
                }

                // 13.5 Catat activity shop agar badge "First Buy" bisa terbuka
                app(\App\Services\LoyaltyService::class)->addPoints($user, 0, 'shop', 'Menyelesaikan pesanan #' . $receiptNumber);

                // 13.6 Kredit Koin Karebla langsung (payment wallet = instan)
                $baseCoins = floor($totalBayar / 10000);
                if ($baseCoins > 0) {
                    $userLevel = $user->loyalty_level;
                    $bonusCoins = 0;
                    if ($userLevel === 'VIP') {
                        $bonusCoins = floor($baseCoins * 0.20);
                    } elseif ($userLevel === 'Premium') {
                        $bonusCoins = floor($baseCoins * 0.10);
                    }
                    $totalCoins = $baseCoins + $bonusCoins;

                    \App\Models\RewardPoint::create([
                        'user_id'      => $user->id,
                        'points'       => $totalCoins,
                        'type'         => 'earn',
                        'description'  => 'Koin dari pembelian #' . $receiptNumber . ($bonusCoins > 0 ? ' (Bonus ' . $userLevel . ' +' . $bonusCoins . ')' : ''),
                        'reference_id' => $receiptNumber,
                    ]);

                    // Update coins_status menjadi credited
                    $transaction->update(['coins_status' => 'credited']);
                }

                return $transaction;
            });

            // 14. Redirect
            return redirect()->route('checkout.success', $transaction->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);
        $transaction->load(['items.product', 'shopVoucher']);
        return view('checkout.sukses', compact('transaction'));
    }
}
