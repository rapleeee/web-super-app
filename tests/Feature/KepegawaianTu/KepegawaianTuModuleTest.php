<?php

use App\Models\BeritaAcara;
use App\Models\IzinKaryawan;
use App\Models\SaranaUmumBeritaAcara;
use App\Models\TuArsipDokumen;
use App\Models\TuSurat;
use App\Models\TuSuratTemplate;
use App\Models\User;

beforeEach(function () {
    $this->laboran = User::factory()->laboran()->create();
    $this->admin = User::factory()->admin()->create();
    $this->pejabat = User::factory()->pejabat()->create();
    $this->staff = User::factory()->staff()->create();
    $this->staffTwo = User::factory()->staff()->create();
    $this->guru = User::factory()->create(['role' => 'guru']);
});

test('kepegawaian tu dashboard and menu pages can be rendered', function () {
    $this->actingAs($this->laboran)
        ->get(route('kepegawaian-tu.dashboard'))
        ->assertSuccessful();

    $this->actingAs($this->laboran)
        ->get(route('kepegawaian-tu.berita-acara-final.index'))
        ->assertSuccessful();

    $this->actingAs($this->laboran)
        ->get(route('kepegawaian-tu.izin-karyawan.index'))
        ->assertSuccessful();

    $this->actingAs($this->laboran)
        ->get(route('kepegawaian-tu.arsip-digital.index'))
        ->assertSuccessful();
});

test('approval center and audit log can only be accessed by admin or pejabat', function () {
    $this->actingAs($this->admin)
        ->get(route('kepegawaian-tu.pusat-approval.index'))
        ->assertSuccessful();

    $this->actingAs($this->pejabat)
        ->get(route('kepegawaian-tu.audit-log.index'))
        ->assertSuccessful();

    $this->actingAs($this->staff)
        ->get(route('kepegawaian-tu.pusat-approval.index'))
        ->assertForbidden();

    $this->actingAs($this->laboran)
        ->get(route('kepegawaian-tu.audit-log.index'))
        ->assertForbidden();
});

test('template surat can only be accessed by admin or pejabat', function () {
    $this->actingAs($this->admin)
        ->get(route('kepegawaian-tu.template-surat.index'))
        ->assertSuccessful();

    $this->actingAs($this->pejabat)
        ->get(route('kepegawaian-tu.template-surat.index'))
        ->assertSuccessful();

    $this->actingAs($this->staff)
        ->get(route('kepegawaian-tu.template-surat.index'))
        ->assertForbidden();
});

test('inbox berita acara final merges laboran and sarana umum records', function () {
    BeritaAcara::factory()->final()->create([
        'nama_guru' => 'Guru Laboran Final',
    ]);

    SaranaUmumBeritaAcara::factory()->create([
        'status' => 'final',
        'nama_guru' => 'Guru Sarana Umum Final',
    ]);

    $response = $this->actingAs($this->laboran)
        ->get(route('kepegawaian-tu.berita-acara-final.index'));

    $response->assertSuccessful();
    $response->assertSee('Guru Laboran Final');
    $response->assertSee('Guru Sarana Umum Final');
    $response->assertSee('Laboran');
    $response->assertSee('Sarana Umum');
});

test('staff can create izin karyawan', function () {
    $response = $this->actingAs($this->staff)
        ->post(route('kepegawaian-tu.izin-karyawan.store'), [
            'nama_karyawan' => 'Andi Karyawan',
            'jenis' => 'izin',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDay()->toDateString(),
            'alasan' => 'Urusan keluarga.',
        ]);

    $response->assertRedirect(route('kepegawaian-tu.izin-karyawan.index'));

    $this->assertDatabaseHas('izin_karyawans', [
        'nama_karyawan' => 'Andi Karyawan',
        'status' => 'diajukan',
        'user_id' => $this->staff->id,
    ]);
});

