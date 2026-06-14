<?php

use App\Http\Controllers\AnalyticsReportExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/dashboard', '/admin')->name('dashboard');

Route::middleware(['auth', 'mfa.verified', 'can:View:Analytics'])->group(function (): void {
    Route::get('/analytics/reports/{report}/export/{format}', AnalyticsReportExportController::class)
        ->whereIn('format', ['csv', 'xlsx', 'pdf'])
        ->name('analytics.reports.export');
});

require __DIR__.'/auth.php';
