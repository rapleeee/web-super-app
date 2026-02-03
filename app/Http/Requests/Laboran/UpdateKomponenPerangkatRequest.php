<?php

namespace App\Http\Requests\Laboran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKomponenPerangkatRequest extends FormRequest
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
            'kode_inventaris' => ['required', 'string', 'max:50', Rule::unique('komponen_perangkats', 'kode_inventaris')->ignore($this->route('komponen_perangkat'))],
            'unit_komputer_id' => ['required', 'exists:unit_komputers,id'],
            'kategori_id' => ['required', 'exists:kategori_perangkats,id'],
            'merk' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'nomor_seri' => ['nullable', 'string', 'max:100'],
            'tahun_pengadaan' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'status' => ['required', 'in:aktif,dalam_perbaikan,tidak_aktif'],
            'spesifikasi' => ['nullable', 'string', 'max:500'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode_inventaris.required' => 'Kode inventaris wajib diisi.',
            'kode_inventaris.unique' => 'Kode inventaris sudah digunakan.',
            'unit_komputer_id.required' => 'Unit komputer wajib dipilih.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
