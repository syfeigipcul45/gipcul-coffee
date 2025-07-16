<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect('/admin/login');
});

route::get('/', [App\Http\Controllers\HomepageController::class, 'index'])->name('home');
route::get('/home', [App\Http\Controllers\HomepageController::class, 'index'])->name('home');
