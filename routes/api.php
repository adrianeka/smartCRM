<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;


Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications', [NotificationController::class, 'store']);
Route::post('/notifications/read', [NotificationController::class, 'markAsRead']);
Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
