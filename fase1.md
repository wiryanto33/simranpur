Berdasarkan proyek SIMRANPUR Laravel 13 yang sudah dijelaskan sebelumnya,
lakukan setup awal proyek dengan langkah berikut:


1. STRUKTUR FOLDER
   Buat struktur folder proyek yang rapi:
   - app/Http/Controllers/ (pisahkan per modul)
   - app/Models/
   - app/Livewire/
   - app/Services/
   - resources/views/layouts/
   - resources/views/components/

2. KONFIGURASI RBAC (Spatie)
   - Publish config spatie/laravel-permission
   - Buat seeder untuk 5 role: Admin, Komandan, KepMek, Logistik, Operator
   - Buat seeder untuk permissions tiap modul
   - Buat user default untuk setiap role

3. MIGRASI DATABASE
   Buat semua file migration untuk tabel berikut:
   - kendaraan (id, nomor_ranpur, nama, jenis, tahun, foto, status, kompi_id, created_at, updated_at)
   - kompi (id, nama, kode)
   - detail_user (id, user_id, avatar, jabatan, pangkat, kompi_id, created_at, updated_at)
   - jadwal_pemeliharaan (id, kendaraan_id, tanggal, jenis_pemeliharaan, mekanik_id, status, keterangan)
   - laporan_kerusakan (id, kendaraan_id, pelapor_id, tanggal, deskripsi, foto, tingkat_prioritas, status)
   - laporan_perbaikan (id, laporan_kerusakan_id, mekanik_id, tanggal_mulai, tanggal_selesai, deskripsi, status, approved_by)
   - suku_cadang (id, kode, nama, satuan, stok, stok_minimum, lokasi)
   - transaksi_suku_cadang (id, suku_cadang_id, jenis (in/out), jumlah, keterangan, user_id, laporan_perbaikan_id, tanggal)
   - Jalankan: php artisan migrate --seed

4. LAYOUT UTAMA
   Buat layout Blade utama (resources/views/layouts/app.blade.php) dengan:
   - buatkan 1 halaman login (resources/views/auth/login.blade.php)
   - buatkan 1 halaman landing page (resources/views/landing.blade.php)
   - Sidebar navigasi yang responsif
   - Topbar dengan info user & tombol logout
   - Konten area utama
   - Desain militer/profesional menggunakan Tailwind CSS
   - Warna tema: hijau militer (#1a3c2e) dan abu-abu gelap

Tampilkan semua kode lengkap untuk setiap file yang perlu dibuat atau dimodifikasi.