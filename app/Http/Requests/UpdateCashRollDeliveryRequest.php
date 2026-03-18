<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCashRollDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gang_id' => ['required', 'exists:gangs,id'],
            'holding_id' => ['required', 'exists:holdings,id'],
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
            'holding_id.required' => 'Debes seleccionar un holding.',
            'holding_id.exists' => 'El holding seleccionado no existe.',
            'amount.required' => 'Debes indicar el importe.',
            'amount.min' => 'El importe debe ser mayor que cero.',
            'status.required' => 'Debes seleccionar un estado.',
        ];
    }
}
