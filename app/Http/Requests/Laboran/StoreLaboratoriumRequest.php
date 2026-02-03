<?php

namespace App\Http\Requests\Laboran;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaboratoriumRequest extends FormRequest
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
            'kode' => ['required', 'string', 'max:20', 'unique:laboratoriums,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:aktif,nonaktif,renovasi'],
            'deskripsi' => ['nullable', 'string'],
            'jurusan' => ['required', 'in:DKV,TKJ,RPL'],
            'penanggung_jawab_id' => ['nullable', 'exists:laborans,id'],
            'fasilitas' => ['nullable', 'array'],
            'fasilitas.*' => ['string'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
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
            'kode.required' => 'Kode laboratorium wajib diisi.',
            'kode.unique' => 'Kode laboratorium sudah terdaftar.',
            'nama.required' => 'Nama laboratorium wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 1.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'jurusan.required' => 'Jurusan wajib dipilih.',
            'jurusan.in' => 'Jurusan tidak valid.',
            'penanggung_jawab_id.exists' => 'Penanggung jawab tidak ditemukan.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
