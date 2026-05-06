<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function summary()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_customers' => Customer::count(),
            'notifications_unread' => Notification::where('is_read', false)->count()
        ]);
    }
}
