Saya ingin membangun sistem informasi pemeliharaan kendaraan tempur (ranpur) 
untuk Korps Marinir TNI AL menggunakan Laravel 11. 

Proyek ini bernama "SIMRANPUR" (Sistem Informasi Pemeliharaan Kendaraan Tempur).

Tech stack yang digunakan:
- Laravel 13
- Breeze
- MySQL
- Livewire 4 + Alpine.js
- Tailwind CSS
- Blade templating engine
- spatie/laravel-permission (RBAC)
- barryvdh/laravel-dompdf (export PDF)
- maatwebsite/laravel-excel (export Excel)
- Laravel Scheduler + Notifications

Fitur utama:
1. Jadwal pemeliharaan kendaraan tempur
2. Status kesiapan kendaraan tempur
3. Laporan kerusakan
4. Laporan perbaikan
5. Manajemen suku cadang (input & output)
6. Role-Based Access Control (RBAC) dengan 5 role:
   - Admin (akses penuh)
   - Komandan (view semua laporan & dashboard)
   - Kepala Mekanik (approve perbaikan, kelola jadwal)
   - Logistik (kelola suku cadang)
   - Operator (input data kendaraan tempur input kerusakan & perbaikan)
7. Dashboard
8. Laporan
9. Notifikasi
10. User Management

Simpan konteks ini. Saya akan memberikan instruksi per modul secara bertahap.