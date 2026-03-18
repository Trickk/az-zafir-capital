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
            'name' => ['required', 'string', 'max:150', 'unique:gangs,name'],
            'description' => ['nullable', 'string'],
            'boss_name' => ['nullable', 'string', 'max:150'],
            'contact_discord' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe una banda con ese nombre.',
            'status.required' => 'Debes seleccionar un estado.',
        ];
    }
}
