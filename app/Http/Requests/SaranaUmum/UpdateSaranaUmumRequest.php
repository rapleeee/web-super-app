<?php

namespace App\Http\Requests\SaranaUmum;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSaranaUmumRequest extends FormRequest
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
            'kode_inventaris' => ['required', 'string', 'max:50', Rule::unique('sarana_umums', 'kode_inventaris')->ignore($this->route('sarana_umum'))],
            'nama' => ['required', 'string', 'max:100'],
            'jenis' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:150'],
            'merk' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'nomor_seri' => ['nullable', 'string', 'max:100'],
            'tahun_pengadaan' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'status' => ['required', 'in:aktif,dalam_perbaikan,tidak_aktif'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode_inventaris.required' => 'Kode inventaris wajib diisi.',
            'kode_inventaris.unique' => 'Kode inventaris sudah digunakan.',
            'nama.required' => 'Nama sarana wajib diisi.',
            'jenis.required' => 'Jenis sarana wajib diisi.',
            'lokasi.required' => 'Lokasi penempatan wajib diisi.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
