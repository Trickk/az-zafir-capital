<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['nullable', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'boss_name' => ['nullable', 'string', 'max:150'],
            'contact_discord' => ['nullable', 'string', 'max:150'],
            'commission_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'matrix_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $commission = (float) ($this->input('commission_percent', 10));
            $matrix = (float) ($this->input('matrix_percent', 10));

            if (($commission + $matrix) > 100) {
                $validator->errors()->add(
                    'matrix_percent',
                    'La suma de porcentaje gestor y porcentaje Matrix no puede superar el 100%.'
                );
            }
        });
    }
}
