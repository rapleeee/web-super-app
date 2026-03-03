<?php

namespace App\Http\Requests\KepegawaianTu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTuSuratRequest extends FormRequest
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
            'tu_surat_template_id' => ['nullable', 'exists:tu_surat_templates,id'],
            'perihal' => ['required', 'string', 'max:200'],
            'tujuan' => ['required', 'string', 'max:200'],
            'tanggal_surat' => ['nullable', 'date'],
            'isi_surat' => ['required', 'string', 'max:10000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'perihal.required' => 'Perihal wajib diisi.',
            'tujuan.required' => 'Tujuan surat wajib diisi.',
            'isi_surat.required' => 'Isi surat wajib diisi.',
        ];
    }
}
