<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Badge;
use App\Services\LoyaltyService;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Selalu panggil updateLoginStreak (di dalamnya sudah ada validasi isToday)
        $this->loyaltyService->updateLoginStreak($user);
        $user->refresh();

        $points = $user->loyalty_points;
        $level = $user->loyalty_level;
        $streak = $user->loginStreak;
        $badges = Badge::all();
        $userBadgeIds = $user->badges()->pluck('badges.id')->toArray();
        
        // Get all vouchers, might need to sort by level required
        $vouchers = Voucher::all();
        
        $history = $user->loyaltyPoints()->orderBy('created_at', 'desc')->take(10)->get();

        return view('user.loyalty.index', compact('points', 'level', 'streak', 'badges', 'userBadgeIds', 'vouchers', 'history'));
    }

    public function redeemVoucher(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
        ]);

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $this->loyaltyService->redeemVoucher($user, $request->voucher_id);
            return back()->with('success', 'Voucher berhasil ditukar!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getHistory()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $history = $user->loyaltyPoints()->orderBy('created_at', 'desc')->paginate(15);
        // We will just return the main view or JSON. Let's return JSON for simplicity if it's called via ajax.
        return response()->json($history);
    }
}
