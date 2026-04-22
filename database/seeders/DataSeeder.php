<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\DetailUser;
use App\Models\Kompi;
use App\Models\Kendaraan;
use App\Models\JadwalPemeliharaan;
use App\Models\LaporanKerusakan;
use App\Models\LaporanPerbaikan;
use App\Models\SukuCadang;
use Carbon\Carbon;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // 1. BUAT ROLE MEKANIK (jika belum ada)
        // =====================================================================
        $mekanikRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Mekanik']);
        $mekanikRole->syncPermissions([
            'view_laporan_kerusakan',
            'view_jadwal_pemeliharaan',
            'view_laporan_perbaikan',
            'create_laporan_perbaikan',
            'view_suku_cadang',
            'view_permintaan_suku_cadang',
            'create_permintaan_suku_cadang',
        ]);

        // =====================================================================
        // 2. AKUN USER MEKANIK (3 akun)
        // =====================================================================
        $mekanikData = [
            ['name' => 'Sersan Budi Santoso',    'email' => 'mekanik1@simranpur.com'],
            ['name' => 'Kopral Agus Prasetyo',   'email' => 'mekanik2@simranpur.com'],
            ['name' => 'Prada Rizky Firmansyah', 'email' => 'mekanik3@simranpur.com'],
        ];

        $mekaniks = [];
        foreach ($mekanikData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token'    => Str::random(10),
                ]
            );
            $user->assignRole('Mekanik');
            $mekaniks[] = $user;
        }

        // =====================================================================
        // 3. AKUN USER OPERATOR (4 akun)
        // =====================================================================
        $operatorData = [
            ['name' => 'Kopral Satu Hendra Wijaya', 'email' => 'operator1@simranpur.com'],
            ['name' => 'Kopral Darto Nugroho',       'email' => 'operator2@simranpur.com'],
            ['name' => 'Prada Eko Saputra',           'email' => 'operator3@simranpur.com'],
            ['name' => 'Prada Joko Susilo',           'email' => 'operator4@simranpur.com'],
        ];

        $operators = [];
        foreach ($operatorData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token'    => Str::random(10),
                ]
            );
            $user->assignRole('Operator');
            $operators[] = $user;
        }

        // Ambil user KepMek untuk pelapor laporan kerusakan
        $kepmek = User::whereHas('roles', fn($q) => $q->where('name', 'KepMek'))->first();
        $admin  = User::whereHas('roles', fn($q) => $q->where('name', 'Admin'))->first();

        // Operator ditugaskan ke kendaraan setelah kendaraan dibuat
        // (akan dilakukan setelah $kendaraans tersedia di bawah)

        // =====================================================================
        // 4. 3 KOMPI
        // =====================================================================
        $kompiData = [
            ['nama' => 'Kompi Alfa',  'kode' => 'A'],
            ['nama' => 'Kompi Bravo', 'kode' => 'B'],
            ['nama' => 'Kompi Charlie', 'kode' => 'C'],
        ];

        $kompis = [];
        foreach ($kompiData as $k) {
            $kompis[] = Kompi::firstOrCreate(['kode' => $k['kode']], ['nama' => $k['nama']]);
        }

        // =====================================================================
        // 5. 20 KENDARAAN DENGAN BERBAGAI STATUS
        // =====================================================================
        $kendaraanData = [
            // Kompi Alfa (7 kendaraan)
            ['nomor' => 'MBT-A-001', 'nama' => 'Tank Leopard 2A4',        'jenis' => 'Tank Amfibi',        'tahun' => 2015, 'status' => 'Siap Tempur', 'kompi' => 0],
            ['nomor' => 'MBT-A-002', 'nama' => 'Tank Leopard 2A4',        'jenis' => 'Tank Amfibi',        'tahun' => 2015, 'status' => 'Siap Tempur', 'kompi' => 0],
            ['nomor' => 'APC-A-001', 'nama' => 'Panser Anoa 6x6',         'jenis' => 'Tank Amfibi',        'tahun' => 2018, 'status' => 'Siap Tempur', 'kompi' => 0],
            ['nomor' => 'APC-A-002', 'nama' => 'Panser Anoa 6x6',         'jenis' => 'Tank Amfibi',        'tahun' => 2019, 'status' => 'Standby',     'kompi' => 0],
            ['nomor' => 'APC-A-003', 'nama' => 'Panser Anoa 4x4',         'jenis' => 'Tank Amfibi',        'tahun' => 2017, 'status' => 'Perbaikan',   'kompi' => 0],
            ['nomor' => 'KND-A-001', 'nama' => 'Kendaraan Taktis Maung',  'jenis' => 'Tank Amfibi',        'tahun' => 2021, 'status' => 'Siap Tempur', 'kompi' => 0],
            ['nomor' => 'KND-A-002', 'nama' => 'Kendaraan Taktis Maung',  'jenis' => 'Tank Amfibi',        'tahun' => 2020, 'status' => 'Tidak Layak', 'kompi' => 0],

            // Kompi Bravo (7 kendaraan)
            ['nomor' => 'MBT-B-001', 'nama' => 'Tank AMX-13',             'jenis' => 'Tank Amfibi',        'tahun' => 2010, 'status' => 'Siap Tempur', 'kompi' => 1],
            ['nomor' => 'MBT-B-002', 'nama' => 'Tank AMX-13',             'jenis' => 'Tank Amfibi',        'tahun' => 2010, 'status' => 'Perbaikan',   'kompi' => 1],
            ['nomor' => 'APC-B-001', 'nama' => 'Panser Anoa 6x6',         'jenis' => 'Tank Amfibi',        'tahun' => 2016, 'status' => 'Siap Tempur', 'kompi' => 1],
            ['nomor' => 'APC-B-002', 'nama' => 'Panser Stormer',          'jenis' => 'Tank Amfibi',        'tahun' => 2014, 'status' => 'Standby',     'kompi' => 1],
            ['nomor' => 'KND-B-001', 'nama' => 'Kendaraan Taktis Maung',  'jenis' => 'Tank Amfibi',        'tahun' => 2022, 'status' => 'Siap Tempur', 'kompi' => 1],
            ['nomor' => 'KND-B-002', 'nama' => 'Kendaraan Angkut Personel', 'jenis' => 'Tank Amfibi',       'tahun' => 2019, 'status' => 'Siap Tempur', 'kompi' => 1],
            ['nomor' => 'KND-B-003', 'nama' => 'Kendaraan Komando',       'jenis' => 'Tank Amfibi',        'tahun' => 2018, 'status' => 'Standby',     'kompi' => 1],

            // Kompi Charlie (6 kendaraan)
            ['nomor' => 'MBT-C-001', 'nama' => 'Tank Scorpion',           'jenis' => 'Tank Amfibi',        'tahun' => 2008, 'status' => 'Tidak Layak', 'kompi' => 2],
            ['nomor' => 'APC-C-001', 'nama' => 'Panser Anoa 6x6',         'jenis' => 'Tank Amfibi',        'tahun' => 2020, 'status' => 'Siap Tempur', 'kompi' => 2],
            ['nomor' => 'APC-C-002', 'nama' => 'Panser Anoa 4x4',         'jenis' => 'Tank Amfibi',        'tahun' => 2021, 'status' => 'Siap Tempur', 'kompi' => 2],
            ['nomor' => 'KND-C-001', 'nama' => 'Kendaraan Taktis Maung',  'jenis' => 'Tank Amfibi',        'tahun' => 2023, 'status' => 'Siap Tempur', 'kompi' => 2],
            ['nomor' => 'KND-C-002', 'nama' => 'Kendaraan Taktis Maung',  'jenis' => 'Tank Amfibi',        'tahun' => 2022, 'status' => 'Perbaikan',   'kompi' => 2],
            ['nomor' => 'KND-C-003', 'nama' => 'Kendaraan Angkut Amunisi', 'jenis' => 'Tank Amfibi',        'tahun' => 2017, 'status' => 'Siap Tempur', 'kompi' => 2],
        ];

        $kendaraans = [];
        foreach ($kendaraanData as $k) {
            $kendaraans[] = Kendaraan::firstOrCreate(
                ['nomor_ranpur' => $k['nomor']],
                [
                    'nama'     => $k['nama'],
                    'jenis'    => $k['jenis'],
                    'tahun'    => $k['tahun'],
                    'status'   => $k['status'],
                    'kompi_id' => $kompis[$k['kompi']]->id,
                ]
            );
        }

        // =====================================================================
        // ASSIGN OPERATOR KE KENDARAAN (1 operator = 1 ranpur)
        // =====================================================================
        // Operator 1 → KND-A-001, Operator 2 → KND-B-001
        // Operator 3 → KND-C-001, Operator 4 → APC-A-001
        $operatorKendaraanMap = [
            0 => 'KND-A-001',
            1 => 'KND-B-001',
            2 => 'KND-C-001',
            3 => 'APC-A-001',
        ];

        foreach ($operatorKendaraanMap as $opIdx => $nomorRanpur) {
            if (!isset($operators[$opIdx])) continue;
            $ranpur = Kendaraan::where('nomor_ranpur', $nomorRanpur)->first();
            if (!$ranpur) continue;

            DetailUser::updateOrCreate(
                ['user_id' => $operators[$opIdx]->id],
                [
                    'jabatan'      => 'Operator Ranpur',
                    'pangkat'      => match($opIdx) {
                        0 => 'Kopral Satu',
                        1 => 'Kopral',
                        default => 'Prada',
                    },
                    'kendaraan_id' => $ranpur->id,
                    'kompi_id'     => $ranpur->kompi_id,
                ]
            );
        }

        // =====================================================================
        // 6. JADWAL PEMELIHARAAN 3 BULAN KE DEPAN
        // =====================================================================
        $jenisPemeliharaan = [
            'Pemeliharaan Mingguan',
            'Cek Sistem Hidrolik',
            'Penggantian Oli Mesin',
            'Pemeriksaan Sistem Kemudi',
            'Kalibrasi Sistem Senjata',
            'Pemeriksaan Ban & Suspensi',
            'Servis Sistem Pendingin',
            'Pemeliharaan Bulanan',
            'Pengecekan Aki & Kelistrikan',
            'Pemeriksaan Transmisi',
        ];

        $statusJadwal = ['Terjadwal', 'Terjadwal', 'Terjadwal', 'Selesai', 'Selesai', 'Dalam Proses'];
        $checklistItems = [
            ['Cek level oli', 'Cek tekanan ban', 'Uji mesin', 'Cek rem'],
            ['Cek sistem hidrolik', 'Cek kebocoran', 'Ganti filter'],
            ['Kuras oli lama', 'Isi oli baru', 'Cek kebersihan filter'],
            ['Cek setir', 'Uji manuver', 'Cek komponen kemudi'],
        ];

        $now = Carbon::now();
        $jadwals = [];

        // Buat jadwal untuk 3 bulan ke depan
        foreach ($kendaraans as $idx => $kendaraan) {
            // Skip kendaraan dengan status Tidak Layak dari jadwal
            if ($kendaraan->status === 'Tidak Layak') continue;

            // 2-3 jadwal per kendaraan dalam 3 bulan ke depan
            $jumlahJadwal = ($idx % 2 === 0) ? 3 : 2;

            for ($i = 0; $i < $jumlahJadwal; $i++) {
                $tanggal = $now->copy()->addDays(rand(1, 90));
                $mekanikTerpilih = [$mekaniks[array_rand($mekaniks)]->id];
                // Kadang 2 mekanik
                if (rand(0, 1)) {
                    $mekanik2Id = $mekaniks[array_rand($mekaniks)]->id;
                    if ($mekanik2Id !== $mekanikTerpilih[0]) {
                        $mekanikTerpilih[] = $mekanik2Id;
                    }
                }

                $jadwal = JadwalPemeliharaan::create([
                    'kendaraan_id'      => $kendaraan->id,
                    'tanggal'           => $tanggal,
                    'jenis_pemeliharaan' => $jenisPemeliharaan[array_rand($jenisPemeliharaan)],
                    'status'            => 'Terjadwal',
                    'keterangan'        => 'Jadwal pemeliharaan rutin kendaraan ' . $kendaraan->nomor_ranpur,
                    'checklist'         => $checklistItems[array_rand($checklistItems)],
                    'estimasi_hari'     => rand(1, 3),
                ]);

                // Attach mekanik via pivot
                $jadwal->mekanik()->sync($mekanikTerpilih);
                $jadwals[] = $jadwal;
            }
        }

        // Jadwal sudah selesai (untuk data historis)
        $kendaraanSiap = array_filter($kendaraans, fn($k) => $k->status === 'Siap Tempur');
        $kendaraanSiap = array_values($kendaraanSiap);

        for ($i = 0; $i < 6; $i++) {
            $kendaraan = $kendaraanSiap[$i % count($kendaraanSiap)];
            $tanggalLalu = $now->copy()->subDays(rand(10, 60));
            $jadwal = JadwalPemeliharaan::create([
                'kendaraan_id'      => $kendaraan->id,
                'tanggal'           => $tanggalLalu,
                'jenis_pemeliharaan' => $jenisPemeliharaan[array_rand($jenisPemeliharaan)],
                'status'            => 'Selesai',
                'keterangan'        => 'Pemeliharaan telah selesai dilaksanakan dengan baik.',
                'checklist'         => $checklistItems[array_rand($checklistItems)],
                'estimasi_hari'     => rand(1, 2),
            ]);
            $jadwal->mekanik()->sync([$mekaniks[array_rand($mekaniks)]->id]);
        }

        // =====================================================================
        // 7. 15 LAPORAN KERUSAKAN DENGAN BERBAGAI STATUS
        // =====================================================================
        $deskripsiKerusakan = [
            'Mesin mengalami overheat setelah dioperasikan selama 2 jam. Suhu mesin melebihi batas normal.',
            'Sistem transmisi tidak bekerja dengan baik. Terjadi selip saat pergantian gigi.',
            'Kebocoran pada sistem hidrolik roda kiri belakang. Tekanan hidrolik menurun drastis.',
            'Aki kendaraan tidak dapat menyimpan daya dengan baik. Starter sulit.',
            'Sistem kemudi mengalami gangguan. Setir terasa berat saat berbelok.',
            'Ban depan kiri mengalami kerusakan pada dinding samping. Ban kempes saat operasi.',
            'Sistem pendingin mesin bocor. Cairan coolant habis lebih cepat dari normal.',
            'Track/rantai kendaraan mengalami keausan berlebih. Kendur dan berisiko putus.',
            'Sistem senjata tidak dapat dioperasikan. Mekanisme penguncian bermasalah.',
            'Lampu indikator dashboard tidak berfungsi sebagian. Beberapa sensor tidak terbaca.',
            'Getaran abnormal terdeteksi pada bagian bawah kendaraan saat kecepatan tinggi.',
            'Filter udara tersumbat menyebabkan konsumsi bahan bakar meningkat drastis.',
            'Sistem rem mengalami penurunan performa. Jarak pengereman lebih panjang dari normal.',
            'Kebocoran oli pada blok mesin bagian bawah. Volume oli terus berkurang.',
            'Sistem komunikasi internal kendaraan tidak berfungsi. Interkom tidak merespons.',
        ];

        $statusKerusakan = [
            'Menunggu', 'Menunggu', 'Menunggu',
            'Diproses', 'Diproses', 'Diproses',
            'Selesai', 'Selesai', 'Selesai', 'Selesai',
            'Ditolak',
        ];

        $tingkatPrioritas = ['Rendah', 'Sedang', 'Sedang', 'Tinggi'];

        $pelaporUserId = $kepmek ? $kepmek->id : ($operators[0]->id);
        $laporanKerusakans = [];

        foreach ($deskripsiKerusakan as $idx => $deskripsi) {
            // Pilih kendaraan secara variatif (termasuk yang bermasalah)
            $kendaraanTarget = $kendaraans[$idx % count($kendaraans)];
            $status = $statusKerusakan[$idx % count($statusKerusakan)];
            $prioritas = $tingkatPrioritas[$idx % count($tingkatPrioritas)];

            // Tanggal bervariasi: beberapa bulan lalu hingga kemarin
            $tanggal = $now->copy()->subDays(rand(1, 90));

            $laporan = LaporanKerusakan::create([
                'kendaraan_id'    => $kendaraanTarget->id,
                'pelapor_id'      => $operators[$idx % count($operators)]->id,
                'tanggal'         => $tanggal,
                'deskripsi'       => $deskripsi,
                'tingkat_prioritas' => $prioritas,
                'status'          => $status,
            ]);

            $laporanKerusakans[] = $laporan;
        }

        // =====================================================================
        // 8. 10 LAPORAN PERBAIKAN
        // =====================================================================
        // Ambil laporan kerusakan yang statusnya "Diproses" atau "Selesai"
        $laporanUntukPerbaikan = array_filter($laporanKerusakans, fn($l) => in_array($l->status, ['Diproses', 'Selesai']));
        $laporanUntukPerbaikan = array_values($laporanUntukPerbaikan);

        $deskripsiPerbaikan = [
            'Dilakukan penggantian thermostat dan flush sistem pendingin. Mesin kembali normal.',
            'Penggantian oli transmisi dan penyetelan mekanisme gigi. Transmisi bekerja lancar.',
            'Penggantian seal hidrolik yang bocor dan pengisian ulang cairan hidrolik.',
            'Penggantian aki baru dan perbaikan terminal aki yang korosi.',
            'Penyetelan sistem kemudi dan penggantian komponen rack steer yang aus.',
            'Pemasangan ban cadangan dan pemesanan ban baru untuk penggantian.',
            'Penambalan kebocoran radiator dan pengisian coolant baru.',
            'Penggantian segmen track yang aus dan pengencangan track secara keseluruhan.',
            'Perbaikan mekanisme penguncian sistem senjata oleh teknisi khusus.',
            'Penggantian modul sensor dashboard dan kalibrasi ulang sistem elektronik.',
        ];

        for ($i = 0; $i < min(10, count($laporanUntukPerbaikan)); $i++) {
            $laporanKerusakan = $laporanUntukPerbaikan[$i];
            $mekanik = $mekaniks[$i % count($mekaniks)];
            $tanggalMulai = Carbon::parse($laporanKerusakan->tanggal)->addDays(rand(1, 5));
            $statusPerbaikan = ($laporanKerusakan->status === 'Selesai') ? 'Selesai' : 'Dalam Proses';
            $tanggalSelesai = ($statusPerbaikan === 'Selesai') ? $tanggalMulai->copy()->addDays(rand(1, 7)) : null;

            LaporanPerbaikan::firstOrCreate(
                ['laporan_kerusakan_id' => $laporanKerusakan->id],
                [
                    'mekanik_id'        => $mekanik->id,
                    'tanggal_mulai'     => $tanggalMulai,
                    'tanggal_selesai'   => $tanggalSelesai,
                    'deskripsi'         => $deskripsiPerbaikan[$i],
                    'status'            => $statusPerbaikan,
                    'approved_by'       => ($statusPerbaikan === 'Selesai' && $kepmek) ? $kepmek->id : null,
                ]
            );
        }

        // =====================================================================
        // 9. 30 JENIS SUKU CADANG DENGAN STOK BERVARIASI
        // =====================================================================
        $sukuCadangData = [
            // Filter & Oli
            ['kode' => 'SC-OLI-001', 'nama' => 'Oli Mesin Diesel 15W-40',        'satuan' => 'Liter',  'stok' => 120, 'min' => 30, 'lokasi' => 'Rak A-1'],
            ['kode' => 'SC-OLI-002', 'nama' => 'Oli Transmisi ATF',              'satuan' => 'Liter',  'stok' => 80,  'min' => 20, 'lokasi' => 'Rak A-2'],
            ['kode' => 'SC-OLI-003', 'nama' => 'Oli Hidrolik ISO 46',            'satuan' => 'Liter',  'stok' => 60,  'min' => 15, 'lokasi' => 'Rak A-3'],
            ['kode' => 'SC-FLT-001', 'nama' => 'Filter Oli Mesin',              'satuan' => 'Pcs',    'stok' => 45,  'min' => 10, 'lokasi' => 'Rak B-1'],
            ['kode' => 'SC-FLT-002', 'nama' => 'Filter Udara',                  'satuan' => 'Pcs',    'stok' => 30,  'min' => 8,  'lokasi' => 'Rak B-2'],
            ['kode' => 'SC-FLT-003', 'nama' => 'Filter Solar',                  'satuan' => 'Pcs',    'stok' => 8,   'min' => 10, 'lokasi' => 'Rak B-3'],  // stok di bawah minimum

            // Sistem Pendingin
            ['kode' => 'SC-PND-001', 'nama' => 'Coolant Radiator',              'satuan' => 'Liter',  'stok' => 50,  'min' => 20, 'lokasi' => 'Rak C-1'],
            ['kode' => 'SC-PND-002', 'nama' => 'Thermostat Mesin',              'satuan' => 'Pcs',    'stok' => 12,  'min' => 5,  'lokasi' => 'Rak C-2'],
            ['kode' => 'SC-PND-003', 'nama' => 'Selang Radiator',               'satuan' => 'Set',    'stok' => 6,   'min' => 3,  'lokasi' => 'Rak C-3'],
            ['kode' => 'SC-PND-004', 'nama' => 'Water Pump Mesin',              'satuan' => 'Pcs',    'stok' => 4,   'min' => 2,  'lokasi' => 'Rak C-4'],

            // Sistem Kelistrikan
            ['kode' => 'SC-ELK-001', 'nama' => 'Aki / Baterai 12V 100Ah',      'satuan' => 'Pcs',    'stok' => 15,  'min' => 5,  'lokasi' => 'Rak D-1'],
            ['kode' => 'SC-ELK-002', 'nama' => 'Alternator Kendaraan',          'satuan' => 'Pcs',    'stok' => 5,   'min' => 2,  'lokasi' => 'Rak D-2'],
            ['kode' => 'SC-ELK-003', 'nama' => 'Kabel Set Kelistrikan',         'satuan' => 'Set',    'stok' => 10,  'min' => 3,  'lokasi' => 'Rak D-3'],
            ['kode' => 'SC-ELK-004', 'nama' => 'Sekring / Fuse 30A',            'satuan' => 'Pcs',    'stok' => 100, 'min' => 30, 'lokasi' => 'Rak D-4'],
            ['kode' => 'SC-ELK-005', 'nama' => 'Starter Motor',                 'satuan' => 'Pcs',    'stok' => 4,   'min' => 2,  'lokasi' => 'Rak D-5'],

            // Sistem Rem & Kemudi
            ['kode' => 'SC-REM-001', 'nama' => 'Kampas Rem Set',                'satuan' => 'Set',    'stok' => 20,  'min' => 6,  'lokasi' => 'Rak E-1'],
            ['kode' => 'SC-REM-002', 'nama' => 'Caliper Rem',                   'satuan' => 'Pcs',    'stok' => 8,   'min' => 4,  'lokasi' => 'Rak E-2'],
            ['kode' => 'SC-REM-003', 'nama' => 'Minyak Rem DOT 4',              'satuan' => 'Liter',  'stok' => 25,  'min' => 8,  'lokasi' => 'Rak E-3'],
            ['kode' => 'SC-KMD-001', 'nama' => 'Rack Steer / End Rod',          'satuan' => 'Pcs',    'stok' => 6,   'min' => 2,  'lokasi' => 'Rak E-4'],
            ['kode' => 'SC-KMD-002', 'nama' => 'Tie Rod End',                   'satuan' => 'Pcs',    'stok' => 12,  'min' => 4,  'lokasi' => 'Rak E-5'],

            // Ban & Roda
            ['kode' => 'SC-BAN-001', 'nama' => 'Ban Luar 14.00-20 (All Terrain)', 'satuan' => 'Pcs',  'stok' => 18,  'min' => 8,  'lokasi' => 'Rak F-1'],
            ['kode' => 'SC-BAN-002', 'nama' => 'Ban Dalam 14.00-20',             'satuan' => 'Pcs',   'stok' => 20,  'min' => 8,  'lokasi' => 'Rak F-2'],
            ['kode' => 'SC-BAN-003', 'nama' => 'Velg Kendaraan Taktis',          'satuan' => 'Pcs',   'stok' => 5,   'min' => 4,  'lokasi' => 'Rak F-3'],  // hampir habis
            ['kode' => 'SC-TRK-001', 'nama' => 'Segmen Track / Rantai Tank',     'satuan' => 'Pcs',   'stok' => 0,   'min' => 20, 'lokasi' => 'Rak F-4'],  // HABIS

            // Mesin & Transmisi
            ['kode' => 'SC-MSN-001', 'nama' => 'Gasket Kepala Silinder',         'satuan' => 'Set',   'stok' => 8,   'min' => 3,  'lokasi' => 'Rak G-1'],
            ['kode' => 'SC-MSN-002', 'nama' => 'V-Belt / Tali Kipas',            'satuan' => 'Set',   'stok' => 22,  'min' => 6,  'lokasi' => 'Rak G-2'],
            ['kode' => 'SC-MSN-003', 'nama' => 'Busi / Injektor Diesel',         'satuan' => 'Set',   'stok' => 35,  'min' => 10, 'lokasi' => 'Rak G-3'],
            ['kode' => 'SC-HID-001', 'nama' => 'Seal Kit Hidrolik',              'satuan' => 'Set',   'stok' => 14,  'min' => 5,  'lokasi' => 'Rak H-1'],
            ['kode' => 'SC-HID-002', 'nama' => 'Silinder Hidrolik Kecil',        'satuan' => 'Pcs',   'stok' => 6,   'min' => 2,  'lokasi' => 'Rak H-2'],

            // Bahan Habis Pakai
            ['kode' => 'SC-BHP-001', 'nama' => 'Gemuk / Grease Lithium',         'satuan' => 'Kg',    'stok' => 40,  'min' => 15, 'lokasi' => 'Rak I-1'],
            ['kode' => 'SC-BHP-002', 'nama' => 'Cairan Penetrant (WD-40)',        'satuan' => 'Kaleng', 'stok' => 24, 'min' => 10, 'lokasi' => 'Rak I-2'],
        ];

        foreach ($sukuCadangData as $sc) {
            SukuCadang::firstOrCreate(
                ['kode' => $sc['kode']],
                [
                    'nama'         => $sc['nama'],
                    'satuan'       => $sc['satuan'],
                    'stok'         => $sc['stok'],
                    'stok_minimum' => $sc['min'],
                    'lokasi'       => $sc['lokasi'],
                ]
            );
        }

        $this->command->info('✅ DataSeeder berhasil dijalankan!');
        $this->command->info('   - 3 Kompi');
        $this->command->info('   - 20 Kendaraan');
        $this->command->info('   - ' . JadwalPemeliharaan::count() . ' Jadwal Pemeliharaan');
        $this->command->info('   - 15 Laporan Kerusakan');
        $this->command->info('   - 10 Laporan Perbaikan');
        $this->command->info('   - 30 Suku Cadang');
        $this->command->info('   - 3 Akun Mekanik (mekanik1-3@simranpur.com)');
        $this->command->info('   - 4 Akun Operator (operator1-4@simranpur.com)');
        $this->command->info('   - Penugasan operator ke ranpur:');
        $this->command->info('     operator1 → KND-A-001 | operator2 → KND-B-001');
        $this->command->info('     operator3 → KND-C-001 | operator4 → APC-A-001');
        $this->command->info('   - Semua password: password');
    }
}
