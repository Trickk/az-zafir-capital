<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gang_id' => ['required', 'exists:gangs,id'],
            'concept' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'status' => ['required', Rule::in(['draft', 'issued', 'paid', 'cancelled'])],
            'issued_at' => ['nullable', 'date'],
            'invoice_customer_name' => ['nullable', 'string', 'max:150'],
            'invoice_state_id' => ['nullable', 'string', 'max:100'],
        ];
    }
}
