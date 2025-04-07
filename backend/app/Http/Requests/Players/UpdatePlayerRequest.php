<?php

namespace App\Http\Requests\Players;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayerRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'birth_date' => ['sometimes', 'date'],
            'score' => ['sometimes', 'integer'],

            'address' => ['sometimes', 'array'],
            'address.postal_code' => ['required_with:address', 'string'],
            'address.street' => ['required_with:address', 'string'],
            'address.city' => ['required_with:address', 'string'],
            'address.province' => ['required_with:address', 'string'],
        ];
    }
}
