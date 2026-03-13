<?php

namespace App\Http\Requests\Laboran;

use Illuminate\Foundation\Http\FormRequest;

class BulkUpdateUnitKomputerRequest extends FormRequest
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
            'unit_ids' => ['required', 'array', 'min:1'],
            'unit_ids.*' => ['required', 'integer', 'distinct', 'exists:unit_komputers,id'],
            'mode' => ['required', 'in:overwrite,fill_empty'],
            'laboratorium_id' => ['nullable', 'integer', 'exists:laboratoriums,id'],
            'nomor_meja' => ['nullable', 'integer', 'min:1', 'max:100'],
            'kondisi' => ['nullable', 'in:baik,rusak_ringan,rusak_berat,mati_total'],
            'status' => ['nullable', 'in:aktif,dalam_perbaikan,tidak_aktif'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (! $this->hasAnyFilledFields()) {
                $validator->errors()->add('bulk_fields', 'Isi minimal satu field untuk diproses secara massal.');
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'unit_ids.required' => 'Pilih minimal satu unit komputer.',
            'unit_ids.array' => 'Format data unit tidak valid.',
            'unit_ids.min' => 'Pilih minimal satu unit komputer.',
            'mode.required' => 'Mode bulk action wajib dipilih.',
            'mode.in' => 'Mode bulk action tidak valid.',
            'laboratorium_id.exists' => 'Laboratorium tidak valid.',
            'nomor_meja.integer' => 'Nomor meja harus berupa angka.',
            'nomor_meja.min' => 'Nomor meja minimal 1.',
            'nomor_meja.max' => 'Nomor meja maksimal 100.',
            'kondisi.in' => 'Nilai kondisi tidak valid.',
            'status.in' => 'Nilai status tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ];
    }

    private function hasAnyFilledFields(): bool
    {
        return $this->filled('laboratorium_id')
            || $this->filled('nomor_meja')
            || $this->filled('kondisi')
            || $this->filled('status')
            || $this->filled('keterangan');
    }
}
