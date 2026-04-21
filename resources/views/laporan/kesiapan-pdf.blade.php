<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kesiapan Kendaraan</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 80px; }
        .title { font-weight: bold; font-size: 16px; text-transform: uppercase; }
        .subtitle { font-size: 14px; margin-top: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 10px; }
        .footer { margin-top: 50px; width: 100%; }
        .ttd-table { width: 100%; }
        .ttd-box { text-align: center; width: 40%; }
        .badge { padding: 2px 5px; border-radius: 3px; color: #fff; font-weight: bold; font-size: 10px; }
        .status-siap { color: #10B981; }
        .status-rusak { color: #EF4444; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo_tnial.png') }}" class="logo" onerror="this.style.display='none'">
        <div class="title">KORPS MARINIR TNI AL</div>
        <div class="subtitle">{{ auth()->user()->kompi->nama ?? 'SATUAN PELAKSANA' }}</div>
        <hr style="border: 1px solid #000; margin-top: 10px;">
        <h2 style="text-decoration: underline; margin-top: 20px;">LAPORAN KESIAPAN KENDARAAN TEMPUR</h2>
        <p>Periode: {{ now()->format('d F Y') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="30">NO</th>
                <th>NOMOR RANPUR</th>
                <th>NAMA KENDARAAN</th>
                <th>JENIS</th>
                <th>SATUAN</th>
                <th>STATUS KESIAPAN</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $ken)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td style="font-weight: bold;">{{ $ken->nomor_ranpur }}</td>
                <td>{{ $ken->nama }}</td>
                <td>{{ $ken->jenis }}</td>
                <td>{{ $ken->kompi->nama ?? '-' }}</td>
                <td style="text-align: center; font-weight: bold;" class="{{ $ken->status == 'Siap Tempur' ? 'status-siap' : ($ken->status == 'Tidak Layak' ? 'status-rusak' : '') }}">
                    {{ strtoupper($ken->status) }}
                </td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table class="ttd-table">
            <tr>
                <td width="60%"></td>
                <td class="ttd-box">
                    <p>Jakarta, {{ now()->format('d F Y') }}</p>
                    <p style="margin-bottom: 60px;">KOMANDAN SATUAN,</p>
                    <p style="text-decoration: underline; font-weight: bold;">( ............................................ )</p>
                    <p>PANGKAT / NRP</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
