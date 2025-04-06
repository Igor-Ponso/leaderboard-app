<?php
namespace App\Http\Controllers;

use App\Models\Player;
use App\Http\Requests\Players\UpdatePlayerRequest;
use App\Http\Requests\Players\StorePlayerRequest;
use App\Services\PlayerService;

class PlayerController extends Controller
{
    public function __construct(
        protected PlayerService $service
    ) {
    }

    /**
     * Display a listing of the players.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->service->all());
    }

    /**
     * Store a newly created player in storage.
     *
     * @param \App\Http\Requests\Players\StorePlayerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePlayerRequest $request)
    {
        return response()->json($this->service->create($request->validated()), 201);
    }

    /**
     * Display the specified player.
     *
     * @param \App\Models\Player $player
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Player $player)
    {
        return response()->json($player);
    }

    /**
     * Update the specified player in storage.
     *
     * @param \App\Http\Requests\Players\UpdatePlayerRequest $request
     * @param \App\Models\Player $player
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePlayerRequest $request, Player $player)
    {
        return response()->json($this->service->update($player, $request->validated()));
    }

    /**
     * Remove the specified player from storage.
     *
     * @param \App\Models\Player $player
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Player $player)
    {
        $this->service->delete($player);
        return response()->json(null, 204);
    }
}
