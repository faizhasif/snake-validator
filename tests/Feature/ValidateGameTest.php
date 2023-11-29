<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\GameSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ValidateGameTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_incomplete_params(): void
    {
        $response = $this->postJson('/api/validate', []);

        $response->assertStatus(400);
    }

    public function test_move_out_of_bounds(): void
    {
        $response = $this->postJson('/api/validate', [
            'gameId' => 1,
            'width' => 10,
            'height' => 10,
            'score' => 0,
            'fruit' => [
                'x' => 3,
                'y' => 4,
            ],
            'snake' => [
                'x' => 0,
                'y' => 0,
                'velX' => 1,
                'velY' => 0,
            ],
            'ticks' => [
                [
                    'velX' => 0,
                    'velY' => -1,
                ],
                [
                    'velX' => 0,
                    'velY' => -1,
                ],
            ],
        ]);

        $response->assertStatus(418);
    }

    public function test_move_180(): void
    {
        $response = $this->postJson('/api/validate', [
            'gameId' => 1,
            'width' => 10,
            'height' => 10,
            'score' => 0,
            'fruit' => [
                'x' => 3,
                'y' => 4,
            ],
            'snake' => [
                'x' => 0,
                'y' => 0,
                'velX' => 1,
                'velY' => 0,
            ],
            'ticks' => [
                [
                    'velX' => -1,
                    'velY' => 0,
                ],
            ],
        ]);

        $response->assertStatus(418);
    }

    public function test_move_does_not_reach_fruit(): void
    {
        $response = $this->postJson('/api/validate', [
            'gameId' => 1,
            'width' => 10,
            'height' => 10,
            'score' => 0,
            'fruit' => [
                'x' => 3,
                'y' => 4,
            ],
            'snake' => [
                'x' => 0,
                'y' => 0,
                'velX' => 1,
                'velY' => 0,
            ],
            'ticks' => [
                [
                    'velX' => 1,
                    'velY' => 0,
                ],
                [
                    'velX' => 1,
                    'velY' => 0,
                ],
                [
                    'velX' => 1,
                    'velY' => 0,
                ],
            ],
        ]);

        $response->assertStatus(404);
    }

    public function test_move_to_fruit_location(): void
    {
        $response = $this->postJson('/api/validate', [
            'gameId' => 1,
            'width' => 10,
            'height' => 10,
            'score' => 0,
            'fruit' => [
                'x' => 3,
                'y' => 4,
            ],
            'snake' => [
                'x' => 0,
                'y' => 0,
                'velX' => 1,
                'velY' => 0,
            ],
            'ticks' => [
                [
                    'velX' => 1,
                    'velY' => 0,
                ],
                [
                    'velX' => 1,
                    'velY' => 0,
                ],
                [
                    'velX' => 0,
                    'velY' => 1,
                ],
                [
                    'velX' => 0,
                    'velY' => 1,
                ],
                [
                    'velX' => 0,
                    'velY' => 1,
                ],
                [
                    'velX' => 0,
                    'velY' => 1,
                ],
                [
                    'velX' => 0,
                    'velY' => 1,
                ],
            ],
        ]);

        $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) =>
            $json->hasAll([
                'gameId',
                'width',
                'height',
                'score',
                'fruit',
                'snake',
            ])
                ->where('width', 10)
                ->where('height', 10)
                ->where('score', 1)
                ->where('snake.x', 3)
                ->where('snake.y', 4)
        );
    }
}
