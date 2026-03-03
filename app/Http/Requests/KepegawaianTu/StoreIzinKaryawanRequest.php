<?php

namespace App\Http\Requests\KepegawaianTu;

use App\Http\Requests\KepegawaianTu\Concerns\ValidatesIzinKaryawanBusinessRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreIzinKaryawanRequest extends FormRequest
{
    use ValidatesIzinKaryawanBusinessRules;

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
        $lampiranMaxKb = (int) config('kepegawaian_tu.izin.lampiran_max_kb', 2048);

        return [
            'nama_karyawan' => ['required', 'string', 'max:100'],
            'jenis' => ['required', 'in:izin,cuti,sakit,dinas_luar'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan' => ['required', 'string', 'max:1000'],
            'dinas_luar_hari' => ['nullable', 'string', 'max:120', 'required_if:jenis,dinas_luar'],
            'dinas_luar_waktu' => ['nullable', 'string', 'max:120', 'required_if:jenis,dinas_luar'],
            'dinas_luar_tempat' => ['nullable', 'string', 'max:200', 'required_if:jenis,dinas_luar'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:'.$lampiranMaxKb],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $lampiranMaxKb = (int) config('kepegawaian_tu.izin.lampiran_max_kb', 2048);
        $lampiranMaxMb = $lampiranMaxKb / 1024;

        return [
            'nama_karyawan.required' => 'Nama karyawan wajib diisi.',
            'jenis.required' => 'Jenis izin wajib dipilih.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'alasan.required' => 'Alasan izin wajib diisi.',
            'dinas_luar_hari.required_if' => 'Hari pelaksanaan wajib diisi untuk dinas luar.',
            'dinas_luar_waktu.required_if' => 'Waktu pelaksanaan wajib diisi untuk dinas luar.',
            'dinas_luar_tempat.required_if' => 'Tempat pelaksanaan wajib diisi untuk dinas luar.',
            'lampiran.max' => "Ukuran lampiran maksimal {$lampiranMaxMb}MB.",
        ];
    }
}
