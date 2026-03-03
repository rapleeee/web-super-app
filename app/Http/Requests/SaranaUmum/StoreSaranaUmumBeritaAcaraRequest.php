<?php

namespace App\Http\Requests\SaranaUmum;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaranaUmumBeritaAcaraRequest extends FormRequest
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
            'sarana_umum_id' => ['required', 'exists:sarana_umums,id'],
            'ruangan_id' => ['nullable', 'exists:laboratoriums,id'],
            'tanggal' => ['required', 'date'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'nama_guru' => ['required', 'string', 'max:255'],
            'mata_pelajaran' => ['nullable', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50'],
            'jumlah_peserta' => ['required', 'integer', 'min:1'],
            'kegiatan' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:draft,final'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sarana_umum_id.required' => 'Sarana umum wajib dipilih.',
            'sarana_umum_id.exists' => 'Sarana umum tidak valid.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            'nama_guru.required' => 'Nama guru wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
            'jumlah_peserta.required' => 'Jumlah peserta wajib diisi.',
            'jumlah_peserta.min' => 'Jumlah peserta minimal 1.',
        ];
    }
}
