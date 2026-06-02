<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::redirect('/dashboard', '/admin')->name('dashboard');

require __DIR__.'/auth.php';