test('non tu role can submit izin karyawan and only access own izin records', function () {
    $this->actingAs($this->guru)
        ->get(route('kepegawaian-tu.izin-karyawan.index'))
        ->assertSuccessful();

    $response = $this->actingAs($this->guru)
        ->post(route('kepegawaian-tu.izin-karyawan.store'), [
            'nama_karyawan' => 'Guru Non TU',
            'jenis' => 'izin',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDay()->toDateString(),
            'alasan' => 'Keperluan keluarga.',
        ]);

    $response->assertRedirect(route('kepegawaian-tu.izin-karyawan.index'));

    $izinGuru = IzinKaryawan::query()
        ->where('user_id', $this->guru->id)
        ->latest('id')
        ->first();

    expect($izinGuru)->not->toBeNull();

    IzinKaryawan::factory()->create([
        'user_id' => $this->staff->id,
        'nama_karyawan' => 'Staff Internal TU',
        'status' => 'diajukan',
    ]);

    $this->actingAs($this->guru)
        ->get(route('kepegawaian-tu.izin-karyawan.index'))
        ->assertSuccessful()
        ->assertSee('Guru Non TU')
        ->assertDontSee('Staff Internal TU');

    $this->actingAs($this->guru)
        ->get(route('kepegawaian-tu.izin-karyawan.show', $izinGuru))
        ->assertSuccessful();
});

test('non tu role can not access tu internal dashboard and can not read other user izin detail', function () {
    $izinStaff = IzinKaryawan::factory()->create([
        'user_id' => $this->staff->id,
    ]);

    $this->actingAs($this->guru)
        ->get(route('kepegawaian-tu.dashboard'))
        ->assertForbidden();

    $this->actingAs($this->guru)
        ->get(route('kepegawaian-tu.izin-karyawan.show', $izinStaff))
        ->assertForbidden();
});

test('staff can create surat draft', function () {
    $template = TuSuratTemplate::factory()->create(['created_by' => $this->admin->id]);

    $response = $this->actingAs($this->staff)
        ->post(route('kepegawaian-tu.surat.store'), [
            'tu_surat_template_id' => $template->id,
            'perihal' => 'Permohonan Surat Keterangan',
            'tujuan' => 'Kepala Sekolah',
            'tanggal_surat' => now()->toDateString(),
            'isi_surat' => 'Isi draft surat pengajuan.',
        ]);

    $surat = TuSurat::query()->first();

    $response->assertRedirect(route('kepegawaian-tu.surat.show', $surat));
    $this->assertDatabaseHas('tu_surats', [
        'id' => $surat->id,
        'status' => 'draft',
        'created_by' => $this->staff->id,
    ]);
});

test('owner can submit surat draft for review', function () {
    $surat = TuSurat::factory()->create([
        'created_by' => $this->staff->id,
        'status' => 'draft',
    ]);

    $this->actingAs($this->staff)
        ->patch(route('kepegawaian-tu.surat.submit-review', $surat))
        ->assertRedirect(route('kepegawaian-tu.surat.show', $surat));

    $this->assertDatabaseHas('tu_surats', [
        'id' => $surat->id,
        'status' => 'review',
    ]);
});

test('admin can finalize surat and generate nomor with verification token', function () {
    $surat = TuSurat::factory()->create([
        'created_by' => $this->staff->id,
        'status' => 'review',
        'nomor_surat' => null,
        'verification_token' => null,
    ]);

    $this->actingAs($this->admin)
        ->patch(route('kepegawaian-tu.surat.approve-final', $surat), [
            'tanggal_surat' => now()->toDateString(),
        ])
        ->assertRedirect(route('kepegawaian-tu.surat.show', $surat));

    $surat->refresh();

    expect($surat->status)->toBe('final')
        ->and($surat->nomor_surat)->not->toBeNull()
        ->and($surat->verification_token)->not->toBeNull()
        ->and($surat->approved_by)->toBe($this->admin->id);
});

test('non owner staff can not view another staff surat', function () {
    $surat = TuSurat::factory()->create([
        'created_by' => $this->staff->id,
    ]);

    $this->actingAs($this->staffTwo)
        ->get(route('kepegawaian-tu.surat.show', $surat))
        ->assertForbidden();
});

