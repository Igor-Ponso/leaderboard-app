<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Player;
use PHPUnit\Framework\Attributes\Test;

class ResetPlayerScoresCommandTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_resets_all_player_scores_to_zero(): void
    {
        Player::factory()->count(3)->sequence(
            ['score' => 10],
            ['score' => 20],
            ['score' => 30]
        )->create();

        $this->artisan('players:reset-scores')
            ->expectsOutput('✅ 3 player score(s) reset to 0.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('players', ['score' => 0]);
        $this->assertEquals(3, Player::where('score', 0)->count());
    }
}
