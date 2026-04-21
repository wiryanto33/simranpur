Lanjutkan proyek SIMRANPUR. Buat DASHBOARD UTAMA dan modul LAPORAN & REKAP.

=== BAGIAN A: DASHBOARD ===

Livewire: DashboardIndex
Tampilkan widget berbeda berdasarkan role user yang login.

1. WIDGET UNTUK SEMUA ROLE
   - Total kendaraan per status (Siap Tempur / Standby / Perbaikan / Tidak Layak)
     tampilkan sebagai card dengan angka besar dan warna status
   - Persentase kesiapan armada (progress bar)
   - Jadwal pemeliharaan hari ini

2. WIDGET TAMBAHAN UNTUK KOMANDAN & ADMIN
   - Grafik kesiapan ranpur 30 hari terakhir (line chart menggunakan Chart.js CDN)
   - Rekap laporan kerusakan bulan ini: total, selesai, pending
   - Top 5 kendaraan dengan kerusakan terbanyak (bar chart)
   - Tabel jadwal pemeliharaan 7 hari ke depan

3. WIDGET TAMBAHAN UNTUK KEPALA MEKANIK
   - Laporan kerusakan menunggu verifikasi (harus ditampilkan paling atas)
   - Laporan perbaikan menunggu approval
   - Mekanik dengan beban kerja saat ini

4. WIDGET UNTUK LOGISTIK
   - Daftar suku cadang dengan stok menipis/habis
   - Grafik penggunaan suku cadang bulan ini

5. WIDGET TAMBAHAN UNTUK MEKANIK
   - Laporan kerusakan menunggu verifikasi (harus ditampilkan paling atas)
   - Laporan perbaikan menunggu approval
   - Mekanik dengan beban kerja saat ini

6. WIDGET TAMBAHAN UNTUK OPERATOR
   - Laporan kerusakan yang sedang dalam perbaikan
   - Laporan perbaikan yang sudah selesai
   - Laporan perbaikan yang ditolak

7. WIDGET TAMBAHAN UNTUK KEPALA LOGISTIK
   - Laporan kerusakan yang sedang dalam perbaikan
   - Laporan perbaikan yang sudah selesai
   - Laporan perbaikan yang ditolak
   - Laporan permintaan suku cadang
   - Laporan stok suku cadang
   - Laporan penggunaan suku cadang
   - Laporan audit log

=== BAGIAN B: MODUL LAPORAN & REKAP ===

Controller: LaporanController
Buat halaman laporan dengan filter dan export.

1. LAPORAN KESIAPAN KENDARAAN
   - Filter: satuan, periode (dari-sampai), jenis kendaraan
   - Tampilkan tabel rekap status kendaraan
   - Grafik pie kesiapan
   - Export PDF dengan header resmi dan tanda tangan komandan

2. LAPORAN PEMELIHARAAN BERKALA
   - Filter: periode, kendaraan, mekanik, status
   - Rekap jadwal yang sudah selesai vs terlambat
   - Export Excel

3. LAPORAN KERUSAKAN & PERBAIKAN
   - Filter: periode, kendaraan, tingkat prioritas
   - Rekap jumlah kerusakan per kendaraan
   - Rata-rata waktu penyelesaian perbaikan
   - Export PDF dan Excel

4. LAPORAN PENGGUNAAN SUKU CADANG
   - Filter: periode, jenis transaksi, suku cadang
   - Rekap total masuk dan keluar per periode
   - Suku cadang paling sering digunakan
   - Export Excel format kartu stok

5. AUDIT LOG
   - Tampilkan log aktivitas penting: siapa mengubah apa dan kapan
   - Gunakan package spatie/laravel-activitylog
   - Filter: user, modul, periode
   - Hanya Admin yang bisa akses

Untuk semua export PDF, gunakan template yang sama:
- Header: Logo Korps Marinir + Nama Satuan + Judul Laporan
- Footer: Halaman x dari y + tanggal cetak
- Kolom tanda tangan pejabat di bagian bawah

Tampilkan semua kode lengkap.