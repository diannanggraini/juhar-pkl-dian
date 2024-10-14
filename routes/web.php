<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
