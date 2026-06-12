<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


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
        'status' => 'success',
        'data' => [
            'total_users' => User::count(),

            'total_customers' => Customer::count(),

            'notifications_unread' => Notification::whereNull('read_at')->count(),

            'customers_today' => Customer::whereDate('created_at',Carbon::today())->count(),

            'customers_this_month' => Customer::whereMonth('created_at',Carbon::now()->month)->whereYear('created_at',Carbon::now()->year
            )->count(),
        ]
    ]);
}

#[OA\Get(
    path: "/api/v1/dashboard/overview",
    summary: "Dashboard Overview",
    tags: ["Dashboard"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Dashboard overview data"
        )
    ]
)]

public function overview()
{
    $user = Auth::user();

    $role = 'Guest';

    if ($user) {
        $role = $user->roles->first()?->name ?? 'User';
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'welcome_message' => $user
                ? 'Welcome back, ' . $user->name
                : 'Welcome to SmartCRM',

            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ] : null,

            'role' => $role,

            'quick_links' => [
                [
                    'name' => 'Dashboard',
                    'url' => '/dashboard'
                ],
                [
                    'name' => 'Customers',
                    'url' => '/customers'
                ],
                [
                    'name' => 'Notifications',
                    'url' => '/notifications'
                ],
                [
                    'name' => 'Auth',
                    'url' => '/auth'
                ]
            ]
        ]
    ]);
}

#[OA\Get(
    path: "/api/v1/dashboard/customer-growth",
    summary: "Customer Growth Statistics",
    tags: ["Dashboard"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Customer growth chart data"
        )
    ]
)]

    public function customerGrowth()
    {
        $growth = Customer::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('MONTHNAME(created_at) as month_name'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy(
            DB::raw('MONTH(created_at)'),
            DB::raw('MONTHNAME(created_at)')
        )
        ->orderBy('month')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $growth
        ]);
    }


    #[OA\Get(
    path: "/api/v1/dashboard/notifications",
    summary: "Dashboard Notification Center",
    tags: ["Dashboard"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Latest notifications"
        )
    ]
)]


    public function notifications()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'unread_count' => Notification::whereNull('read_at')->count(),
                'latest_notifications' => Notification::latest()
                    ->take(5)
                    ->get()
            ]
        ]);
    }

    #[OA\Get(
    path: "/api/v1/dashboard/guide",
    summary: "Quick Start Guide",
    tags: ["Dashboard"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Quick start guide"
        )
    ]
)]

    public function guide()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                [
                    'step' => 1,
                    'title' => 'Create Customer',
                    'description' => 'Tambahkan pelanggan baru ke sistem CRM'
                ],
                [
                    'step' => 2,
                    'title' => 'Manage Customer',
                    'description' => 'Kelola data pelanggan, tag, dan custom field'
                ],
                [
                    'step' => 3,
                    'title' => 'Monitor Notifications',
                    'description' => 'Pantau notifikasi terbaru dari sistem'
                ],
                [
                    'step' => 4,
                    'title' => 'View Dashboard Reports',
                    'description' => 'Lihat statistik dan ringkasan bisnis'
                ]
            ]
        ]);
    }


    #[OA\Get(
    path: '/api/v1/dashboard/quick-links',
    summary: 'Dashboard Quick Links',
    tags: ['Dashboard'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Quick links dashboard'
            )
        ]
    )]
    public function quickLinks()
        {
            return response()->json([
                'status' => 'success',
                'data' => [
                    [
                        'name' => 'Customers',
                        'url' => '/customers',
                    ],
                    [
                        'name' => 'Notifications',
                        'url' => '/notifications',
                    ],
                    [
                        'name' => 'Dashboard',
                        'url' => '/dashboard',
                    ],
                    [
                        'name' => 'API Documentation',
                        'url' => '/api/documentation',
                    ],
                ]
            ]);
        }
}
