<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Jadwal Pemeliharaan</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 10px;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h3 {
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .content-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .content-table td {
            padding: 5px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
        .separator {
            width: 10px;
        }
        .section-title {
            background-color: #f2f2f2;
            padding: 5px;
            font-weight: bold;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .checklist-table th, .checklist-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .checklist-table th {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 50px;
        }
        .signature-table {
            width: 100%;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
        }
        .signature-box {
            height: 80px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo/marinir.png') }}" style="width: 60px; height: auto; margin-bottom: 5px;" />
        <h2>KORPS MARINIR TNI AL</h2>
        <p>PASUKAN MARINIR I - BATALYON KENDARAAN PENDARAT AMFIBI 1 MARINIR</p>
    </div>

    <div class="title">
        <h3>SURAT PERINTAH PEMELIHARAAN</h3>
        <p>Nomor: SPP/{{ date('m') }}/{{ date('Y') }}/{{ $jadwal->id }}</p>
    </div>

    <div class="section-title">I. INFORMASI KENDARAAN & JADWAL</div>
    <table class="content-table">
        <tr>
            <td class="label">Nama Kendaraan</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->kendaraan->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Ranpur</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->kendaraan->nomor_ranpur ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Pemeliharaan</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->jenis_pemeliharaan }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pelaksanaan</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->tanggal->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Estimasi Pengerjaan</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->estimasi_hari }} Hari</td>
        </tr>
        <tr>
            <td class="label">Status Saat Ini</td>
            <td class="separator">:</td>
            <td>{{ $jadwal->status }}</td>
        </tr>
    </table>

    <div class="section-title">II. TIM MEKANIK YANG DITUGASKAN</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Nama Mekanik</th>
                <th>Pangkat / NRP</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal->mekanik as $index => $m)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $m->name }}</td>
                <td>{{ $m->detail->pangkat ?? '-' }} / {{ $m->detail->nrp ?? '-' }}</td>
                <td>Mekanik</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">III. DAFTAR CHECKLIST PEKERJAAN</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Uraian Tugas / Item Pemeriksaan</th>
                <th style="width: 80px; text-align: center;">Status</th>
                <th style="width: 150px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal->checklist ?? [] as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item['task'] }}</td>
                <td style="text-align: center;">{{ ($item['is_done'] ?? false) ? '[ V ]' : '[   ]' }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($jadwal->keterangan)
    <div class="section-title">IV. CATATAN TAMBAHAN</div>
    <div style="padding: 10px; border: 1px solid #ddd; min-height: 50px;">
        {{ $jadwal->keterangan }}
    </div>
    @endif

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Kepala Mekanik
                    <div class="signature-box"></div>
                    <strong>................................................</strong><br>
                    Pangkat/NRP
                </td>
                <td>
                    Ditetapkan di: Jakarta<br>
                    Tanggal: {{ date('d F Y') }}
                    <div class="signature-box"></div>
                    <strong>{{ auth()->user()->name }}</strong><br>
                    {{ auth()->user()->detailUser->pangkat ?? 'Admin' }}
                </td>
            </tr>
        </table>
    </div>

    <div style="position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8px; color: #aaa;">
        Dicetak otomatis oleh Sistem SIMRANPUR pada {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
