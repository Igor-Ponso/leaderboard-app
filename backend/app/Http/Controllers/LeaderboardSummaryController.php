<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeaderboardSummaryController extends Controller
{
    /**
     * Display a listing of the resource grouped by score and average age.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $grouped = Player::all()
            ->groupBy('score')
            ->map(function ($players) {
                $names = $players->pluck('name')->toArray();

                $ages = $players->map(function ($player) {
                    return Carbon::createFromFormat('d/m/Y', $player->birth_date)->age;
                });

                $average_age = round($ages->average());

                return [
                    'names' => $names,
                    'average_age' => $average_age,
                ];
            });

        return response()->json($grouped);
    }
}
