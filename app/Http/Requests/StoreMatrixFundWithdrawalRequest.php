<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatrixFundWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'concept' => ['required', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Debes indicar un importe.',
            'amount.numeric' => 'El importe debe ser numérico.',
            'amount.min' => 'El importe debe ser mayor que 0.',
            'concept.required' => 'Debes indicar un concepto.',
            'concept.max' => 'El concepto no puede superar los 150 caracteres.',
        ];
    }
}
