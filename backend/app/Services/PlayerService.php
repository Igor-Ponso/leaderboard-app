<?php

namespace App\Services;

use App\Jobs\GenerateUserQrJob;
use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class PlayerService
{
    /**
     * Get all players ordered by the latest created.
     *
     * @return Collection<int, Player>
     */
    public function all(): Collection
    {
        return Player::latest()->get();
    }

    /**
     * Create a new player and dispatch QR code generation job.
     *
     * @param array<string, mixed> $data
     * @return Player
     */
    public function create(array $data): Player
    {
        $player = Player::create($data);

        GenerateUserQrJob::dispatch($player);

        return $player->refresh();
    }

    /**
     * Update the given player with the provided data.
     *
     * @param Player $player
     * @param array<string, mixed> $data
     * @return Player
     */
    public function update(Player $player, array $data): Player
    {
        $player->update($data);
        return $player;
    }

    /**
     * Delete the given player.
     *
     * @param Player $player
     * @return void
     */
    public function delete(Player $player): void
    {
        $player->delete();
    }
}
