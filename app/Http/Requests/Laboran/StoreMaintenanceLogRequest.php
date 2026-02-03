<?php

namespace App\Http\Requests\Laboran;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceLogRequest extends FormRequest
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
            'komponen_perangkat_id' => ['required', 'exists:komponen_perangkats,id'],
            'tanggal_lapor' => ['required', 'date'],
            'tanggal_mulai' => ['nullable', 'date', 'after_or_equal:tanggal_lapor'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'keluhan' => ['required', 'string', 'max:1000'],
            'diagnosa' => ['nullable', 'string', 'max:1000'],
            'tindakan' => ['nullable', 'string', 'max:1000'],
            'teknisi' => ['nullable', 'string', 'max:100'],
            'biaya' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,proses,selesai,tidak_bisa_diperbaiki'],
            'kondisi_sebelum' => ['required', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'kondisi_sesudah' => ['nullable', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'catatan' => ['nullable', 'string', 'max:500'],
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
            'komponen_perangkat_id.required' => 'Komponen wajib dipilih.',
            'tanggal_lapor.required' => 'Tanggal lapor wajib diisi.',
            'keluhan.required' => 'Keluhan wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'kondisi_sebelum.required' => 'Kondisi sebelum wajib dipilih.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum tanggal lapor.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ];
    }
}
