<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Api\WebhookController;

Route::prefix('v1')->group(function () {

    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);

    Route::middleware(['api.logger'])->prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/', [NotificationController::class, 'store']);
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
       

        Route::get('/test-log', function () {
        return response()->json([
            'message' => 'API logging active'
            ]);
        });

    });
    
    Route::apiResource('customers', CustomerController::class);

    Route::post('/webhook/receive', [WebhookController::class, 'receive']);
});
