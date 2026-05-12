<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteNotification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = SiteNotification::with('user')->latest()->paginate(10);
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.notifications.index', compact('notifications', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,danger',
            'target' => 'required|in:all,specific',
            'user_id' => 'required_if:target,specific|exists:users,id',
            'link' => 'nullable'
        ]);

        SiteNotification::create([
            'user_id' => $request->target === 'all' ? null : $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'link' => $request->link,
        ]);

        return back()->with('success', 'Notifikasi berhasil dikirim!');
    }

    public function destroy(SiteNotification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus!');
    }
}
