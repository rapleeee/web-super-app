<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tuSurat->nomor_surat }} - Cetak Surat</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; margin: 0; padding: 28px; }
        .sheet { max-width: 900px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .meta p { margin: 0 0 6px; font-size: 13px; }
        .title { text-align: center; margin: 16px 0 24px; }
        .title h1 { font-size: 20px; margin: 0 0 8px; }
        .title p { margin: 0; font-size: 13px; color: #374151; }
        .content { white-space: pre-wrap; font-size: 14px; line-height: 1.65; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; }
        .verify { font-size: 12px; color: #374151; max-width: 380px; }
        .actions { margin-top: 20px; }
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
        <a href="{{ route('kepegawaian-tu.surat.show', $tuSurat) }}" style="margin-left:8px;color:#1f2937;">Kembali</a>
    </div>

    <div class="header">
        <div class="meta">
            <p><strong>Nomor:</strong> {{ $tuSurat->nomor_surat }}</p>
            <p><strong>Tanggal:</strong> {{ $tuSurat->tanggal_surat?->format('d M Y') }}</p>
            <p><strong>Tujuan:</strong> {{ $tuSurat->tujuan }}</p>
            <p><strong>Perihal:</strong> {{ $tuSurat->perihal }}</p>
        </div>
        <img src="{{ $qrUrl }}" alt="QR Verifikasi Surat" style="width:140px;height:140px;">
    </div>

    <div class="title">
        <h1>{{ $tuSurat->template?->judul ?? 'SURAT RESMI' }}</h1>
        <p>KEPEGAWAIAN TATA USAHA</p>
    </div>

    <div class="content">{{ $tuSurat->isi_surat }}</div>

    <div class="footer">
        <div class="verify">
            <p><strong>Verifikasi Dokumen:</strong></p>
            <p>{{ $verifyUrl }}</p>
            <p>Scan QR untuk memastikan keaslian dokumen final.</p>
        </div>
        <div style="text-align:right;font-size:13px;">
            <p style="margin:0 0 70px;">Disetujui oleh,</p>
            <p style="margin:0;font-weight:700;">{{ $tuSurat->approver?->name ?? '-' }}</p>
        </div>
    </div>
</div>
</body>
</html>
