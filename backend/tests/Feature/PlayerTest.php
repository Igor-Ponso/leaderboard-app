<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Player;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Autentica como um usuário fake
        $this->actingAs(User::factory()->create(), 'sanctum');
    }

    #[Test]
    public function it_can_create_a_player(): void
    {
        $data = [
            'name' => 'Test Player',
            'birth_date' => '2000-01-01',
            'address' => [
                'postal_code' => 'V6C 1A1',
                'street' => '123 Main St',
                'city' => 'Vancouver',
                'province' => 'BC',
            ],
        ];

        $response = $this->postJson('/api/players', $data);

        $response->assertCreated();
        $this->assertDatabaseHas('players', ['name' => 'Test Player']);
    }

    #[Test]
    public function it_can_update_a_player(): void
    {
        $player = Player::factory()->create([
            'name' => 'Original Name',
            'birth_date' => '2000-01-01',
            'address' => [
                'postal_code' => 'V6C 1A1',
                'street' => '123 Main St',
                'city' => 'Vancouver',
                'province' => 'BC',
            ],
        ]);

        $response = $this->putJson("/api/players/{$player->hash}", [
            'name' => 'Updated Name',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('players', ['name' => 'Updated Name']);
    }

    #[Test]
    public function it_can_delete_a_player(): void
    {
        $player = Player::factory()->create();

        $response = $this->deleteJson("/api/players/{$player->hash}");

        $response->assertNoContent();
        $this->assertSoftDeleted($player);
    }
}
