<?php

return [
    'izin' => [
        'sla_hari' => (int) env('TU_IZIN_SLA_HARI', 3),
        'kuota_cuti_tahunan' => (int) env('TU_KUOTA_CUTI_TAHUNAN', 12),
        'lampiran_max_kb' => (int) env('TU_LAMPIRAN_MAX_KB', 2048),
    ],
    'arsip' => [
        'retensi_tahun_default' => (int) env('TU_ARSIP_RETENSI_TAHUN', 5),
    ],
    'surat_tugas' => [
        'instansi' => env('TU_SURAT_TUGAS_INSTANSI', 'SMK Informatika Pesat Kota Bogor'),
        'kepala_sekolah' => env('TU_SURAT_TUGAS_KEPALA_SEKOLAH', 'Adhi Rachmat Saputra, S.Kom'),
        'jabatan_penandatangan' => env('TU_SURAT_TUGAS_JABATAN', 'Kepala Sekolah'),
    ],
];
