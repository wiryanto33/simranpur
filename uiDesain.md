Lanjutkan proyek SIMRANPUR Laravel 11. Sekarang buat desain UI yang profesional 
dan berkesan militer untuk layout utama aplikasi, fokus pada sidebar navigasi.

=== SPESIFIKASI DESAIN ===

Tema visual:
- Warna primer   : #1B3A2D (hijau militer gelap)
- Warna sekunder : #2D5A45 (hijau militer medium)
- Warna aksen    : #C8A84B (emas/gold militer)
- Background app : #F0F2F0 (abu-abu sangat terang)
- Sidebar bg     : #1B3A2D
- Topbar bg      : #FFFFFF dengan border bawah tipis
- Font           : Inter (Google Fonts) untuk semua teks UI
- Border radius  : 8px untuk card, 6px untuk badge/button

=== FILE YANG HARUS DIBUAT ===
1. resources/views/auth/login.blade.php
   - buatkan 1 halaman login (resources/views/auth/login.blade.php)
2. resources/views/landing.blade.php
   - buatkan 1 halaman landing page (resources/views/landing.blade.php)
3. resources/views/layouts/app.blade.php
   Layout utama dengan struktur:
   - Sidebar kiri (lebar 260px, collapsible menjadi 64px icon-only)
   - Topbar atas (tinggi 64px, sticky)
   - Konten area kanan (scrollable)
   - Overlay backdrop saat sidebar terbuka di mobile

4. resources/views/layouts/partials/sidebar.blade.php
   Komponen sidebar dengan:
   
   HEADER SIDEBAR:
   - Logo Korps Marinir (placeholder img, path: /images/logo-marinir.png)
   - Nama aplikasi "SIMRANPUR" dengan font bold
   - Subtitle "Korps Marinir TNI AL" font kecil warna emas
   - Garis separator tipis warna emas setelah header
   
   PROFIL USER DI SIDEBAR:
   - Avatar lingkaran (foto profil atau inisial nama jika tidak ada foto)
   - Nama lengkap user
   - Badge role (warna berbeda per role: merah=Admin, emas=Komandan, 
     biru=KepMek, hijau=Mekanik, ungu=Logistik)
   - Semua ditampilkan di bawah header, sebelum menu navigasi
   
   MENU NAVIGASI:
   Kelompokkan menu dengan label grup (label grup warna emas tipis uppercase):
   
   Grup "UTAMA":
   - Dashboard          → icon: chart-bar
   
   Grup "OPERASIONAL":
   - Kendaraan Tempur   → icon: truck
   - Jadwal Pemeliharaan → icon: calendar
   - Status Kesiapan    → icon: shield-check
   
   Grup "LAPORAN":
   - Lap. Kerusakan     → icon: exclamation-triangle
   - Lap. Perbaikan     → icon: wrench-screwdriver
   
   Grup "LOGISTIK":
   - Suku Cadang        → icon: cube
   - Transaksi Stok     → icon: arrows-right-left
   
   Grup "REKAP":
   - Laporan & Rekap    → icon: document-chart-bar
   
   Grup "SISTEM" (hanya tampil untuk role Admin):
   - Manajemen User     → icon: users
   - Pengaturan         → icon: cog-6-tooth
   - Audit Log          → icon: clipboard-document-list
   - role & permission  → icon: shield-check
   
   STYLE SETIAP ITEM MENU:
   - Normal  : teks putih 85%, icon abu-abu muda, padding 10px 16px
   - Hover   : background #2D5A45, teks putih 100%, icon putih,
               border-left 3px solid transparan
   - Active  : background #2D5A45, teks putih, icon emas (#C8A84B),
               border-left 3px solid #C8A84B
   - Memiliki badge notifikasi bulat merah di kanan (jika ada notif pending)
     contoh: Lap. Kerusakan menampilkan badge angka jika ada laporan pending
   
   FOOTER SIDEBAR:
   - Tombol collapse/expand sidebar (icon panah kiri/kanan)
   - Tombol logout dengan icon dan teks "Keluar"
   - Warna tombol logout: merah gelap saat hover

5. resources/views/layouts/partials/topbar.blade.php
   Topbar dengan:
   
   SISI KIRI:
   - Tombol hamburger untuk toggle sidebar (mobile & collapsed mode)
   - Breadcrumb navigasi dinamis (Home > Modul > Halaman)
   
   SISI KANAN (dari kiri ke kanan):
   - Tanggal dan waktu saat ini (update realtime dengan Alpine.js)
   - Bell icon notifikasi dengan badge merah (jumlah notif belum dibaca)
     → Klik buka dropdown daftar notifikasi terbaru (maks 5)
     → Footer dropdown: link "Lihat Semua Notifikasi"
   - Divider vertikal
   - Avatar + nama user (klik buka dropdown profil)
     → Item dropdown: Profil Saya, Ganti Password, Keluar
   
