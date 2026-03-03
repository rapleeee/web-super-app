<?php

namespace App\Http\Requests\KepegawaianTu\Concerns;

use App\Models\IzinKaryawan;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;

trait ValidatesIzinKaryawanBusinessRules
{
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $this->validateTanggalBentrok($validator);

            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $this->validateKuotaCutiTahunan($validator);
        });
    }

    private function validateTanggalBentrok(Validator $validator): void
    {
        $tanggalMulai = $this->input('tanggal_mulai');
        $tanggalSelesai = $this->input('tanggal_selesai');

        if (! $tanggalMulai || ! $tanggalSelesai || ! $this->user()) {
            return;
        }

        $query = IzinKaryawan::query()
            ->where('user_id', $this->user()->id)
            ->whereIn('status', ['diajukan', 'disetujui'])
            ->whereDate('tanggal_mulai', '<=', $tanggalSelesai)
            ->whereDate('tanggal_selesai', '>=', $tanggalMulai);

        $excludedIzinId = $this->excludedIzinId();
        if ($excludedIzinId !== null) {
            $query->whereKeyNot($excludedIzinId);
        }

        if ($query->exists()) {
            $validator->errors()->add('tanggal_mulai', 'Rentang tanggal bentrok dengan pengajuan izin yang masih aktif.');
        }
    }

    private function validateKuotaCutiTahunan(Validator $validator): void
    {
        if ($this->input('jenis') !== 'cuti' || ! $this->user()) {
            return;
        }

        $tanggalMulai = Carbon::parse((string) $this->input('tanggal_mulai'));
        $tanggalSelesai = Carbon::parse((string) $this->input('tanggal_selesai'));

        if ($tanggalMulai->year !== $tanggalSelesai->year) {
            $validator->errors()->add('tanggal_selesai', 'Pengajuan cuti harus berada dalam tahun kalender yang sama.');

            return;
        }

        $tahunCuti = $tanggalMulai->year;
        $durasiPengajuan = $tanggalMulai->diffInDays($tanggalSelesai) + 1;
        $kuotaTahunan = max((int) config('kepegawaian_tu.izin.kuota_cuti_tahunan', 12), 1);

        $query = IzinKaryawan::query()
            ->where('user_id', $this->user()->id)
            ->where('jenis', 'cuti')
            ->whereIn('status', ['diajukan', 'disetujui'])
            ->whereYear('tanggal_mulai', $tahunCuti);

        $excludedIzinId = $this->excludedIzinId();
        if ($excludedIzinId !== null) {
            $query->whereKeyNot($excludedIzinId);
        }

        $durasiTerpakai = $query
            ->get(['tanggal_mulai', 'tanggal_selesai'])
            ->sum(fn (IzinKaryawan $izin): int => $izin->tanggal_mulai->diffInDays($izin->tanggal_selesai) + 1);

        if (($durasiTerpakai + $durasiPengajuan) > $kuotaTahunan) {
            $sisaKuota = max($kuotaTahunan - $durasiTerpakai, 0);

            $validator->errors()->add(
                'jenis',
                "Kuota cuti tahun {$tahunCuti} tidak cukup. Sisa kuota: {$sisaKuota} hari dari total {$kuotaTahunan} hari."
            );
        }
    }

    private function excludedIzinId(): ?int
    {
        $izinKaryawan = $this->route('izinKaryawan');

        if ($izinKaryawan instanceof IzinKaryawan) {
            return $izinKaryawan->getKey();
        }

        if (is_numeric($izinKaryawan)) {
            return (int) $izinKaryawan;
        }

        return null;
    }
}
