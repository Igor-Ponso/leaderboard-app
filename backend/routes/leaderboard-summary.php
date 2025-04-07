<?php

use App\Http\Controllers\LeaderboardSummaryController;
use Illuminate\Support\Facades\Route;

Route::get('/leaderboard-summary', [LeaderboardSummaryController::class, 'index']);
