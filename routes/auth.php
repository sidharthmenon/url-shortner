<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::get('login', [LoginController::class, 'login_page'])->name('login');
Route::post('login', [LoginController::class, 'authenticate']);

Route::get('password/forgot', [LoginController::class, 'forgot_page'])->name('password.forgot');
Route::post('password/forgot', [LoginController::class, 'send_reset_link']);

Route::get('password/reset', [LoginController::class, 'reset_page'])->name('password.reset');
Route::post('password/reset', [LoginController::class, 'reset_password']);