6. resources/views/layouts/partials/page-header.blade.php
   Header halaman konten dengan:
   - Judul halaman (h1, bold)
   - Subtitle/deskripsi singkat halaman (opsional, warna abu)
   - Slot untuk tombol aksi (mis: tombol Tambah Data di sisi kanan)
   - Garis separator di bawah

=== JAVASCRIPT (Alpine.js) ===

Buat logika berikut menggunakan Alpine.js (x-data di body atau layout):

1. SIDEBAR TOGGLE
   - State: sidebarOpen (mobile: true/false), sidebarCollapsed (desktop: true/false)
   - Di mobile (<768px): sidebar overlay dari kiri, backdrop gelap saat terbuka
   - Di desktop: sidebar collapse menjadi 64px hanya tampil icon, 
     tooltip nama menu muncul saat hover icon
   - Simpan state collapsed di localStorage agar ingat preferensi user

2. ACTIVE MENU DETECTION
   - Otomatis tandai menu aktif berdasarkan URL saat ini
   - Gunakan @class Blade directive untuk kondisi aktif

3. REAL-TIME CLOCK
   - Tampilkan jam digital di topbar, update setiap detik
   - Format: Senin, 20 Jan 2025  |  14:30:55 WIB

4. NOTIFIKASI DROPDOWN
   - Toggle dropdown notifikasi
   - Tutup otomatis jika klik di luar area dropdown
   - Mark as read saat dropdown dibuka (AJAX call ke route notif.read)

=== RESPONSIVE BEHAVIOR ===

Desktop (≥1024px):
- Sidebar selalu tampil, bisa di-collapse jadi icon-only
- Konten area menyesuaikan lebar sidebar

Tablet (768px - 1023px):
- Sidebar default tersembunyi, muncul overlay saat tombol hamburger diklik
- Konten area full width

Mobile (<768px):
- Sama dengan tablet
- Topbar lebih compact
- Breadcrumb disembunyikan

=== BLADE COMPONENTS ===

Buat reusable Blade components berikut yang akan sering dipakai di seluruh halaman:

1. <x-card> : wrapper kartu dengan shadow dan border-radius
   Props: title, subtitle, action (slot untuk tombol di kanan judul)

2. <x-badge> : badge status berwarna
   Props: color (green/red/yellow/blue/gray), size (sm/md)
   Usage: <x-badge color="green">Siap Tempur</x-badge>

3. <x-stat-card> : card statistik untuk dashboard
   Props: title, value, icon, color, change (persentase naik/turun), trend (up/down)

4. <x-page-header> : header halaman konten
   Props: title, subtitle
   Slot: actions

5. <x-empty-state> : tampilan saat data kosong
   Props: title, description, icon
   Slot: action (tombol tambah data)

6. <x-table> : wrapper tabel responsif dengan style konsisten

=== CONTOH IMPLEMENTASI ===

Tunjukkan contoh halaman lengkap dashboard (resources/views/dashboard/index.blade.php) 
yang menggunakan semua komponen di atas, sehingga bisa menjadi referensi 
konsistensi desain untuk halaman-halaman lain.

=== OUTPUT YANG DIHARAPKAN ===

Tampilkan kode lengkap untuk semua file berikut:
1. resources/views/layouts/app.blade.php
2. resources/views/layouts/partials/sidebar.blade.php
3. resources/views/layouts/partials/topbar.blade.php
4. resources/views/layouts/partials/page-header.blade.php
5. resources/views/components/card.blade.php
6. resources/views/components/badge.blade.php
7. resources/views/components/stat-card.blade.php
8. resources/views/components/page-header.blade.php
9. resources/views/components/empty-state.blade.php
10. resources/views/components/table.blade.php
11. resources/views/dashboard/index.blade.php (contoh penggunaan)
12. resources/css/app.css (tambahan custom CSS jika diperlukan)
13. resources/js/app.js (inisialisasi Alpine.js dan helper functions)

Pastikan semua kode:
- Menggunakan Tailwind CSS utility classes (tidak ada custom CSS inline berlebihan)
- Kompatibel dengan Livewire 3 (tidak konflik dengan wire:navigate)
- Semua teks label dalam Bahasa Indonesia
- Menggunakan Heroicons via Blade UI Kit atau inline SVG
- Dark mode TIDAK diperlukan
- Sudah responsif untuk mobile, tablet, dan desktop