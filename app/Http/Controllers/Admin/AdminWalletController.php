<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    public function index(Request $request)
    {
        $query = Wallet::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $wallets = $query->orderBy('balance', 'desc')->paginate(20);
        return view('admin.wallets.index', compact('wallets'));
    }
}