test('public verification page is accessible for final surat with valid token', function () {
    $surat = TuSurat::factory()->final()->create([
        'created_by' => $this->staff->id,
        'approved_by' => $this->admin->id,
        'verification_token' => 'token-verifikasi-surat-tu',
    ]);

    $this->get(route('kepegawaian-tu.surat.verify', [
        'tuSurat' => $surat,
        'token' => $surat->verification_token,
    ]))
        ->assertSuccessful()
        ->assertSee('Dokumen Terverifikasi')
        ->assertSee((string) $surat->nomor_surat);
});

test('staff can not create overlapping izin request', function () {
    IzinKaryawan::factory()->create([
        'user_id' => $this->staff->id,
        'status' => 'diajukan',
        'tanggal_mulai' => '2026-03-10',
        'tanggal_selesai' => '2026-03-12',
    ]);

    $response = $this->actingAs($this->staff)
        ->from(route('kepegawaian-tu.izin-karyawan.create'))
        ->post(route('kepegawaian-tu.izin-karyawan.store'), [
            'nama_karyawan' => 'Andi Karyawan',
            'jenis' => 'izin',
            'tanggal_mulai' => '2026-03-11',
            'tanggal_selesai' => '2026-03-13',
            'alasan' => 'Ada agenda keluarga.',
        ]);

    $response->assertRedirect(route('kepegawaian-tu.izin-karyawan.create'));
    $response->assertSessionHasErrors(['tanggal_mulai']);
});

test('staff can not exceed yearly cuti quota', function () {
    IzinKaryawan::factory()->create([
        'user_id' => $this->staff->id,
        'jenis' => 'cuti',
        'status' => 'disetujui',
        'tanggal_mulai' => '2026-01-01',
        'tanggal_selesai' => '2026-01-10',
    ]);

    $response = $this->actingAs($this->staff)
        ->from(route('kepegawaian-tu.izin-karyawan.create'))
        ->post(route('kepegawaian-tu.izin-karyawan.store'), [
            'nama_karyawan' => 'Andi Karyawan',
            'jenis' => 'cuti',
            'tanggal_mulai' => '2026-02-01',
            'tanggal_selesai' => '2026-02-03',
            'alasan' => 'Cuti tahunan.',
        ]);

    $response->assertRedirect(route('kepegawaian-tu.izin-karyawan.create'));
    $response->assertSessionHasErrors(['jenis']);
});

test('admin can approve izin karyawan', function () {
    $izin = IzinKaryawan::factory()->create([
        'user_id' => $this->staff->id,
        'status' => 'diajukan',
        'approved_by' => null,
        'approved_at' => null,
    ]);

    $response = $this->actingAs($this->admin)
        ->patch(route('kepegawaian-tu.izin-karyawan.approval', $izin), [
            'status' => 'disetujui',
            'catatan_persetujuan' => 'Disetujui admin TU.',
        ]);

    $response->assertRedirect(route('kepegawaian-tu.izin-karyawan.show', $izin));

    $this->assertDatabaseHas('izin_karyawans', [
        'id' => $izin->id,
        'status' => 'disetujui',
        'approved_by' => $this->admin->id,
        'catatan_persetujuan' => 'Disetujui admin TU.',
    ]);
});

test('admin must fill surat tugas fields when approving dinas luar request', function () {
    $izin = IzinKaryawan::factory()->dinasLuar()->create([
        'user_id' => $this->staff->id,
        'status' => 'diajukan',
    ]);

    $response = $this->actingAs($this->admin)
        ->from(route('kepegawaian-tu.izin-karyawan.show', $izin))
        ->patch(route('kepegawaian-tu.izin-karyawan.approval', $izin), [
            'status' => 'disetujui',
            'catatan_persetujuan' => 'Disetujui.',
        ]);

    $response->assertRedirect(route('kepegawaian-tu.izin-karyawan.show', $izin));
    $response->assertSessionHasErrors(['surat_tugas_nomor', 'surat_tugas_sebagai']);
});

