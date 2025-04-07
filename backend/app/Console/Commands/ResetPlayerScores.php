<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;
use Illuminate\Support\Facades\DB;

class ResetPlayerScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'players:reset-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all player scores to 0';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        DB::transaction(function () {
            $affected = Player::query()->update(['score' => 0]);
            $this->info("✅ {$affected} player score(s) reset to 0.");
        });

        return self::SUCCESS;
    }
}
