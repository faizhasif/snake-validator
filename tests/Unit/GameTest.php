<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Game;

class GameTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_new_game(): void
    {
        $game = Game::factory()->create();

        $this->assertModelExists($game);
    }

    public function test_create_new_game_with_width_10_and_height_10(): void
    {
        $game = Game::factory()->withSize(10, 10)->create();

        $this->assertEquals($game->width, 10);
        $this->assertEquals($game->height, 10);
    }

    public function test_cannot_make_180_movement(): void
    {
        $game = Game::factory()->create();

        // Right to Left
        $this->assertFalse($game->changeVelocity(-1, 0));

        // Left to Right
        $game->snake_vel_x = -1;
        $this->assertFalse($game->changeVelocity(1, 0));

        // Top to Bottom
        $game->snake_vel_x = 0;
        $game->snake_vel_y = 1;
        $this->assertFalse($game->changeVelocity(0, -1));

        // Bottom to Top
        $game->snake_vel_y = 1;
        $this->assertFalse($game->changeVelocity(0, -1));
    }

    public function test_cannot_move_out_of_bounds(): void
    {
        $game = Game::factory()->withSize(10, 10)->create();

        // Right wall
        $game->snake_x = 10;
        $this->assertFalse($game->moveSnake());

        //Left wall
        $game->snake_x = 0;
        $game->snake_vel_x = -1;
        $this->assertFalse($game->moveSnake());

        // Bottom wall
        $game->snake_y = 0;
        $game->snake_vel_x = 0;
        $game->snake_vel_y = -1;
        $this->assertFalse($game->moveSnake());

        // Top wall
        $game->snake_y = 10;
        $game->snake_vel_y = 1;
        $this->assertFalse($game->moveSnake());
    }

    public function test_snake_on_top_of_fruit(): void
    {
        $game = Game::factory()->create();
        $game->fruit_x = 15;
        $game->fruit_y = 13;
        $game->snake_x = 15;
        $game->snake_y = 13;

        $this->assertTrue($game->checkIsSnakeOnFruit());

        $game->generateFruitLocation();
        $this->assertFalse($game->checkIsSnakeOnFruit());
    }

    public function test_increment_score(): void
    {
        $game = Game::factory()->create();
        $game->incrementScore();

        $this->assertEquals($game->score, 1);
    }

    public function test_generate_fruit_location(): void
    {
        $game = Game::factory()->create();
        $this->assertNull($game->fruit_x);
        $this->assertNull($game->fruit_y);

        $game->generateFruitLocation(true);
        $this->assertNotNull($game->fruit_x);
        $this->assertNotNull($game->fruit_y);
    }
}
