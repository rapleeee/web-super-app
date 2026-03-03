<?php

namespace App\Http\Requests\KepegawaianTu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTuBeritaAcaraTindakLanjutRequest extends FormRequest
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
            'status' => ['required', 'in:baru,diproses,selesai,arsip'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'tags' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status tindak lanjut wajib dipilih.',
            'status.in' => 'Status tindak lanjut tidak valid.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
            'tags.max' => 'Tag maksimal 500 karakter.',
        ];
    }
}
