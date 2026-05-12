<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Product;
use App\Models\SavingGoal;
use App\Models\DailyMission;
use App\Models\UserMission;
use App\Models\RewardPoint;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        // Ambil 4 produk Naturea secara acak untuk slider dashboard
        $featuredProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Ambil saving goal aktif pertama milik user
        $savingGoal = SavingGoal::where('user_id', $user->id)
            ->where('is_completed', false)
            ->with('product')
            ->latest()
            ->first();

        // Siapkan Misi Harian
        if (DailyMission::count() === 0) {
            DailyMission::insert([
                ['title' => 'Login Hari Ini', 'description' => 'Login ke aplikasi Trinexa', 'type' => 'login', 'reward_xp' => 10, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Tonton 1 Video Skin School', 'description' => 'Belajar sesuatu yang baru hari ini', 'type' => 'watch_video', 'reward_xp' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['title' => 'Top Up Harvestly', 'description' => 'Siapkan dana untuk produk impian', 'type' => 'topup', 'reward_xp' => 100, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        $today = Carbon::today();
        $dailyMissions = DailyMission::where('is_active', true)->get();
        
        // Pastikan UserMission hari ini ter-create
        foreach ($dailyMissions as $mission) {
            UserMission::firstOrCreate([
                'user_id' => $user->id,
                'daily_mission_id' => $mission->id,
                'date' => $today,
            ]);
        }

        // Ambil misi user hari ini
        $userMissions = UserMission::with('dailyMission')
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->get();

        // Update login mission (kalau ada)
        $loginMission = $userMissions->first(function($um) {
            return $um->dailyMission->type === 'login';
        });

        if ($loginMission && !$loginMission->is_completed) {
            $loginMission->update(['is_completed' => true]);
            RewardPoint::create([
                'user_id' => $user->id,
                'points' => $loginMission->dailyMission->reward_xp,
                'type' => 'earn',
                'description' => 'Misi Harian: ' . $loginMission->dailyMission->title,
            ]);
        }

        return view('user.dashboard', compact('wallet', 'featuredProducts', 'savingGoal', 'userMissions'));
    }

    public function completeMission(Request $request, $missionId)
    {
        $user = Auth::user();
        $userMission = UserMission::where('user_id', $user->id)
            ->where('id', $missionId)
            ->where('date', Carbon::today())
            ->firstOrFail();

        if (!$userMission->is_completed) {
            $userMission->update(['is_completed' => true]);
            RewardPoint::create([
                'user_id' => $user->id,
                'points' => $userMission->dailyMission->reward_xp,
                'type' => 'earn',
                'description' => 'Misi Harian: ' . $userMission->dailyMission->title,
            ]);
        }

        return redirect()->back();
    }

    // Fungsi untuk Top Up (Tambah Saldo Rp 50.000)
    public function topUp()
    {
        $user = Auth::user();
        
        // Cari dompetnya
        $wallet = Wallet::where('user_id', $user->id)->first();

        if ($wallet) {
            // Tambah saldonya Rp 50.000
            $wallet->balance += 50000;
            $wallet->save();
        }

        // Kembalikan ke halaman dashboard dengan pesan sukses
        return redirect()->back()->with('success', 'Hore! Saldo Harvestly berhasil ditambah Rp 50.000 🎉');
    }
}