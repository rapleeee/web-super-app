<?php

namespace App\Http\Requests\Laboran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitKomputerRequest extends FormRequest
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
            'kode_unit' => ['required', 'string', 'max:50', Rule::unique('unit_komputers', 'kode_unit')->ignore($this->route('unit_komputer'))],
            'nama' => ['required', 'string', 'max:100'],
            'laboratorium_id' => ['required', 'exists:laboratoriums,id'],
            'nomor_meja' => ['nullable', 'integer', 'min:1', 'max:100'],
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'status' => ['required', 'in:aktif,dalam_perbaikan,tidak_aktif'],
            'keterangan' => ['nullable', 'string', 'max:500'],
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
            'kode_unit.required' => 'Kode unit wajib diisi.',
            'kode_unit.unique' => 'Kode unit sudah digunakan.',
            'nama.required' => 'Nama unit wajib diisi.',
            'laboratorium_id.required' => 'Laboratorium wajib dipilih.',
            'laboratorium_id.exists' => 'Laboratorium tidak valid.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
        ];
    }
}
