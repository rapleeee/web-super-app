<?php

namespace App\Http\Requests\KepegawaianTu;

use App\Models\IzinKaryawan;
use Illuminate\Foundation\Http\FormRequest;

class ApprovalIzinKaryawanRequest extends FormRequest
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
        $rules = [
            'status' => ['required', 'in:disetujui,ditolak'],
            'catatan_persetujuan' => ['nullable', 'string', 'max:1000', 'required_if:status,ditolak'],
            'surat_tugas_nomor' => ['nullable', 'string', 'max:120'],
            'surat_tugas_sebagai' => ['nullable', 'string', 'max:1000'],
        ];

        /** @var IzinKaryawan|null $izinKaryawan */
        $izinKaryawan = $this->route('izinKaryawan');
        if ($izinKaryawan?->jenis === 'dinas_luar') {
            $rules['surat_tugas_nomor'][] = 'required_if:status,disetujui';
            $rules['surat_tugas_sebagai'][] = 'required_if:status,disetujui';
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status approval wajib dipilih.',
            'catatan_persetujuan.required_if' => 'Catatan persetujuan wajib diisi saat menolak izin.',
            'surat_tugas_nomor.required_if' => 'Nomor surat tugas wajib diisi untuk dinas luar yang disetujui.',
            'surat_tugas_sebagai.required_if' => 'Keterangan \"sebagai\" wajib diisi untuk dinas luar yang disetujui.',
        ];
    }
}
