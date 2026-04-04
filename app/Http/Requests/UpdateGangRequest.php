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
        $gangId = $this->route('gang')->id;

        return [
            'description' => ['nullable', 'string'],
            'boss_name' => ['nullable', 'string', 'max:150'],
            'contact_discord' => ['nullable', 'string', 'max:150'],
            'commission_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Ya existe una banda con ese nombre.',
            'commission_percent.required' => 'Debes indicar el porcentaje de comisión.',
            'commission_percent.numeric' => 'El porcentaje de comisión debe ser numérico.',
            'status.required' => 'Debes seleccionar un estado.',
        ];
    }
}
