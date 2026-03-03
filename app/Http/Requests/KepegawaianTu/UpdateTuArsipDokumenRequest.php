<?php

namespace App\Http\Requests\KepegawaianTu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTuArsipDokumenRequest extends FormRequest
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
            'tags' => ['nullable', 'string', 'max:500'],
            'retensi_sampai' => ['nullable', 'date'],
            'catatan_versi' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tags.max' => 'Tag maksimal 500 karakter.',
            'retensi_sampai.date' => 'Format tanggal retensi tidak valid.',
            'catatan_versi.max' => 'Catatan versi maksimal 255 karakter.',
        ];
    }
}
