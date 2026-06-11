<?php

use App\Http\Controllers\Auth\WaitingAssignmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/dashboard', '/admin')->name('dashboard');

Route::get('/waiting-assignment', [WaitingAssignmentController::class, 'show'])
    ->name('waiting-assignment')
    ->middleware('auth');

require __DIR__.'/auth.php';
