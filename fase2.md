Lanjutkan proyek SIMRANPUR. Sekarang buat modul MASTER DATA KENDARAAN.

Model: Kendaraan, Kompi
Controller: KendaraanController (resource)
Livewire: KendaraanIndex (tabel + search), KendaraanForm (create/edit)

Buat fitur berikut secara lengkap:

1. MODEL & RELASI
   - Model Kendaraan dengan relasi: belongsTo Kompi, hasMany JadwalPemeliharaan,
     hasMany LaporanKerusakan, hasOne status terbaru
   - Gunakan soft delete
   - Tambahkan accessor untuk label status berwarna

2. LIVEWIRE COMPONENT: KendaraanIndex
   - Tabel daftar kendaraan dengan kolom: No. Ranpur, Nama, Jenis, Kompi, 
     Status (badge warna), Aksi
   - Fitur search by nama/nomor, filter by status dan kompi
   - Pagination 15 item per halaman
   - Tombol Tambah, Edit, Detail, Hapus
   - Konfirmasi hapus dengan modal
   - Gate/Policy: hanya Admin dan KepMek yang bisa tambah/edit/hapus

3. LIVEWIRE COMPONENT: KendaraanForm
   - Form create dan edit dalam satu komponen
   - Field: nomor_ranpur, nama, jenis (dropdown), tahun, kompi_id, 
     status (dropdown: Siap Tempur/Standby/Perbaikan/Tidak Layak), 
     keterangan, foto (upload dengan preview)
   - Validasi lengkap dengan pesan error bahasa Indonesia
   - Upload foto ke storage/public/kendaraan
   - Flash message sukses/gagal

4. HALAMAN DETAIL KENDARAAN
   - Info lengkap kendaraan
   - Tab: Riwayat Pemeliharaan, Riwayat Kerusakan, Suku Cadang Terpakai
   - Tombol ubah status langsung dari halaman detail

5. ROUTES
   Tambahkan routes dengan middleware auth dan permission yang sesuai

Tampilkan semua kode lengkap setiap file. Gunakan bahasa Indonesia untuk 
label, pesan validasi, dan komentar kode.