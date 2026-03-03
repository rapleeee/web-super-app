<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Tugas - {{ $izinKaryawan->surat_tugas_nomor }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; color: #111827; margin: 0; padding: 28px; }
        .sheet { max-width: 850px; margin: 0 auto; }
        .kop { text-align: center; border-bottom: 3px solid #111827; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h1 { font-size: 19px; margin: 0; letter-spacing: 0.7px; text-transform: uppercase; }
        .kop p { margin: 4px 0 0; font-size: 14px; }
        .title { text-align: center; margin: 24px 0 18px; }
        .title h2 { margin: 0; font-size: 20px; letter-spacing: 1px; text-transform: uppercase; text-decoration: underline; }
        .title p { margin: 8px 0 0; font-size: 14px; }
        .paragraph { font-size: 16px; line-height: 1.7; text-align: justify; }
        .indent { text-indent: 44px; }
        .detail-table { width: 100%; margin: 8px 0 16px 0; border-collapse: collapse; }
        .detail-table td { padding: 3px 0; font-size: 16px; vertical-align: top; }
        .detail-table td:first-child { width: 180px; }
        .detail-table td:nth-child(2) { width: 14px; }
        .signature { margin-top: 36px; width: 100%; display: flex; justify-content: flex-end; }
        .signature-box { width: 300px; text-align: left; font-size: 15px; }
        .digital-note { margin-top: 72px; font-size: 12px; color: #374151; }
        .actions { margin-bottom: 16px; }
        @media print {
            .actions { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
<div class="sheet">
    <div class="actions">
        <button onclick="window.print()" style="background:#1f2937;color:#fff;border:0;border-radius:8px;padding:10px 14px;cursor:pointer;">Cetak / Simpan PDF</button>
        <a href="{{ route('kepegawaian-tu.izin-karyawan.show', $izinKaryawan) }}" style="margin-left:8px;color:#1f2937;">Kembali</a>
    </div>

    <div class="kop">
        <h1>SURAT TUGAS</h1>
        <p>{{ $instansi }}</p>
    </div>

    <div class="title">
        <h2>Surat Tugas</h2>
        <p>Nomor: {{ $izinKaryawan->surat_tugas_nomor }}</p>
    </div>

    <div class="paragraph">
        <p class="indent">
            Kepala SMK Informatika Pesat Kota Bogor dengan ini menugaskan kepada:
        </p>

        <table class="detail-table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $izinKaryawan->nama_karyawan }}</td>
            </tr>
            <tr>
                <td>Tanggal Tugas</td>
                <td>:</td>
                <td>{{ $izinKaryawan->tanggal_mulai->format('d M Y') }} s/d {{ $izinKaryawan->tanggal_selesai->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>{{ $izinKaryawan->alasan }}</td>
            </tr>
        </table>

        <p class="indent">
            Sebagai {{ $izinKaryawan->surat_tugas_sebagai }}, yang akan dilaksanakan pada:
        </p>

        <table class="detail-table">
            <tr>
                <td>Hari</td>
                <td>:</td>
                <td>{{ $izinKaryawan->dinas_luar_hari }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ $izinKaryawan->dinas_luar_waktu }}</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $izinKaryawan->dinas_luar_tempat }}</td>
            </tr>
        </table>

        <p class="indent">
            Demikian surat tugas ini diberikan untuk dilaksanakan dengan penuh tanggung jawab.
        </p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>Bogor, {{ ($izinKaryawan->surat_tugas_diterbitkan_at ?? now())->format('d M Y') }}</p>
            <p>{{ $jabatanPenandatangan }},</p>
            <p class="digital-note">Ditandatangani secara digital pada {{ $izinKaryawan->surat_tugas_signed_at?->format('d M Y H:i') ?? '-' }}</p>
            <p class="digital-note">Token: {{ strtoupper(substr((string) $izinKaryawan->surat_tugas_signature_token, 0, 12)) }}</p>
            <p style="margin-top: 10px; font-weight: 700; text-decoration: underline;">{{ $kepalaSekolah }}</p>
        </div>
    </div>
</div>
</body>
</html>
