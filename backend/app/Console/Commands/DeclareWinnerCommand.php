<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;
use App\Models\Winner;

class DeclareWinnerCommand extends Command
{
    protected $signature = 'declare:winner';
    protected $description = 'Find player with highest score and save as winner if not tied';

    public function handle(): int
    {
        $topScore = Player::max('score');

        if ($topScore === null) {
            $this->info('No players found.');
            return self::SUCCESS;
        }

        $topPlayers = Player::where('score', $topScore)->get();

        if ($topPlayers->count() !== 1) {
            $this->info('No unique top scorer — tie detected.');
            return self::SUCCESS;
        }

        Winner::create([
            'player_id' => $topPlayers->first()->id,
            'score' => $topScore,
            'created_at' => now(),
        ]);

        $this->info('Winner declared successfully!');
        return self::SUCCESS;
    }
}
