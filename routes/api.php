<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
    });

    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/overview', [DashboardController::class, 'overview']);
    });
    Route::get('/dashboard/customer-growth', [DashboardController::class, 'customerGrowth']);
    Route::get('/dashboard/notifications',[DashboardController::class, 'notifications']);
    Route::get('/dashboard/guide',[DashboardController::class, 'guide']);
    Route::get('/dashboard/quick-links', [DashboardController::class, 'quickLinks']);
    Route::get('/dashboard/tasks', [DashboardController::class, 'tasks']);

    Route::middleware(['api.logger'])->prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/', [NotificationController::class, 'store']);
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::get('/test-log', function () {
            return response()->json(['message' => 'API logging active']);
        });
    });

    Route::get('/customers/export/json', [CustomerController::class, 'exportJson']);
    Route::get('/customers/export/csv', [CustomerController::class, 'exportCsv']);
    Route::get('/customers/export/excel', [CustomerController::class, 'exportExcel']);
    Route::post('/customers/import', [CustomerController::class, 'importCsv']);
    Route::get('/customers/duplicates', [CustomerController::class, 'duplicates']);
    Route::post('/customers/{id}/merge', [CustomerController::class, 'merge']);
    Route::get('/customers/{id}/activities', [CustomerController::class, 'activities']);
    Route::post('/customers/{id}/tags', [CustomerController::class, 'attachTags']);
    Route::get('/customers/{id}/attachments', [CustomerController::class, 'attachments']);
    Route::post('/customers/{id}/attachments', [CustomerController::class, 'uploadAttachment']);
    Route::patch('/customers/{id}/favorite', [CustomerController::class, 'toggleFavorite']);
    Route::apiResource('customers', CustomerController::class);
    Route::get('/attachments/{id}/preview', [CustomerController::class, 'previewAttachment']);
    Route::get('/attachments/{id}/download', [CustomerController::class, 'downloadAttachment']);
    Route::delete('/attachments/{id}', [CustomerController::class, 'deleteAttachment']);

    Route::post('/webhook/receive', [WebhookController::class, 'receive']);
    Route::patch('/customers/{id}/favorite', [CustomerController::class, 'toggleFavorite']);


    Route::get('/analytics/summary', [AnalyticsController::class, 'summary']);
    Route::get('/analytics/customer-growth', [AnalyticsController::class, 'customerGrowth']);
    Route::get('/analytics/customer-status', [AnalyticsController::class, 'customerStatus']);
});
