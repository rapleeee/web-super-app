<?php

namespace App\Http\Requests\Laboran;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteUnitKomputerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unit_ids' => ['required', 'array', 'min:1'],
            'unit_ids.*' => ['required', 'integer', 'distinct', 'exists:unit_komputers,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'unit_ids.required' => 'Pilih minimal satu unit komputer.',
            'unit_ids.array' => 'Format data unit tidak valid.',
            'unit_ids.min' => 'Pilih minimal satu unit komputer.',
            'unit_ids.*.exists' => 'Terdapat unit komputer yang tidak valid.',
        ];
    }
}
