<?php

namespace App\Http\Requests\SaranaUmum;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreventiveMaintenanceRequest extends FormRequest
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
            'nama_tugas' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'interval_hari' => ['required', 'integer', 'min:1', 'max:365'],
            'toleransi_hari' => ['nullable', 'integer', 'min:0', 'max:30'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_maintenance_terakhir' => ['nullable', 'date'],
            'tanggal_maintenance_berikutnya' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sarana_umum_id.required' => 'Sarana umum wajib dipilih.',
            'nama_tugas.required' => 'Nama tugas preventive wajib diisi.',
            'interval_hari.required' => 'Interval hari wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_maintenance_berikutnya.required' => 'Tanggal maintenance berikutnya wajib diisi.',
        ];
    }
}
