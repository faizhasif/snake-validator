<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $casts = [
        'is_over' => 'boolean',
    ];

    public function generateFruitLocation(bool $init = false): Game
    {
        $x = random_int(0, $this->width);
        $y = random_int(0, $this->height);

        if ($init) {
            if ($y === 0) {
                $x = random_int(2, $this->width);
            }
        } else {
            while($x === $this->fruit_x) {
                $x = random_int(0, $this->width);
            }

            while ($y === $this->fruit_y) {
                $y = random_int(0, $this->height);
            }
        }

        $this->fruit_x = $x;
        $this->fruit_y = $y;

        return $this;
    }

    public function moveSnake(): bool
    {

        $new_snake_x = $this->snake_x + $this->snake_vel_x;
        $new_snake_y = $this->snake_y + $this->snake_vel_y;

        // Out of bounds (Horizontal)
        if ($new_snake_x < 0 || $new_snake_x > $this->width) {
            return false;
        }

        // Out of bounds (Vertical)
        if ($new_snake_y < 0 || $new_snake_y > $this->height) {
            return false;
        }

        $this->snake_x = $new_snake_x;
        $this->snake_y = $new_snake_y;

        return true;
    }

    public function changeVelocity(int $vel_x, int $vel_y): bool
    {
        // Invalid move (Not moving)
        if ($vel_x === 0 && $vel_y === 0) {
            return false;
        }

        // Invalid move (Horizontal 180)
        if (($this->snake_vel_x === 1 && $vel_x === -1)
            || ($this->snake_vel_x === -1 && $vel_x === 1)) {
            return false;
        }

        // Invalid move (Vertical 180)
        if (($this->snake_vel_y === 1 && $vel_y === -1)
            || ($this->snake_vel_y === -1 && $vel_y === 1)) {
            return false;
        }
        $this->snake_vel_x = $vel_x;
        $this->snake_vel_y = $vel_y;

        return true;

    }

    public function checkIsSnakeOnFruit(): bool
    {
        return ($this->snake_x === $this->fruit_x && $this->snake_y === $this->fruit_y);
    }

    public function incrementScore(): Game
    {
        $this->score++;

        return $this;
    }
}
