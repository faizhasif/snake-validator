<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateGameRequest;
use App\Http\Requests\ValidateGameRequest;
use App\Models\Game;
use App\Http\Resources\GameStateResource;

class GameController extends Controller
{
    public function createGame(CreateGameRequest $request)
    {
        $game = Game::factory()->withSize((int)$request->w, (int)$request->h)->create();
        $game->generateFruitLocation(true);
        $game->save();

        return response()->json(new GameStateResource($game));
    }

    public function validateGame(ValidateGameRequest $request)
    {
        $game = Game::findOrFail($request->gameId);

        if ($game->is_over) {
            return response()->json([
                'error' => 'Game over. Start a new game',
                'current_state' => new GameStateResource($game),
            ], Response::HTTP_I_AM_A_TEAPOT);
        }

        foreach ($request->ticks as $tick) {
            if (!$game->moveSnake()) {
                $game->is_over = true;
                $game->save();
                return response()->json([
                    'error' => 'Out of bounds. Game over',
                    'current_state' => new GameStateResource($game),
                ], Response::HTTP_I_AM_A_TEAPOT);
            }

            if (!$game->changeVelocity($tick['velX'], $tick['velY'])) {
                $game->is_over = true;
                $game->save();
                return response()->json([
                    'error' => 'Invalid move. Game over',
                    'current_state' => new GameStateResource($game),
                ], Response::HTTP_I_AM_A_TEAPOT);
            }

            // Just in case if snake already on top of a fruit while there are still more ticks available, discard remaining ticks
            if ($game->checkIsSnakeOnFruit()) {
                break;
            }
        }

        // This one checks if all ticks are exhausted and the snake is still not on top of a fruit
        if (!$game->checkIsSnakeOnFruit()) {
            return response()->json([
                'error' => 'Fruit not found',
                'current_state' => new GameStateResource($game),
            ], Response::HTTP_NOT_FOUND);
        }

        $game->incrementScore();
        $game->generateFruitLocation();
        $game->save();

        return response()->json(new GameStateResource($game));
    }
}
