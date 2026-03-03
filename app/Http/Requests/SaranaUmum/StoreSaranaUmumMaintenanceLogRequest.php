<?php

namespace App\Http\Requests\SaranaUmum;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaranaUmumMaintenanceLogRequest extends FormRequest
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
            'tanggal_lapor' => ['required', 'date'],
            'tanggal_mulai' => ['nullable', 'date', 'after_or_equal:tanggal_lapor'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'sla_deadline' => ['nullable', 'date', 'after_or_equal:tanggal_lapor'],
            'keluhan' => ['required', 'string', 'max:1000'],
            'diagnosa' => ['nullable', 'string', 'max:1000'],
            'tindakan' => ['nullable', 'string', 'max:1000'],
            'teknisi' => ['nullable', 'string', 'max:100'],
            'biaya' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,proses,selesai,tidak_bisa_diperbaiki'],
            'kondisi_sebelum' => ['required', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'kondisi_sesudah' => ['nullable', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'bukti_sebelum' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'bukti_sesudah' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'bukti_invoice' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sarana_umum_id.required' => 'Sarana wajib dipilih.',
            'tanggal_lapor.required' => 'Tanggal lapor wajib diisi.',
            'keluhan.required' => 'Keluhan wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'kondisi_sebelum.required' => 'Kondisi sebelum wajib dipilih.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum tanggal lapor.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'sla_deadline.after_or_equal' => 'Deadline SLA tidak boleh sebelum tanggal lapor.',
            'bukti_sebelum.max' => 'Lampiran bukti sebelum maksimal 2MB.',
            'bukti_sesudah.max' => 'Lampiran bukti sesudah maksimal 2MB.',
            'bukti_invoice.max' => 'Lampiran invoice maksimal 2MB.',
        ];
    }
}
