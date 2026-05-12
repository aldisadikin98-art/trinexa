<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SiteNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $notifications = SiteNotification::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhereNull('user_id');
        })
        ->latest()
        ->paginate(15);
        
        return view('user.notifications.index', compact('notifications'));
    }

    public function markAsRead(SiteNotification $notification)
    {
        if ($notification->user_id === auth()->id()) {
            $notification->update(['read_at' => now()]);
        }
        
        if ($notification->link) {
            return redirect($notification->link);
        }
        
        return back();
    }
}
