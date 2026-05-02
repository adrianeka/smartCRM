<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(
            Notification::latest('created_at')->get()
        );
    }

    public function store(Request $request)
    {
        $notif = Notification::create([
            'user_id' => 1,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return response()->json($notif);
    }

    public function markAsRead()
    {
        Notification::where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }
}
