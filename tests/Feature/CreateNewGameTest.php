<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateNewGameTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_new_game_without_params(): void
    {
        $response = $this->getJson('/api/new');

        $response->assertStatus(400);
    }

    public function test_create_new_game_with_only_height_param(): void
    {
        $response = $this->getJson('/api/new?h=10');

        $response->assertStatus(400);
    }

    public function test_create_new_game_with_only_width_param(): void
    {
        $response = $this->getJson('/api/new?w=10');

        $response->assertStatus(400);
    }

    public function test_create_new_game_with_non_integer_width_param(): void
    {
        $response = $this->getJson('/api/new?w=abc&h=10');

        $response->assertStatus(400);

        $response = $this->getJson('/api/new?w=10.5&h=10');

        $response->assertStatus(400);
    }

    public function test_create_new_game_with_non_integer_height_param(): void
    {
        $response = $this->getJson('/api/new?w=10&h=abc');

        $response->assertStatus(400);

        $response = $this->getJson('/api/new?w=10&h=10.5');

        $response->assertStatus(400);
    }

    public function test_create_new_game_correctly(): void
    {
        $response = $this->getJson('/api/new?w=10&h=10');

        $response
            ->assertStatus(200)
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
            );
    }
}
