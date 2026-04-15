# BUKU PANDUAN PENGGUNA (MANUAL BOOK)
# SIBHP – SISTEM INFORMASI BARANG HABIS PAKAI

---

**Versi:** 1.5 (Edisi Final - April 2026)  
**Aplikasi:** SIBHP - Sistem Informasi Barang Habis Pakai  
**Platform:** Web-Based Application (Laravel Framework)  
**Instansi:** [ISI NAMA INSTANSI ANDA DI SINI]

---

## DAFTAR ISI

1. [KATA PENGANTAR](#kata-pengantar)
2. [PENDAHULUAN](#1-pendahuluan)
3. [AKSES SISTEM & ALUR LOGIN](#2-akses-sistem--alur-login)
4. [PANDUAN OPERASIONAL PEGAWAI (USER)](#3-panduan-operasional-pegawai-user)
   - 3.1 Dashboard Personal
   - 3.2 Alur Pengajuan Permintaan Barang
   - 3.3 Konfirmasi & Cetak Bukti
5. [PANDUAN OPERASIONAL ADMINISTRATOR](#4-panduan-operasional-administrator)
   - 4.1 Dashboard Analitik
   - 4.2 Manajemen Master Data
   - 4.3 Transaksi Barang Masuk
   - 4.4 Verifikasi & Approval Permintaan
   - 4.5 Inventarisasi & Laporan
6. [TIPS PENGGUNAAN & KEAMANAN](#5-tips-penggunaan--keamanan)

---

## KATA PENGANTAR

Puji syukur kita panjatkan ke hadirat Tuhan Yang Maha Esa atas selesainya pengembangan Sistem Informasi Barang Habis Pakai (SIBHP). SIBHP hadir sebagai solusi digital untuk mengoptimalkan manajemen inventaris kantor, memberikan kemudahan dalam proses permintaan barang, pemantauan stok secara real-time, hingga penyusunan laporan yang akurat dan transparan.

Buku panduan ini disusun sebagai kompas bagi seluruh pengguna agar dapat mengoperasikan sistem dengan standar operasional yang benar. Kami berharap aplikasi ini dapat meningkatkan efisiensi kerja dan akuntabilitas pengelolaan aset di lingkungan kerja kita.

---

## 1. PENDAHULUAN

**SIBHP** adalah aplikasi berbasis web yang dirancang untuk mengelola seluruh siklus hidup barang habis pakai kantor—mulai dari pengadaan stok (Barang Masuk), permintaan internal oleh staf, hingga pelaporan otomatis melalui Kartu Persediaan.

**Tujuan Utama:**
- Menghilangkan proses pencatatan manual yang rentan kesalahan.
- Memastikan ketersediaan stok melalui peringatan dini (*Low Stock Warning*).
- Transparansi alur distribusi barang dari gudang ke tiap bidang.

---

## 2. AKSES SISTEM & ALUR LOGIN

Halaman ini merupakan pintu gerbang utama keamanan data SIBHP.

### 2.1 Prosedur Login
1.  Buka browser Anda dan masukkan alamat URL aplikasi SIBHP.
2.  Masukkan **Username** dan **Password** yang telah didaftarkan.
3.  **Keamanan Lockout:** Jika Anda salah memasukkan password sebanyak **3 kali berturut-turut**, akun akan terkunci secara otomatis selama **5 menit** (300 detik) untuk mencegah upaya peretasan. Silakan tunggu atau hubungi Admin.

### 2.2 Alur Redirect Halaman
Setelah klik tombol **"Masuk"**, sistem akan membaca peran (*role*) Anda secara otomatis:
- **Pengguna/Pegawai:** Diarahkan ke `/user/dashboard` (Pusat permintaan pribadi).
- **Administrator:** Diarahkan ke `/admin/dashboard` (Pusat kendali seluruh sistem).

---

## 3. PANDUAN OPERASIONAL PEGAWAI (USER)

Ditujukan bagi staf yang akan mengajukan kebutuhan operasional barang.

### 3.1 Dashboard Personal
Halaman ini menampilkan ringkasan status permintaan Anda dalam bentuk widget interaktif (Menunggu Admin, Disetujui, Ditolak, atau Selesai).

### 3.2 Alur Pengajuan Permintaan Barang (Rinci)
1.  Klik menu **"Buat Permintaan Baru"** pada sidebar kiri.
2.  Isi **Tanggal Dibutuhkan** dan tentukan **Prioritas** (Biasa/Segera/Sangat Segera).
3.  **Pilih Barang:** Klik tombol **"+ Tambah Item"**. Anda bisa mencari barang berdasarkan nama atau kode. Masukkan jumlah yang dibutuhkan dan alasan keperluannya.
4.  **Multi-Item:** Anda dapat mengajukan banyak jenis barang sekaligus dalam satu surat permintaan.
5.  **Finalisasi:** Klik **"Kirim Permintaan"**. Status akan menjadi 🟡 **Menunggu Admin**.

### 3.3 Konfirmasi & Cetak Bukti
- **Cek Status:** Periksa menu **"Riwayat Permintaan"** secara berkala.
- **Konfirmasi Terima:** Jika Admin menyetujui, status akan menjadi 🔵 **Menunggu User**. Setelah barang Anda terima secara fisik, klik tombol **"Konfirmasi Terima"** di halaman detail permintaan.
- **Cetak Struk:** Setelah dikonfirmasi, Anda dapat mengklik tombol **"Cetak Struk"** sebagai bukti arsip pribadi atau dokumen pengambilan.

---

## 4. PANDUAN OPERASIONAL ADMINISTRATOR

Ditujukan bagi pengelola logistik dan inventaris.

### 4.1 Dashboard Analitik
Menampilkan statistik real-time:
- Grafik perbandingan Barang Masuk vs Keluar.
- Daftar **Barang Stok Rendah** (barang yang jumlahnya di bawah batas minimal).
- Notifikasi permintaan terbaru yang butuh persetujuan.

### 4.2 Manajemen Master Data (Setup Awal)
Sebelum sistem berjalan, Admin wajib mengisi:
- **Kategori:** (Contoh: ATK, Kebersihan, Sarana Prasarana).
- **Satuan:** (Contoh: Rim, Pcs, Box, Galon).
- **Data Barang:** Daftarkan barang lengkap dengan **Kode Barang**, Harga Satuan, dan **Stok Minimal**.

### 4.3 Transaksi Barang Masuk
Berfungsi untuk menambah stok saldo gudang.
- Menu: **Transaksi -> Barang Masuk**.
- Pastikan mengisi nomor dokumen (Faktur/SPK) dan nama supplier untuk keperluan audit.
- Harga yang diinput akan dikalikan otomatis dengan jumlah untuk mendapatkan **Nilai Total**.

### 4.4 Verifikasi & Approval Permintaan (Rinci)
Setiap permintaan pegawai masuk ke menu **"Permintaan Menunggu"**.
1.  Klik **"Detail"** pada permintaan pegawai.
2.  **Verifikasi Stok:** Cek apakah stok fisik mencukupi.
3.  **Keputusan Admin:**
    - Jika **Setujui:** Admin bisa menyesuaikan "Jumlah Disetujui" jika stok terbatas.
    - Jika **Tolak:** Admin **wajib** mengisi alasan penolakan yang akan dibaca oleh pegawai di dashboard mereka.

### 4.5 Inventarisasi & Laporan
- **Kartu Persediaan:** Fitur audit untuk melihat alur mutasi barang (Saldo Awal + Masuk - Keluar = Saldo Akhir). Dapat dicetak sebagai dokumen legalitas stok.
- **Dokumen Laporan:** Admin dapat menjerandah Dokumen **SPB** (Surat Permintaan Barang), **BAST** (Berita Acara Serah Terima), dan **SPPB** (Surat Perintah Pengeluaran Barang).
- **Manajemen Pengguna:** Admin berhak menambah, mengedit, atau menonaktifkan akun pegawai serta meriset password jika pegawai lupa.

---

## 5. TIPS PENGGUNAAN & KEAMANAN

1.  **Logout Berkala:** Selalu klik "Keluar" setiap kali selesai menggunakan aplikasi, terutama jika bekerja pada komputer bersama.
2.  **Cek Stok Minimal:** Admin disarankan mengecek Dashboard setiap pagi untuk melihat barang apa saja yang harus segera dilakukan pengadaan ulang.
3.  **Akurasi Data:** Pastikan jumlah "Konfirmasi Terima" oleh pegawai sesuai dengan jumlah fisik barang agar data Kartu Persediaan tetap akurat.

---

*Dokumen ini merupakan panduan resmi Sistem Informasi Barang Habis Pakai (SIBHP). Segala bentuk kendala teknis dapat menghubungi Administrator Sistem.*
