<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{


#[OA\Get(
    path: "/api/v1/notifications",
    summary: "Team Notifications",
    tags: ["Collaboration"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Notification list"
        )
    ]
)]
    public function index()
    {
        $notifications = DB::table('notifications')
            ->where('notifiable_id', 1)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $notifications
        ]);
    }
}
