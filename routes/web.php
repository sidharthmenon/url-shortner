<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::redirect('/', 'home');


Route::middleware('auth')->get('home', function(){

//   if(auth()->user()->can('admin:pages:view')){
//     return redirect()->route('admin.pages');
//   }

//   if(auth()->user()->can('admin:grievance:view')){
//     return redirect()->route('admin.grievance');
//   }

  return redirect()->route('admin.urls');
})->name('home');

Route::get('logout', [LoginController::class, 'logout']);