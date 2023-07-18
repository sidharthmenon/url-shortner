<?php

use App\Http\Controllers\Auth\LoginController;
use App\Models\Shorten;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', 'login');

Route::redirect('home', 'admin/urls');

Route::get('logout', [LoginController::class, 'logout']);
