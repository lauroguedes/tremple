<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardsReorderUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'columns.*.id' => ['integer', 'required', 'exists:\App\Models\Column,id'],
            'columns.*.cards' => ['array'],
            'columns.*.cards.*.id' => ['required', 'integer', 'exists:\App\Models\Card,id'],
            'columns.*.cards.*.position' => ['numeric', 'required'],
        ];
    }
}
