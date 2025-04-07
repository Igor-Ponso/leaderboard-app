<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Player;
use App\Models\Winner;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;

class DeclareWinnerCommandTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_declares_a_winner_when_only_one_top_scorer_exists(): void
    {
        $player = Player::factory()->create(['score' => 50]);

        $this->artisan('declare:winner')
            ->expectsOutput('Winner declared successfully!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('winners', [
            'player_id' => $player->id,
            'score' => 50,
        ]);
    }

    #[Test]
    public function it_does_not_declare_winner_when_no_players_exist(): void
    {
        $this->artisan('declare:winner')
            ->expectsOutput('No players found.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('winners', 0);
    }

    #[Test]
    public function it_does_not_declare_winner_if_there_is_a_tie(): void
    {
        Player::factory()->create(['score' => 100]);
        Player::factory()->create(['score' => 100]);

        $this->artisan('declare:winner')
            ->expectsOutput('No unique top scorer — tie detected.')
            ->assertExitCode(0);

        $this->assertDatabaseCount('winners', 0);
    }
}