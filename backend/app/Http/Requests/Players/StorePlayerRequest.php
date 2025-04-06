<?php

namespace App\Http\Requests\Players;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajuste se usar policies
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:players,email'],
            'birth_date' => ['required', 'date'],
            'score' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
