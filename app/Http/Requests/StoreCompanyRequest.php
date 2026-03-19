<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180', 'unique:companies,name'],
            'legal_name' => ['nullable', 'string', 'max:220'],
            'type' => ['required', 'in:cultural,logistics,hospitality,investment,entertainment,security,technology,trading,other'],
            'country' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'invoice_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:active,inactive'],
            'responsible_name' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe una empresa con ese nombre.',
            'type.required' => 'Debes seleccionar un tipo.',
            'status.required' => 'Debes seleccionar un estado.',
            'logo.image' => 'El logo debe ser una imagen válida.',
            'invoice_image.image' => 'La imagen de factura debe ser una imagen válida.',
        ];
    }
}