test('approving dinas luar generates surat tugas data and printable page', function () {
    $izin = IzinKaryawan::factory()->dinasLuar()->create([
        'user_id' => $this->staff->id,
        'status' => 'diajukan',
    ]);

    $this->actingAs($this->admin)
        ->patch(route('kepegawaian-tu.izin-karyawan.approval', $izin), [
            'status' => 'disetujui',
            'catatan_persetujuan' => 'Disetujui admin TU.',
            'surat_tugas_nomor' => '090/ST-TU/III/2026',
            'surat_tugas_sebagai' => 'Peserta koordinasi sarpras',
        ])
        ->assertRedirect(route('kepegawaian-tu.izin-karyawan.show', $izin));

    $izin->refresh();

    expect($izin->hasSuratTugas())->toBeTrue()
        ->and($izin->surat_tugas_nomor)->toBe('090/ST-TU/III/2026')
        ->and($izin->surat_tugas_signature_token)->not->toBeNull();

    $this->actingAs($this->staff)
        ->get(route('kepegawaian-tu.izin-karyawan.surat-tugas', $izin))
        ->assertSuccessful()
        ->assertSee('SURAT TUGAS')
        ->assertSee('090/ST-TU/III/2026')
        ->assertSee('Adhi Rachmat Saputra, S.Kom');
});

test('non admin can not approve izin karyawan', function () {
    $izin = IzinKaryawan::factory()->create([
        'user_id' => $this->staff->id,
        'status' => 'diajukan',
    ]);

    $this->actingAs($this->staff)
        ->patch(route('kepegawaian-tu.izin-karyawan.approval', $izin), [
            'status' => 'disetujui',
        ])
        ->assertForbidden();
});

test('pejabat can approve izin karyawan', function () {
    $izin = IzinKaryawan::factory()->create([
        'user_id' => $this->staff->id,
        'status' => 'diajukan',
    ]);

    $this->actingAs($this->pejabat)
        ->patch(route('kepegawaian-tu.izin-karyawan.approval', $izin), [
            'status' => 'disetujui',
            'catatan_persetujuan' => 'Approved by pejabat.',
        ])
        ->assertRedirect(route('kepegawaian-tu.izin-karyawan.show', $izin));

    $this->assertDatabaseHas('izin_karyawans', [
        'id' => $izin->id,
        'status' => 'disetujui',
        'approved_by' => $this->pejabat->id,
    ]);
});

test('kepegawaian tu berita acara final can be exported to csv', function () {
    BeritaAcara::factory()->final()->create();
    SaranaUmumBeritaAcara::factory()->create(['status' => 'final']);

    $response = $this->actingAs($this->laboran)
        ->get(route('kepegawaian-tu.berita-acara-final.export'));

    $response->assertSuccessful();
    $response->assertHeader('content-disposition', 'attachment; filename="tu-berita-acara-final.csv"');
    $response->assertSee('Tanggal,Sumber,"Nama Guru"', false);

    $this->assertDatabaseHas('audit_logs', [
        'module' => 'tu-berita-acara-final',
        'action' => 'export',
    ]);
});

test('admin can update tindak lanjut berita acara final and sync to arsip digital', function () {
    $beritaAcara = BeritaAcara::factory()->final()->create([
        'nama_guru' => 'Guru Uji TL',
    ]);

    $this->actingAs($this->admin)
        ->patch(route('kepegawaian-tu.berita-acara-final.tindak-lanjut', [
            'sourceType' => 'laboran',
            'sourceId' => $beritaAcara->id,
        ]), [
            'status' => 'diproses',
            'catatan' => 'Dicek tim TU.',
            'tags' => 'urgent, sarpras,urgent',
        ])
        ->assertRedirect(route('kepegawaian-tu.berita-acara-final.index'));

    $this->assertDatabaseHas('tu_berita_acara_tindak_lanjuts', [
        'source_type' => 'laboran',
        'source_id' => $beritaAcara->id,
        'status' => 'diproses',
        'processed_by' => $this->admin->id,
    ]);

    $arsip = TuArsipDokumen::query()
        ->where('module', 'tu-berita-acara-final')
        ->where('source_type', 'laboran')
        ->where('source_id', $beritaAcara->id)
        ->first();

    expect($arsip)->not->toBeNull()
        ->and($arsip?->status_sumber)->toBe('diproses')
        ->and($arsip?->tags)->toContain('urgent')
        ->and($arsip?->version)->toBe(1);
});

