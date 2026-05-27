<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Notification::latest('created_at')->get()
        ]);
    }

    public function store(Request $request)
    {
        $notif = Notification::create([
            'user_id' => 1,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'source_module' => $request->source_module,
            'priority' => $request->priority ?? 'normal',
            'action_url' => $request->action_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification created',
            'data' => $notif
        ]);
    }

    public function markAsRead($id)
    {
        $notif = Notification::findOrFail($id);

        $notif->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

public function markAllAsRead()
{
    Notification::query()
        ->where('is_read', false)
        ->update([
            'is_read' => true,
            'read_at' => now()
        ]);

    return response()->json([
        'success' => true,
        'message' => 'All notifications marked as read'
    ]);
}

public function unreadCount()
{
    $count = Notification::query()
        ->where('is_read', false)
        ->count();

    return response()->json([
        'success' => true,
        'unread_count' => $count
    ]);
}
}
