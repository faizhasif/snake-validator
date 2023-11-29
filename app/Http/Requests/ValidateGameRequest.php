<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class ValidateGameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'gameId' => 'required|integer|exists:games,id',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'score' => 'required|integer',
            'fruit' => 'required',
            'fruit.x' => 'required|integer',
            'fruit.y' => 'required|integer',
            'snake' => 'required',
            'snake.x' => 'required|integer',
            'snake.y' => 'required|integer',
            'snake.velX' => 'required|integer',
            'snake.velY' => 'required|integer',
            'ticks' => 'required|array',
            'ticks.*.velX' => 'required|integer',
            'ticks.*.velY' => 'required|integer',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl())
            ->status(Response::HTTP_BAD_REQUEST);
    }
}
