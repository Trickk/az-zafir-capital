<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['nullable', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:150', 'unique:gangs,name'],
            'description' => ['nullable', 'string'],
            'boss_name' => ['nullable', 'string', 'max:150'],
            'contact_discord' => ['nullable', 'string', 'max:150'],
            'settlement_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.exists' => 'La empresa seleccionada no existe.',
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe una banda con ese nombre.',
            'settlement_percent.required' => 'Debes indicar el porcentaje de liquidación.',
            'settlement_percent.numeric' => 'El porcentaje de liquidación debe ser numérico.',
            'status.required' => 'Debes seleccionar un estado.',
        ];
    }
}
