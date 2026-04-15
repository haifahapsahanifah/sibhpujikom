# ERD – SIBHP (Sistem Informasi Barang Habis Pakai)

*(Dokumentasi Resmi Basis Data Berdasarkan Struktur Aktual)*

**Diagram Relasi Entitas Fisik (Physical Entity-Relationship Diagram):**
*Diagram visual dari skema ini telah dicetak dalam bentuk PNG dengan nama `erd_model_tabel_final.png` di dalam direktori `dokumen_perancangan/`.*

---

## Deskripsi Entitas

### 1. `users`
Menyimpan data pengguna sistem (Admin & Pengguna/Staff).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Primary key auto-increment |
| `nama` | VARCHAR | Nama lengkap pengguna |
| `email` | VARCHAR UK | Email unik untuk login |
| `username` | VARCHAR UK | Username unik |
| `password` | VARCHAR | Password terenkripsi (bcrypt) |
| `bidang` | VARCHAR | Bidang/unit kerja pengguna |
| `nip` | VARCHAR UK | Nomor Induk Pegawai (16 digit) |
| `role` | ENUM | `admin` atau `pengguna` |

---

### 2. `satuan`
Master data satuan ukuran barang (pcs, rim, botol, dll).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Primary key |
| `name` | VARCHAR UK | Nama satuan (unik) |
| `code` | VARCHAR UK | Kode singkat satuan |
| `description` | TEXT | Deskripsi opsional |

---

### 3. `kategori`
Master data kategori pengelompokan barang.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Primary key |
| `name` | VARCHAR UK | Nama kategori (unik) |
| `code` | VARCHAR UK | Kode singkat kategori |
| `description` | TEXT | Deskripsi opsional |

---

### 4. `barang`
Master data barang habis pakai yang dikelola.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Primary key |
| `kode_barang` | VARCHAR UK | Kode unik barang |
| `nama_barang` | VARCHAR | Nama barang |
| `kategori_id` | FK → kategori | Kategori barang |
| `satuan_id` | FK → satuan | Satuan default barang |
| `harga_satuan` | DECIMAL(15,2) | Harga per satuan |
| `description` | TEXT | Deskripsi barang |

---

### 5. `barang_masuk`
Mencatat setiap transaksi penerimaan/pengadaan barang.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Primary key |
| `tanggal_masuk` | DATE | Tanggal barang diterima |
| `nomor_dokumen` | VARCHAR | Nomor dokumen pengadaan |
| `nama_supplier` | VARCHAR | Nama penyuplai barang |
| `barang_id` | FK → barang (nullable) | Referensi ke master barang |
| `kode_barang` | VARCHAR | Kode barang (snapshot) |
| `nama_barang` | VARCHAR | Nama barang (snapshot) |
| `nusp` | VARCHAR | Nomor Urut Satuan Pekerjaan |
| `jumlah` | INT | Jumlah barang masuk |
| `satuan_id` | FK → satuan (nullable) | Referensi satuan |
| `satuan_nama` | VARCHAR | Nama satuan (snapshot) |
| `harga_satuan` | DECIMAL | Harga per satuan |
| `nilai_total` | DECIMAL | Total nilai (jumlah × harga) |
| `keterangan` | TEXT | Catatan tambahan |
| `created_by` | FK → users (nullable) | Admin yang menginput |

---

### 6. `permintaan_barang`
Header dari setiap surat permintaan barang yang diajukan pengguna.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Primary key |
| `nomor_surat` | VARCHAR UK | Nomor surat permintaan unik |
| `user_id` | FK → users | Pengguna yang mengajukan |
| `divisi` | VARCHAR | Divisi/unit pengaju |
| `tanggal_dibutuhkan` | DATE | Tanggal barang dibutuhkan |
| `prioritas` | ENUM | `biasa`, `segera`, `sangat_segera` |
| `status` | ENUM | Status alur persetujuan |
| `catatan` | TEXT | Catatan permintaan |
| `lampiran` | VARCHAR | Path file lampiran |
| `disetujui_admin_by` | FK → users (nullable) | Admin yang menyetujui |
| `alasan_ditolak` | TEXT | Alasan jika ditolak |

**Alur Status:**
```
menunggu_admin → menunggu_user → disetujui → selesai
                              ↘ ditolak
```

---

### 7. `detail_permintaan_barang`
Item-item barang yang diminta dalam satu surat permintaan.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Primary key |
| `permintaan_barang_id` | FK → permintaan_barang | Induk permintaan |
| `kode_barang` | VARCHAR | Kode barang yang diminta |
| `nama_barang` | VARCHAR | Nama barang yang diminta |
| `spesifikasi` | TEXT | Spesifikasi detail barang |
| `pengajuan_jumlah` | INT | Jumlah yang diajukan |
| `satuan` | VARCHAR | Satuan barang |
| `keperluan` | TEXT | Keperluan/alasan permintaan |
| `disetujui_jumlah` | INT (nullable) | Jumlah disetujui admin |
| `disetujui_satuan` | VARCHAR (nullable) | Satuan setelah disetujui |
| `status` | ENUM | `menunggu`, `disetujui`, `disesuaikan` |
| `catatan_admin` | TEXT | Catatan dari admin |

---

### 8. `pengeluaran_barang`
Mencatat setiap transaksi pengeluaran barang dari gudang (terhubung ke permintaan yang disetujui).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Primary key |
| `permintaan_barang_id` | FK → permintaan_barang | Permintaan asal |
| `detail_permintaan_id` | FK → detail_permintaan_barang | Detail item asal |
| `barang_id` | FK → barang (nullable) | Referensi master barang |
| `kode_barang` | VARCHAR | Kode barang (snapshot) |
| `nama_barang` | VARCHAR | Nama barang (snapshot) |
| `jumlah` | INT | Jumlah barang dikeluarkan |
| `satuan` | VARCHAR | Satuan barang |
| `penerima` | VARCHAR | Nama penerima barang |
| `divisi` | VARCHAR | Divisi penerima |
| `tanggal_keluar` | DATE | Tanggal barang dikeluarkan |
| `keperluan` | TEXT | Keperluan pengeluaran |
| `nomor_surat` | VARCHAR | Nomor surat permintaan |
| `created_by` | FK → users | Admin yang memproses |

---

## Ringkasan Relasi

| Dari | Ke | Jenis | Keterangan |
|---|---|---|---|
| `barang` | `kategori` | Many-to-One | Setiap barang memiliki 1 kategori |
| `barang` | `satuan` | Many-to-One | Setiap barang memiliki 1 satuan default |
| `barang_masuk` | `barang` | Many-to-One (nullable) | Referensi ke master barang |
| `barang_masuk` | `satuan` | Many-to-One (nullable) | Satuan barang masuk |
| `barang_masuk` | `users` | Many-to-One (nullable) | Penginput data |
| `permintaan_barang` | `users` | Many-to-One | Pengaju permintaan |
| `detail_permintaan_barang` | `permintaan_barang` | Many-to-One | Detail item per permintaan |
| `pengeluaran_barang` | `permintaan_barang` | Many-to-One | Pengeluaran dari permintaan |
| `pengeluaran_barang` | `detail_permintaan_barang` | Many-to-One | Dari item detail |
| `pengeluaran_barang` | `barang` | Many-to-One (nullable) | Referensi master barang |
| `pengeluaran_barang` | `users` | Many-to-One | Admin pemroses |
