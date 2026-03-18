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
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('gangs', 'name')->ignore($gangId),
            ],
            'description' => ['nullable', 'string'],
            'boss_name' => ['nullable', 'string', 'max:150'],
            'contact_discord' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ];
    }
}
