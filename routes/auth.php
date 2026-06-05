<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Auth\ForgotPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\ResetPage;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('login', LoginPage::class)->name('login');
Route::get('password/forgot', ForgotPage::class)->name('password.forgot');
Route::get('password/reset', ResetPage::class)->name('password.reset');


Route::get('login/uid', function(){
  return Socialite::driver('uid')->redirect();
});

Route::get('uid/callback', function(){
  $user = Socialite::driver('uid')->user();

});