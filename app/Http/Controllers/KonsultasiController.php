<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\FaceScanResult;

class KonsultasiController extends Controller
{
    public function index()
    {
        $recentChats = ChatSession::where('user_id', auth()->id())
            ->withCount('messages')
            ->latest()
            ->take(5)
            ->get();

        $recentScans = FaceScanResult::where('user_id', auth()->id())
            ->latest()
            ->take(3)
            ->get();

        $latestScan = $recentScans->first();

        return view('konsultasi.index', compact('recentChats', 'recentScans', 'latestScan'));
    }
}
