<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Ambil data notifikasi untuk dropdown (AJAX)
    public function fetch()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();

        $count = Notification::where('user_id', auth()->id())->whereNull('read_at')->count();

        return response()->json([
            'count' => $count,
            'items' => $notifications
        ]);
    }

    // Tandai satu notifikasi sudah dibaca
    public function markAsRead($id)
    {
        $notif = Notification::where('id', $id)->where('user_id', auth()->id())->first();
        if ($notif) {
            $notif->update(['read_at' => now()]);
            return redirect($notif->url ?? '/');
        }
        return back();
    }
}