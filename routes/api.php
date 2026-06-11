<?php

use App\Http\Controllers\Api\TrackShortLinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/short-links/track', TrackShortLinkController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
