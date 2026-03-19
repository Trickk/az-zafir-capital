<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_customer_name' => ['nullable', 'string', 'max:180'],
            'invoice_state_id' => ['nullable', 'string', 'max:120'],
            'gang_id' => ['required', 'exists:gangs,id'],
            'concept' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'gross_amount' => ['required', 'numeric', 'min:0.01'],
            'issued_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:issued_at'],
            'status' => ['required', 'in:draft,pending,approved,rejected,paid,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'gang_id.required' => 'Debes seleccionar una banda.',
            'gang_id.exists' => 'La banda seleccionada no existe.',
            'concept.required' => 'Debes indicar el concepto.',
            'gross_amount.required' => 'Debes indicar el importe.',
            'gross_amount.min' => 'El importe debe ser mayor que cero.',
            'issued_at.required' => 'Debes indicar la fecha de emisión.',
            'status.required' => 'Debes seleccionar un estado.',
        ];
    }
}
