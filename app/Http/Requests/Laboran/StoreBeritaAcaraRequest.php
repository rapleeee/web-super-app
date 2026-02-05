<?php

namespace App\Http\Requests\Laboran;

use App\Models\BeritaAcara;
use Illuminate\Foundation\Http\FormRequest;

class StoreBeritaAcaraRequest extends FormRequest
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
            'laboratorium_id' => ['required', 'exists:laboratoriums,id'],
            'tanggal' => ['required', 'date'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'nama_guru' => ['required', 'string', 'max:255'],
            'mata_pelajaran' => ['nullable', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50'],
            'jumlah_siswa' => ['required', 'integer', 'min:1'],
            'jumlah_pc_digunakan' => ['required', 'integer', 'min:1'],
            'alat_tambahan' => ['nullable', 'array'],
            'alat_tambahan.*' => ['string', 'in:'.implode(',', BeritaAcara::alatTambahanOptions())],
            'kegiatan' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:draft,final'],
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
            'laboratorium_id.required' => 'Laboratorium wajib dipilih.',
            'laboratorium_id.exists' => 'Laboratorium tidak valid.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            'nama_guru.required' => 'Nama guru wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
            'jumlah_siswa.required' => 'Jumlah siswa wajib diisi.',
            'jumlah_siswa.min' => 'Jumlah siswa minimal 1.',
            'jumlah_pc_digunakan.required' => 'Jumlah PC digunakan wajib diisi.',
            'jumlah_pc_digunakan.min' => 'Jumlah PC minimal 1.',
        ];
    }
}
