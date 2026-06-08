<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markRead(\App\Models\Notification $notification)
    {
        $notification->markAsRead();
        return back();
    }

    public function markAllRead()
    {
        \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function destroy(\App\Models\Notification $notification)
    {
        $notification->delete();
        return back();
    }
}
