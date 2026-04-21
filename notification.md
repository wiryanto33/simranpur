SISTEM NOTIFIKASI IN-APP
   - Buat model Notification menggunakan Laravel Database Notifications
   - Bell icon di topbar dengan badge jumlah notifikasi belum dibaca
   - Livewire component NotificationDropdown yang auto-refresh tiap 30 detik
   - Halaman daftar semua notifikasi dengan mark as read
   - Daftar event yang trigger notifikasi:
     * Laporan kerusakan baru masuk (→ KepMek)
     * Laporan perbaikan menunggu approval (→ KepMek)
     * Jadwal pemeliharaan H-1 (→ Mekanik)
     * Jadwal melewati tanggal belum selesai (→ KepMek & Komandan)
     * Laporan permintaan suku cadang (→ Logistik)
     * Laporan permintaan suku cadang menunggu approval (→ Logistik)
     * Laporan permintaan suku cadang disetujui (→ kepMek)
     * Laporan permintaan suku cadang ditolak (→ kepMek)
     * Stok suku cadang menipis (→ Logistik & Admin)
     * Status kendaraan berubah (→ Komandan)
     * laporan semua notifikasi (→ Admin)