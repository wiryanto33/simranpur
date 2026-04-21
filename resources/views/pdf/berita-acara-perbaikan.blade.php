<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Perbaikan — LP-{{ str_pad($perbaikan->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1a1a1a; }
        .header-wrapper { border-bottom: 3px solid #1B3A2D; padding-bottom: 12px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .header-text h1 { font-size: 14px; font-weight: bold; text-transform: uppercase; color: #1B3A2D; }
        .header-text h2 { font-size: 10px; margin-top: 2px; color: #555; }
        .header-right { text-align: right; font-size: 10px; color: #555; }
        .header-right strong { font-size: 13px; color: #1B3A2D; }
        .doc-title { text-align: center; margin: 16px 0; }
        .doc-title h3 { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1B3A2D; text-decoration: underline; }
        .doc-title p { font-size: 10px; color: #666; margin-top: 4px; }
        .section { margin-bottom: 16px; }
        .section-title { background-color: #1B3A2D; color: white; padding: 5px 10px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 1px; }
        .info-table td { padding: 5px 10px; font-size: 10px; border: 0.5px solid #ddd; vertical-align: top; }
        .info-table td:first-child { width: 35%; font-weight: bold; background-color: #f5f5f5; color: #333; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 1px; }
        .items-table th { background-color: #2D5A45; color: white; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; border: 0.5px solid #2D5A45; }
        .items-table td { padding: 5px 8px; font-size: 10px; border: 0.5px solid #ddd; }
        .items-table tr:nth-child(even) td { background-color: #f9f9f9; }
        .description-box { border: 0.5px solid #ddd; padding: 10px; font-size: 10px; background-color: #f9f9f9; line-height: 1.6; margin-top: 4px; }
        .signature-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .signature-table td { padding: 8px; text-align: center; width: 33.33%; }
        .signature-line { border-top: 1px solid #1a1a1a; margin-top: 60px; padding-top: 6px; }
        .signature-role { font-size: 10px; font-weight: bold; color: #1B3A2D; text-transform: uppercase; }
        .signature-name { font-size: 11px; font-weight: bold; margin-top: 2px; }
        .signature-rank { font-size: 9px; color: #666; }
        .footer { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 8px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header-wrapper">
        <div style="display: flex; align-items: center; justify-content: flex-start; gap: 15px;">
            <img src="{{ public_path('logo/marinir.png') }}" style="width: 50px; height: auto;" />
            <div class="header-text">
                <h1>Batalyon Kendaraan Tempur — Korps Marinir TNI AL</h1>
                <h2>Sistem Informasi Manajemen Ranpur (SIMRANPUR)</h2>
            </div>
        </div>
        <div class="header-right">
            <p>Nomor Dokumen</p>
            <p><strong>LP-{{ str_pad($perbaikan->id, 4, '0', STR_PAD_LEFT) }}</strong></p>
            <p>Dicetak: {{ now()->format('d M Y H:i') }} WIB</p>
        </div>
    </div>

    <div class="doc-title">
        <h3>Berita Acara Perbaikan Kendaraan Tempur</h3>
        <p>Berdasarkan Laporan Kerusakan No. LK-{{ str_pad($lk->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="section">
        <div class="section-title">A. DATA KENDARAAN</div>
        <table class="info-table">
            <tr><td>Nama Kendaraan</td><td>{{ $kendaraan->nama ?? '-' }}</td></tr>
            <tr><td>Nomor Registrasi</td><td>{{ $kendaraan->no_register ?? '-' }}</td></tr>
            <tr><td>Jenis / Model</td><td>{{ $kendaraan->jenis ?? '-' }}</td></tr>
            <tr><td>Status Saat Ini</td><td>{{ $kendaraan->status ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">B. RINCIAN KERUSAKAN</div>
        <table class="info-table">
            <tr><td>No. Laporan Kerusakan</td><td>LK-{{ str_pad($lk->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
            <tr><td>Tanggal Dilaporkan</td><td>{{ $lk->tanggal?->format('d M Y') ?? '-' }}</td></tr>
            <tr><td>Dilaporkan Oleh</td><td>{{ $lk->pelapor->name ?? '-' }}</td></tr>
            <tr><td>Tingkat Prioritas</td><td>{{ $lk->tingkat_prioritas }}</td></tr>
            <tr><td>Deskripsi Kerusakan</td><td>{{ $lk->deskripsi }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">C. PELAKSANAAN PERBAIKAN</div>
        <table class="info-table">
            <tr><td>Mekanik Pelaksana</td><td>{{ $perbaikan->mekanik->name ?? '-' }}</td></tr>
            <tr><td>Tanggal Mulai</td><td>{{ $perbaikan->tanggal_mulai?->format('d M Y') ?? '-' }}</td></tr>
            <tr><td>Tanggal Selesai</td><td>{{ $perbaikan->tanggal_selesai?->format('d M Y') ?? '-' }}</td></tr>
            <tr><td>Status Perbaikan</td><td>{{ $perbaikan->status }}</td></tr>
            @if($perbaikan->approvedBy)
            <tr><td>Disetujui Oleh</td><td>{{ $perbaikan->approvedBy->name }}</td></tr>
            @endif
        </table>
        <p style="padding: 8px 0 4px; font-size: 10px; font-weight: bold; color: #1B3A2D;">Deskripsi Pekerjaan:</p>
        <div class="description-box">{{ $perbaikan->deskripsi }}</div>
    </div>

    <div class="section">
        <div class="section-title">D. DAFTAR SUKU CADANG YANG DIGUNAKAN</div>
        @if($transaksis && $transaksis->count() > 0)
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:8%;text-align:center">No.</th>
                        <th style="width:18%">Kode</th>
                        <th style="width:44%">Nama Suku Cadang</th>
                        <th style="width:15%;text-align:center">Jumlah</th>
                        <th style="width:15%">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $i => $t)
                        <tr>
                            <td style="text-align:center">{{ $i + 1 }}</td>
                            <td>{{ $t->sukuCadang->kode ?? '-' }}</td>
                            <td>{{ $t->sukuCadang->nama ?? '-' }}</td>
                            <td style="text-align:center; font-weight:bold">{{ $t->jumlah }}</td>
                            <td>{{ $t->sukuCadang->satuan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="padding: 8px 10px; font-size: 10px; font-style: italic; color: #666;">— Tidak ada suku cadang yang digunakan —</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">E. PENGESAHAN BERITA ACARA</div>
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-role">Mekanik Pelaksana</div>
                    <div class="signature-line">
                        <div class="signature-name">{{ $perbaikan->mekanik->name ?? '(.............................)' }}</div>
                        <div class="signature-rank">{{ optional($perbaikan->mekanik->detail)->pangkat ?? 'Pangkat / NRP' }}</div>
                    </div>
                </td>
                <td>
                    <div class="signature-role">Kepala Mekanik</div>
                    <div class="signature-line">
                        <div class="signature-name">{{ $perbaikan->approvedBy->name ?? '(.............................)' }}</div>
                        <div class="signature-rank">{{ optional($perbaikan->approvedBy?->detail)->pangkat ?? 'Pangkat / NRP' }}</div>
                    </div>
                </td>
                <td>
                    <div class="signature-role">Komandan Batalyon</div>
                    <div class="signature-line">
                        <div class="signature-name">(.............................)</div>
                        <div class="signature-rank">Pangkat / NRP</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dokumen dicetak otomatis oleh <strong>SIMRANPUR</strong> — Sistem Informasi Manajemen Ranpur Korps Marinir TNI AL
    </div>
</body>
</html>
