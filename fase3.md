Lanjutkan proyek SIMRANPUR. Sekarang buat modul JADWAL PEMELIHARAAN.

Model: JadwalPemeliharaan
Livewire: JadwalIndex, JadwalForm, JadwalKalender
Controller: JadwalController

Buat fitur berikut:

1. MODEL & RELASI
   - JadwalPemeliharaan belongsTo Kendaraan, belongsTo User (mekanik)
   - Status jadwal: Terjadwal, Sedang Dikerjakan, Selesai, Ditunda, Dibatalkan
   - Jenis pemeliharaan: Harian, Mingguan, Bulanan, Triwulan, Tahunan, Insidentil

2. TAMPILAN KALENDER
   - Integrasi FullCalendar.js via CDN
   - Tampilkan jadwal per bulan
   - Warna berbeda untuk setiap jenis pemeliharaan
   - Klik event untuk melihat detail

3. TABEL DAFTAR JADWAL
   - Filter: periode, kendaraan, status, jenis, mekanik
   - Kolom: Tanggal, Ranpur, Jenis, Mekanik Assigned, Status, Aksi
   - Export ke PDF dan Excel

4. FORM JADWAL
   - Pilih kendaraan (searchable dropdown)
   - Tanggal dan estimasi durasi
   - Jenis pemeliharaan
   - Assign mekanik (pilih dari user dengan role Mekanik)
   - Checklist pekerjaan (dynamic form: bisa tambah/hapus item)
   - Catatan tambahan

5. FITUR REMINDER OTOMATIS
   - Buat Laravel Command: php artisan jadwal:reminder
   - Kirim notifikasi database ke mekanik yang assigned H-1 sebelum jadwal
   - Kirim notifikasi ke KepMek jika jadwal melewati tanggal dan belum selesai
   - Daftarkan di app/Console/Kernel.php agar jalan tiap hari jam 07.00

6. UPDATE STATUS JADWAL
   - Mekanik bisa update status dari Terjadwal → Sedang Dikerjakan → Selesai
   - Ketika Selesai, otomatis update status kendaraan menjadi Siap Tempur
   - KepMek bisa menunda atau membatalkan jadwal

Tampilkan semua kode lengkap.