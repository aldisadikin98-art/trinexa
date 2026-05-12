<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function toggle(User $user)
    {
        // Simple toggle for something like 'is_active' if it exists, 
        // but User model doesn't have it yet. 
        // For now, just a placeholder for future management.
        return back()->with('info', 'Fitur manajemen status user akan segera hadir.');
    }
}
