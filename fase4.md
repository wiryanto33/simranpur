Lanjutkan proyek SIMRANPUR. Buat modul LAPORAN KERUSAKAN dan LAPORAN PERBAIKAN
sekaligus karena kedua modul ini saling terhubung erat.

=== BAGIAN A: LAPORAN KERUSAKAN ===

Model: LaporanKerusakan
Livewire: LaporanKerusakanIndex, LaporanKerusakanForm, DetailKerusakan

1. MODEL & RELASI
   - belongsTo Kendaraan, belongsTo User (operator)
   - hasOne LaporanPerbaikan
   - Tingkat prioritas: Kritis (merah), Sedang (kuning), Ringan (hijau)
   - Status alur: Dilaporkan → Diverifikasi → Sedang Diperbaiki → Selesai

2. FORM LAPORAN KERUSAKAN (untuk role Mekanik & KepMek)
   - Pilih kendaraan
   - Deskripsi kerusakan (textarea)
   - Upload multiple foto (maks 5 foto, maks 2MB each)
   - Tingkat prioritas
   - Otomatis update status kendaraan menjadi "Perbaikan" saat laporan dibuat

3. ALUR VERIFIKASI
   - Setelah operator submit, status = Dilaporkan
   - KepMek mendapat notifikasi
   - KepMek bisa Verifikasi atau Tolak laporan
   - Jika diverifikasi, muncul tombol "Buat Laporan Perbaikan"

4. DAFTAR LAPORAN
   - Filter: status, prioritas, kendaraan, periode, pelapor
   - Badge warna untuk prioritas dan status
   - Tampilkan thumbnail foto pertama

=== BAGIAN B: LAPORAN PERBAIKAN ===

Model: LaporanPerbaikan
Livewire: LaporanPerbaikanForm, DetailPerbaikan

1. MODEL & RELASI
   - belongsTo LaporanKerusakan
   - belongsTo User (mekanik pengerjaan)
   - belongsToMany SukuCadang (melalui tabel detail_perbaikan_suku_cadang)
   - belongsTo User (approved_by)

2. FORM LAPORAN PERBAIKAN
   - Otomatis terisi dari data kerusakan terkait
   - Tanggal mulai dan selesai pengerjaan
   - Deskripsi pekerjaan yang dilakukan
   - Tabel suku cadang yang digunakan (dynamic: pilih suku cadang + jumlah)
     jika di setujui oleh bagian logistik maka
     → Otomatis kurangi stok suku cadang saat disimpan
   - Upload foto hasil perbaikan

3. APPROVAL PERBAIKAN
   - Mekanik submit laporan → status = Menunggu Approval
   - KepMek mendapat notifikasi
   - KepMek bisa Setujui atau Kembalikan untuk revisi
   - Jika disetujui: status kendaraan otomatis kembali ke Siap Tempur

4. BERITA ACARA PERBAIKAN (PDF)
   - Tombol "Cetak Berita Acara" di halaman detail perbaikan
   - Template PDF profesional berisi:
     * Header dengan logo Korps Marinir
     * Data kendaraan
     * Detail kerusakan dan perbaikan
     * Daftar suku cadang yang digunakan
     * Kolom tanda tangan Mekanik, KepMek, Komandan
   - Generate menggunakan DomPDF

Tampilkan semua kode lengkap untuk kedua modul.