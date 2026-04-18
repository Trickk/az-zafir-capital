<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHoldingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $holdingId = $this->route('holding')->id;

        return [
            'gang_id' => ['nullable', 'exists:gangs,id'],
            'name' => [
                'required',
                'string',
                'max:180',
                Rule::unique('holdings', 'name')->ignore($holdingId),
            ],
            'legal_name' => ['nullable', 'string', 'max:220'],
            'sector' => ['nullable', 'string', 'max:150'],
            'contact_name' => ['nullable', 'string', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:80'],
            'contact_email' => ['nullable', 'email', 'max:180'],
            'trust_level' => ['required', 'integer', 'min:1', 'max:5'],
            'default_commission_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:active,inactive,blocked'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'gang_id.required' => 'Debes seleccionar una banda.',
            'gang_id.exists' => 'La banda seleccionada no existe.',
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe un holding con ese nombre.',
            'trust_level.required' => 'Debes indicar el nivel de confianza.',
            'default_commission_percent.required' => 'Debes indicar la comisión por defecto.',
            'status.required' => 'Debes seleccionar un estado.',
        ];
    }
}
