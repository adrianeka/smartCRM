<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\OpportunityTaskController;
use App\Http\Controllers\CustomerNoteController;
use App\Http\Controllers\ActivityFeedController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CalendarEventController;

Route::prefix('v1')
    ->middleware('throttle:60,1')
    ->group(function () {

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

    Route::get('/customers/{id}/activity-feed',[ActivityFeedController::class, 'index']);
    Route::get('/customers/{id}/notes',[CustomerNoteController::class, 'index']);
    Route::post('/customers/{id}/notes',[CustomerNoteController::class, 'store']);
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

    Route::get('/analytics/summary', [AnalyticsController::class, 'summary']);
    Route::get('/analytics/customer-growth', [AnalyticsController::class, 'customerGrowth']);
    Route::get('/analytics/customer-status', [AnalyticsController::class, 'customerStatus']);
    Route::get('/analytics/customer-growth-trend', [AnalyticsController::class, 'customerGrowthTrend']);
    Route::get('/analytics/kpi', [AnalyticsController::class, 'kpi']);
    Route::get('/analytics/customer-growth-filtered', [AnalyticsController::class, 'customerGrowthFiltered']);
    Route::get('/analytics/export/csv', [AnalyticsController::class, 'exportCsv']);
    Route::middleware('auth:sanctum')
    ->get('/analytics/role-dashboard', [AnalyticsController::class, 'roleDashboard']);

    Route::get('/opportunities/calendar',[OpportunityController::class, 'calendar']);
    Route::post('/opportunities/{id}/send-whatsapp',[OpportunityController::class, 'sendWhatsapp']);
    Route::post('/opportunities/{id}/send-proposal',[OpportunityController::class, 'sendProposal']);
    Route::get('/opportunities/{id}/tasks',[OpportunityTaskController::class, 'index']);
    Route::post('/opportunities/{id}/tasks',[OpportunityTaskController::class, 'store']);
    Route::get('/opportunities/win-loss',[OpportunityController::class, 'winLossAnalysis']);
    Route::get('/opportunities/forecast',[OpportunityController::class, 'forecast']);
    Route::get('/opportunities/{id}/score',[OpportunityController::class, 'score']);
    Route::get('/opportunities/pipeline',[OpportunityController::class, 'pipeline']);
    Route::get('/opportunities', [OpportunityController::class, 'index']);
    Route::put('/opportunities/{id}',[OpportunityController::class,'update']);
    Route::patch('/opportunities/{id}/stage',[OpportunityController::class, 'updateStage']);
    Route::apiResource('opportunities',OpportunityController::class);

    Route::get('/audit-logs',[AuditLogController::class, 'index']);
    Route::get('/calendar/events',[CalendarEventController::class, 'index']);
    Route::post('/calendar/events',[CalendarEventController::class, 'store']);
    Route::get('/notifications',[NotificationController::class, 'index']);
});
