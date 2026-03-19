<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCashDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gang_id' => ['required', 'exists:gangs,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'status' => ['required', 'in:pending,received,verified,cancelled'],
            'delivered_by' => ['nullable', 'string', 'max:150'],
            'received_by' => ['nullable', 'string', 'max:150'],
            'delivered_at' => ['nullable', 'date'],
            'received_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'gang_id.required' => 'Debes seleccionar una banda.',
            'gang_id.exists' => 'La banda seleccionada no existe.',
            'amount.required' => 'Debes indicar el importe.',
            'amount.min' => 'El importe debe ser mayor que cero.',
            'status.required' => 'Debes seleccionar un estado.',
        ];
    }
}
