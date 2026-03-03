<?php

namespace App\Services\KepegawaianTu;

use App\Models\TuArsipDokumen;
use App\Models\TuBeritaAcaraTindakLanjut;
use App\Models\TuSurat;
use Carbon\Carbon;

class ArsipDokumenService
{
    /**
     * @param  array<string, mixed>  $record
     */
    public function syncFromBeritaAcara(
        string $sourceType,
        int $sourceId,
        array $record,
        TuBeritaAcaraTindakLanjut $tindakLanjut,
        ?int $actorId = null
    ): TuArsipDokumen {
        $tanggalDokumen = isset($record['tanggal']) ? Carbon::parse((string) $record['tanggal']) : null;
        $retensiSampai = $tanggalDokumen?->copy()->addYears($this->retensiYears());

        $tags = array_values(array_unique(array_filter(array_merge(
            ['berita-acara', 'final', str_replace('_', '-', $sourceType)],
            $tindakLanjut->tags ?? []
        ))));

        $payload = [
            'module' => 'tu-berita-acara-final',
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'judul' => 'Berita Acara Final - '.($record['nama_guru'] ?? '-').' ('.strtoupper($sourceType).')',
            'nomor_dokumen' => null,
            'tanggal_dokumen' => $tanggalDokumen?->toDateString(),
            'status_sumber' => $tindakLanjut->status,
            'tags' => $tags,
            'metadata' => [
                'sumber' => $record['sumber'] ?? strtoupper($sourceType),
                'nama_guru' => $record['nama_guru'] ?? null,
                'ruangan' => $record['ruangan'] ?? null,
                'kegiatan' => $record['kegiatan'] ?? null,
                'petugas' => $record['petugas'] ?? null,
                'route_detail' => $record['route_detail'] ?? null,
                'catatan_tindak_lanjut' => $tindakLanjut->catatan,
            ],
            'retensi_sampai' => $retensiSampai?->toDateString(),
            'archived_at' => $tindakLanjut->status === 'arsip' ? now() : null,
            'archived_by' => $tindakLanjut->status === 'arsip' ? $actorId : null,
        ];

        return $this->upsertVersioned($payload);
    }

    public function syncFromSurat(TuSurat $surat, ?int $actorId = null): TuArsipDokumen
    {
        $retensiSampai = $surat->tanggal_surat
            ? $surat->tanggal_surat->copy()->addYears($this->retensiYears())
            : null;

        $metadata = [
            'tujuan' => $surat->tujuan,
            'template' => $surat->template?->nama,
            'verification_token' => $surat->verification_token,
        ];

        $tags = ['surat', 'tu', $surat->status];

        $payload = [
            'module' => 'tu-surat',
            'source_type' => TuSurat::class,
            'source_id' => $surat->id,
            'judul' => $surat->perihal,
            'nomor_dokumen' => $surat->nomor_surat,
            'tanggal_dokumen' => $surat->tanggal_surat?->toDateString(),
            'status_sumber' => $surat->status,
            'tags' => $tags,
            'metadata' => $metadata,
            'retensi_sampai' => $retensiSampai?->toDateString(),
            'archived_at' => $surat->status === 'arsip' ? ($surat->archived_at ?? now()) : null,
            'archived_by' => $surat->status === 'arsip' ? $actorId : null,
        ];

        return $this->upsertVersioned($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function upsertVersioned(array $payload): TuArsipDokumen
    {
        $arsip = TuArsipDokumen::query()->firstOrNew([
            'module' => $payload['module'],
            'source_type' => $payload['source_type'],
            'source_id' => $payload['source_id'],
        ]);

        $isNew = ! $arsip->exists;

        $dirty = false;
        foreach ([
            'judul',
            'nomor_dokumen',
            'tanggal_dokumen',
            'status_sumber',
            'tags',
            'metadata',
            'retensi_sampai',
            'archived_at',
            'archived_by',
        ] as $field) {
            $incoming = $payload[$field] ?? null;
            if ($arsip->getAttribute($field) != $incoming) {
                $dirty = true;
            }
            $arsip->setAttribute($field, $incoming);
        }

        if ($isNew) {
            $arsip->version = 1;
        } elseif ($dirty) {
            $arsip->version = (int) $arsip->version + 1;
        }

        $arsip->save();

        return $arsip;
    }

    private function retensiYears(): int
    {
        return max((int) config('kepegawaian_tu.arsip.retensi_tahun_default', 5), 1);
    }
}
