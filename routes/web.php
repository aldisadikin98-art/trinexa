<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FaceScanController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ════════════════════════════════════════════════════════
// ADMIN ROUTES (Auth + Admin Middleware)
// ════════════════════════════════════════════════════════
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class)->only(['index', 'store', 'destroy']);
    Route::get('users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::get('wallets', [\App\Http\Controllers\Admin\AdminWalletController::class, 'index'])->name('wallets.index');

    // Produk Naturea
    Route::resource('produk', \App\Http\Controllers\Admin\AdminProductController::class);
    Route::patch('produk/{product}/toggle', [\App\Http\Controllers\Admin\AdminProductController::class, 'toggle'])->name('produk.toggle');

    // Pesanan
    Route::get('pesanan', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('pesanan.index');
    Route::get('pesanan/{transaction}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('pesanan.show');
    Route::patch('pesanan/{transaction}/status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('pesanan.status');
    Route::patch('pesanan/{transaction}/cancel', [\App\Http\Controllers\Admin\AdminOrderController::class, 'cancel'])->name('pesanan.cancel');

    // Moderasi Ulasan
    Route::get('ulasan', [\App\Http\Controllers\Admin\AdminReviewController::class, 'index'])->name('ulasan.index');
    Route::patch('ulasan/{review}/approve', [\App\Http\Controllers\Admin\AdminReviewController::class, 'approve'])->name('ulasan.approve');
    Route::patch('ulasan/{review}/reject', [\App\Http\Controllers\Admin\AdminReviewController::class, 'reject'])->name('ulasan.reject');
    Route::delete('ulasan/{review}', [\App\Http\Controllers\Admin\AdminReviewController::class, 'destroy'])->name('ulasan.destroy');
    Route::post('ulasan/{review}/balas', [\App\Http\Controllers\Admin\AdminReviewController::class, 'reply'])->name('ulasan.reply');

    // Voucher Belanja
    Route::resource('voucher', \App\Http\Controllers\Admin\AdminVoucherController::class);

    // Karebla
    Route::prefix('karebla')->name('karebla.')->group(function () {
        Route::resource('produk', \App\Http\Controllers\Admin\AdminKareblaProductController::class)->parameters(['produk' => 'produk']);
        Route::patch('produk/{product}/toggle', [\App\Http\Controllers\Admin\AdminKareblaProductController::class, 'toggle'])->name('produk.toggle');

        Route::get('penukaran', [\App\Http\Controllers\Admin\AdminKareblaRedemptionController::class, 'index'])->name('penukaran.index');
        Route::get('penukaran/{redemption}', [\App\Http\Controllers\Admin\AdminKareblaRedemptionController::class, 'show'])->name('penukaran.show');
        Route::patch('penukaran/{redemption}/status', [\App\Http\Controllers\Admin\AdminKareblaRedemptionController::class, 'updateStatus'])->name('penukaran.status');
    });

    // Admin Dermatology
    Route::prefix('dermatology')->name('dermatology.')->group(function () {
        Route::resource('/', \App\Http\Controllers\AdminDermatologyController::class)->parameters(['' => 'content']);
        Route::patch('/{content}/toggle-featured', [\App\Http\Controllers\AdminDermatologyController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::patch('/{content}/toggle-published', [\App\Http\Controllers\AdminDermatologyController::class, 'togglePublished'])->name('toggle-published');
    });

    // Laporan Keuangan
    Route::prefix('keuangan')->name('financial.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminFinancialReportController::class, 'index'])->name('index');
        Route::get('/pengeluaran', [\App\Http\Controllers\Admin\AdminFinancialReportController::class, 'expenses'])->name('expenses');
        Route::post('/pengeluaran', [\App\Http\Controllers\Admin\AdminFinancialReportController::class, 'storeExpense'])->name('expenses.store');
        Route::patch('/pengeluaran/{expense}', [\App\Http\Controllers\Admin\AdminFinancialReportController::class, 'updateExpense'])->name('expenses.update');
        Route::delete('/pengeluaran/{expense}', [\App\Http\Controllers\Admin\AdminFinancialReportController::class, 'destroyExpense'])->name('expenses.destroy');
        Route::get('/rekap', [\App\Http\Controllers\Admin\AdminFinancialReportController::class, 'recap'])->name('recap');
        Route::get('/export', [\App\Http\Controllers\Admin\AdminFinancialReportController::class, 'export'])->name('export');
    });
});

// ════════════════════════════════════════════════════════
// USER DASHBOARD ROUTES (Auth + User Role)
// ════════════════════════════════════════════════════════
Route::middleware(['auth', 'verified', 'role:user'])->prefix('dashboard')->group(function () {

    Route::get('/', [UserController::class, 'index'])->name('user.dashboard');
    Route::post('/top-up', [UserController::class, 'topUp'])->name('user.topup');
    Route::post('/mission/{id}/complete', [UserController::class, 'completeMission'])->name('user.mission.complete');

    // Face Scan
    Route::get('/face-scan', [FaceScanController::class, 'index'])->name('user.face-scan.index');
    Route::post('/face-scan', [FaceScanController::class, 'analyze'])->name('user.face-scan.analyze');

    // Dermatology (formerly Skin School)
    Route::get('/dermatology', [\App\Http\Controllers\DermatologyController::class, 'index'])->name('dermatology.index');
    Route::get('/dermatology/{slug}', [\App\Http\Controllers\DermatologyController::class, 'show'])->name('dermatology.show');
    Route::post('/dermatology/{content}/complete', [\App\Http\Controllers\DermatologyController::class, 'complete'])->name('dermatology.complete');
    Route::post('/dermatology/{content}/bookmark', [\App\Http\Controllers\DermatologyController::class, 'bookmark'])->name('dermatology.bookmark');

    // Wallet
    Route::get('/wallet', [\App\Http\Controllers\WalletController::class, 'show'])->name('user.wallet.show');
    Route::get('/wallet/topup', [\App\Http\Controllers\WalletController::class, 'topup'])->name('user.wallet.topup');
    Route::post('/wallet/topup', [\App\Http\Controllers\WalletController::class, 'processTopup'])->name('user.wallet.processTopup');
    Route::get('/wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'withdraw'])->name('user.wallet.withdraw');
    Route::post('/wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'processWithdraw'])->name('user.wallet.processWithdraw');
    Route::get('/wallet/history', [\App\Http\Controllers\WalletController::class, 'history'])->name('user.wallet.history');

    // Legacy shop (redirect ke /belanja)
    Route::get('/shop', function () { return redirect()->route('shop.index'); })->name('user.shop.index');
    Route::get('/shop/{id}', function ($id) {
        $product = \App\Models\Product::find($id);
        if ($product && $product->slug) return redirect()->route('shop.show', $product->slug);
        return redirect()->route('shop.index');
    })->name('user.shop.show');

    // Loyalty
    Route::get('/loyalty', [\App\Http\Controllers\User\LoyaltyController::class, 'index'])->name('user.loyalty.index');
    Route::post('/loyalty/redeem', [\App\Http\Controllers\User\LoyaltyController::class, 'redeemVoucher'])->name('user.loyalty.redeem');
    Route::get('/loyalty/history', [\App\Http\Controllers\User\LoyaltyController::class, 'getHistory'])->name('user.loyalty.history');
});

// ════════════════════════════════════════════════════════
// BELANJA ROUTES (Auth — clean URLs)
// ════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {

    // Katalog & Detail Produk
    Route::get('/belanja', [\App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
    Route::get('/belanja/{product:slug}', [\App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');

    // Keranjang
    Route::get('/keranjang', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang', [\App\Http\Controllers\CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{cartItem}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{cartItem}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/keranjang', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/prepare', [\App\Http\Controllers\CheckoutController::class, 'prepare'])->name('checkout.prepare');
    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/quick', [\App\Http\Controllers\CheckoutController::class, 'quickBuy'])->name('checkout.quick');
    Route::post('/checkout/voucher', [\App\Http\Controllers\CheckoutController::class, 'applyVoucher'])->name('checkout.voucher');
    Route::get('/checkout/sukses/{transaction}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

    // Transaksi
    Route::get('/pesanan', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/pesanan/{transaction}', [\App\Http\Controllers\TransactionController::class, 'show'])->name('transaction.show');
    Route::post('/pesanan/{transaction}/batalkan', [\App\Http\Controllers\TransactionController::class, 'cancel'])->name('transaction.cancel');

    // Ulasan
    Route::post('/ulasan', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
    Route::post('/ulasan/{review}/helpful', [\App\Http\Controllers\ReviewController::class, 'helpful'])->name('review.helpful');

    // Voucher Check (AJAX)
    Route::post('/voucher/check', [\App\Http\Controllers\VoucherController::class, 'check'])->name('voucher.check');

    // Cart count API (untuk badge navbar)
    Route::get('/api/cart-count', [\App\Http\Controllers\CartController::class, 'count'])->name('cart.count');

    // Karebla - Trinexa Exclusive Rewards
    Route::prefix('karebla')->name('karebla.')->group(function () {
        Route::get('/', [\App\Http\Controllers\KareblaController::class, 'index'])->name('index');
        Route::get('/produk/{product:slug}', [\App\Http\Controllers\KareblaController::class, 'show'])->name('show');
        Route::post('/tukar', [\App\Http\Controllers\KareblaController::class, 'redeem'])->name('redeem');
        Route::get('/sukses/{redemption}', [\App\Http\Controllers\KareblaController::class, 'success'])->name('success');
        Route::get('/riwayat', [\App\Http\Controllers\KareblaController::class, 'history'])->name('history');
        Route::get('/riwayat/{redemption}', [\App\Http\Controllers\KareblaController::class, 'historyDetail'])->name('history.detail');
    });
    Route::get('/notifikasi', [\App\Http\Controllers\User\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/{notification}/read', [\App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// ════════════════════════════════════════════════════════
// PROFILE ROUTES
// ════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ════════════════════════════════════════════════════════
// MIDTRANS WEBHOOK
// ════════════════════════════════════════════════════════
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handleWebhook']);

// ════════════════════════════════════════════════════════
// KONSULTASI AI "AURA" ROUTES
// ════════════════════════════════════════════════════════
Route::middleware('auth')->prefix('konsultasi')->name('konsultasi.')->group(function () {
    // Hub utama
    Route::get('/', [\App\Http\Controllers\KonsultasiController::class, 'index'])->name('index');

    // Chat AI
    Route::get('/chat', [\App\Http\Controllers\ChatAIController::class, 'index'])->name('chat.index');
    Route::post('/chat/session', [\App\Http\Controllers\ChatAIController::class, 'createSession'])->name('chat.session');
    Route::post('/chat/quick', [\App\Http\Controllers\ChatAIController::class, 'quickChat'])->name('chat.quick');
    Route::get('/chat/{session}', [\App\Http\Controllers\ChatAIController::class, 'show'])->name('chat.show');
    Route::post('/chat/{session}/send', [\App\Http\Controllers\ChatAIController::class, 'send'])->name('chat.send');
    Route::delete('/chat/{session}', [\App\Http\Controllers\ChatAIController::class, 'destroy'])->name('chat.destroy');

    // Face Scan AI
    Route::get('/face-scan', [\App\Http\Controllers\FaceScanAIController::class, 'index'])->name('face-scan.index');
    Route::post('/face-scan/analyze', [\App\Http\Controllers\FaceScanAIController::class, 'analyze'])->name('face-scan.analyze');
    Route::get('/face-scan/{result}', [\App\Http\Controllers\FaceScanAIController::class, 'show'])->name('face-scan.show');
    Route::delete('/face-scan/{result}', [\App\Http\Controllers\FaceScanAIController::class, 'destroy'])->name('face-scan.destroy');
});

// SOCIALITE ROUTES
Route::get('auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';