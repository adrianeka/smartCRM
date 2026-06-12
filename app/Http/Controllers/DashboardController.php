<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Notification;
use App\Models\User;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    #[OA\Get(
        path: '/api/v1/dashboard/summary',
        summary: 'Dashboard Summary',
        tags: ['Dashboard'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dashboard statistics'
            ),
        ]
    )]
    public function summary()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_customers' => Customer::count(),
            'notifications_unread' => Notification::whereNull('read_at')->count(),
        ]);
    }

    public function overview()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'welcome_message' => 'Welcome to SmartCRM',
                'role' => 'Admin',
                'quick_links' => [
                    'dashboard',
                    'customers',
                    'notifications',
                    'auth'
                ]
            ]
        ]);
    }
}
