<?php

namespace App\Services;

use App\Jobs\GeneratePlayerQrJob;
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
     * @return Collection<int, Player>
     */
    public function create(array $data): Collection|string
    {

        if ($this->exists($data['name'])) {
            return 'Player name already exists.';
        }

        $player = Player::create($data);

        GeneratePlayerQrJob::dispatch($player);

        return Player::latest()->get();
    }

    /**
     * Update the given player with the provided data.
     *
     * @param Player $player
     * @param array<string, mixed> $data
     * @return Collection<int, Player>
     */
    public function update(Player $player, array $data): Collection
    {
        $player->update($data);

        if (isset($data['address'])) {
            GeneratePlayerQrJob::dispatch($player);
        }

        return Player::latest()->get();
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

    /**
     * Check if a player with the given name exists.
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        return Player::where('name', $name)->exists();
    }
}
