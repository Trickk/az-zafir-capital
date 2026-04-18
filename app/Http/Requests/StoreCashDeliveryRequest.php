<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCashDeliveryRequest extends FormRequest
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
            'status' => ['required', Rule::in(['pending', 'received', 'verified', 'cancelled'])],
            'notes' => ['nullable', 'string'],
        ];
    }
}
