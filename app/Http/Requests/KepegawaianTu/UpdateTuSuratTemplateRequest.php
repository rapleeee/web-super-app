<?php

namespace App\Http\Requests\KepegawaianTu;

use App\Models\TuSuratTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTuSuratTemplateRequest extends FormRequest
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
        /** @var TuSuratTemplate|null $tuSuratTemplate */
        $tuSuratTemplate = $this->route('tuSuratTemplate');

        return [
            'kode' => [
                'required',
                'string',
                'max:30',
                Rule::unique('tu_surat_templates', 'kode')->ignore($tuSuratTemplate?->id),
            ],
            'nama' => ['required', 'string', 'max:120'],
            'judul' => ['required', 'string', 'max:180'],
            'isi_template' => ['required', 'string', 'max:10000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode template wajib diisi.',
            'kode.unique' => 'Kode template sudah digunakan.',
            'nama.required' => 'Nama template wajib diisi.',
            'judul.required' => 'Judul surat wajib diisi.',
            'isi_template.required' => 'Isi template wajib diisi.',
        ];
    }
}
