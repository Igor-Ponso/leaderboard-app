<?php

namespace App\Http\Requests\Players;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                Rule::unique('players', 'email')->ignore($this->player->id),
            ],
            'birth_date' => ['sometimes', 'date'],
            'score' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
