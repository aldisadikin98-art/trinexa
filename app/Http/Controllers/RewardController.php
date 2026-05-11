<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $points = $user->rewardPoints()->orderBy('created_at', 'desc')->get();
        $totalPoints = $user->total_reward_points;

        return view('user.reward.index', compact('points', 'totalPoints'));
    }

    public function recycle()
    {
        return view('user.reward.recycle');
    }

    public function claimRecycle(Request $request)
    {
        $request->validate([
            'bottle_count' => 'required|integer|min:1|max:50',
            'photo' => 'required|image|max:2048'
        ]);

        $user = $request->user();
        $pointsEarned = $request->bottle_count * 50; // 50 points per bottle

        $user->rewardPoints()->create([
            'points' => $pointsEarned,
            'type' => 'earn',
            'description' => "Pengembalian {$request->bottle_count} botol kosong"
        ]);

        return redirect()->route('user.reward.index')->with('success', "Berhasil klaim! Anda mendapatkan $pointsEarned Poin.");
    }
}
