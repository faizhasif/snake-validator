<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'width' => 20,
            'height' => 15,
            'score' => 0,
            'fruit_x' => null,
            'fruit_y' => null,
            'snake_x' => 0,
            'snake_y' => 0,
            'snake_vel_x' => 1,
            'snake_vel_y' => 0,
            'is_over' => false,
        ];
    }

    /**
     * Factory with a user-defined size
     *
     * @return Factory
     */
    public function withSize(int $x, int $y): Factory
    {
        return $this->state(function (array $attributes) use ($x, $y) {
            return [
                'width' => $x,
                'height' => $y,
            ];
        });
    }
}
