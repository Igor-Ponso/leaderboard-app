<?php

namespace App\Services;

use App\Models\Player;

class PlayerService
{
    public function all()
    {
        return Player::latest()->get();
    }

    public function create(array $data): Player
    {
        return Player::create($data);
    }

    public function update(Player $player, array $data): Player
    {
        $player->update($data);
        return $player;
    }

    public function delete(Player $player): void
    {
        $player->delete();
    }
}