test('non privileged user can not update tindak lanjut berita acara final', function () {
    $beritaAcara = BeritaAcara::factory()->final()->create();

    $this->actingAs($this->staff)
        ->patch(route('kepegawaian-tu.berita-acara-final.tindak-lanjut', [
            'sourceType' => 'laboran',
            'sourceId' => $beritaAcara->id,
        ]), [
            'status' => 'selesai',
        ])
        ->assertForbidden();
});

test('arsip digital metadata can be updated by admin and version is incremented', function () {
    $arsip = TuArsipDokumen::factory()->create([
        'module' => 'tu-surat',
        'status_sumber' => 'final',
        'tags' => ['surat', 'final'],
        'version' => 1,
        'metadata' => ['asal' => 'test'],
    ]);

    $this->actingAs($this->admin)
        ->patch(route('kepegawaian-tu.arsip-digital.update', $arsip), [
            'tags' => 'surat,legal',
            'retensi_sampai' => now()->addYear()->toDateString(),
            'catatan_versi' => 'Penyesuaian retensi.',
        ])
        ->assertRedirect(route('kepegawaian-tu.arsip-digital.show', $arsip));

    $arsip->refresh();

    expect($arsip->version)->toBe(2)
        ->and($arsip->tags)->toContain('legal')
        ->and(data_get($arsip->metadata, 'catatan_versi_terakhir'))->toBe('Penyesuaian retensi.');
});

test('non privileged user can not update arsip digital metadata', function () {
    $arsip = TuArsipDokumen::factory()->create();

    $this->actingAs($this->staff)
        ->patch(route('kepegawaian-tu.arsip-digital.update', $arsip), [
            'tags' => 'x,y,z',
        ])
        ->assertForbidden();
});

test('surat final and archive are synced to arsip digital with version update', function () {
    $surat = TuSurat::factory()->create([
        'created_by' => $this->staff->id,
        'status' => 'review',
        'nomor_surat' => null,
        'verification_token' => null,
    ]);

    $this->actingAs($this->admin)
        ->patch(route('kepegawaian-tu.surat.approve-final', $surat), [
            'tanggal_surat' => now()->toDateString(),
        ])
        ->assertRedirect(route('kepegawaian-tu.surat.show', $surat));

    $arsipSaatFinal = TuArsipDokumen::query()
        ->where('module', 'tu-surat')
        ->where('source_type', TuSurat::class)
        ->where('source_id', $surat->id)
        ->first();

    expect($arsipSaatFinal)->not->toBeNull()
        ->and($arsipSaatFinal?->status_sumber)->toBe('final')
        ->and($arsipSaatFinal?->version)->toBe(1);

    $this->actingAs($this->admin)
        ->patch(route('kepegawaian-tu.surat.archive', $surat))
        ->assertRedirect(route('kepegawaian-tu.surat.show', $surat));

    $arsipSaatArsip = TuArsipDokumen::query()
        ->where('module', 'tu-surat')
        ->where('source_type', TuSurat::class)
        ->where('source_id', $surat->id)
        ->first();

    expect($arsipSaatArsip)->not->toBeNull()
        ->and($arsipSaatArsip?->status_sumber)->toBe('arsip')
        ->and((int) ($arsipSaatArsip?->version ?? 0))->toBeGreaterThan(1);
});

test('kepegawaian tu izin karyawan can be exported to csv', function () {
    IzinKaryawan::factory()->create([
        'user_id' => $this->staff->id,
        'status' => 'diajukan',
    ]);

    $response = $this->actingAs($this->laboran)
        ->get(route('kepegawaian-tu.izin-karyawan.export'));

    $response->assertSuccessful();
    $response->assertHeader('content-disposition', 'attachment; filename="tu-izin-karyawan.csv"');
    $response->assertSee('Nomor,"Nama Karyawan",Jenis', false);

    $this->assertDatabaseHas('audit_logs', [
        'module' => 'tu-izin-karyawan',
        'action' => 'export',
    ]);
});
