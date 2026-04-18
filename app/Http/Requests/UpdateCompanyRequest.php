<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id ?? $this->route('company');

        return [
            'name' => ['required', 'string', 'max:180', Rule::unique('companies', 'name')->ignore($companyId)],
            'legal_name' => ['nullable', 'string', 'max:220'],
            'type' => [
                'required',
                Rule::in([
                    'cultural',
                    'logistics',
                    'hospitality',
                    'investment',
                    'entertainment',
                    'security',
                    'technology',
                    'trading',
                ]),
            ],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'responsible_name' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:5120'],
            'invoice_image' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
