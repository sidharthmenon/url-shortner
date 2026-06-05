<?php

use App\Livewire\Admin\Role\RolePage;
use App\Livewire\Admin\Shorten\ShortenPage;
use App\Livewire\Admin\User\UserPage;
use Illuminate\Support\Facades\Route;

Route::get('/users', UserPage::class)->middleware('can:admin:users:view')->name('users');
Route::get('/roles', RolePage::class)->middleware('can:admin:roles:view')->name('roles');
Route::get('/urls', ShortenPage::class)->middleware('can:admin:urls:view')->name('urls');
