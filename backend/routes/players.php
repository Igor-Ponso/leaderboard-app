<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('players', PlayerController::class);
});
