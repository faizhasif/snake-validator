<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Game;

class GameStateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'gameId' => (int)$this->id,
            'width' => (int)$this->width,
            'height' => (int)$this->height,
            'score' => (int)$this->score,
            'fruit' => [
                'x' => (int)$this->fruit_x,
                'y' => (int)$this->fruit_y,
            ],
            'snake' => [
                'x' => (int)$this->snake_x,
                'y' => (int)$this->snake_y,
                'velX' => (int)$this->snake_vel_x,
                'velY' => (int)$this->snake_vel_y,
            ],
        ];
    }
}
