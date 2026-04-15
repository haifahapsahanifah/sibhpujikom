# Panduan N-6 dan N-7 (Persiapan UKK SIBHP)

Dokumen ini adalah *"cheat sheet"* atau panduan praktis yang bisa Anda pelajari dan hapalkan untuk menghadapi ujian keterampilan (N-6 dan N-7) saat sesi hari Rabu dan Kamis mendatang. 

Banyak siswa terhambat pada dua poin ini karena berbeda langsung dari tata cara *coding*, mari kita kupas tuntas dengan bahasa termudah!

---

## ☁️ Kompetensi N-6: Melakukan Pemesanan Domain & Web Hosting

### Konsep Dasar
- **Domain:** Nama alamat website Anda (contoh: `sibhpku.com` atau `smk-uji.my.id`).
- **Web Hosting / cPanel:** Ibarat komputer (*server*) yang hidup 24 jam nonstop untuk menyimpan file project (`sibhpujikom/`) Anda agar bisa diakses online via internet (tidak lagi pakai awalan *localhost*).

### Langkah-Langkah Lengkap Hosting SIBHP (via cPanel):

1. **Siapkan (Export) Database Lokal Anda:**
   - Nyalakan **XAMPP** (Apache & MySQL).
   - Buka browser, ke `http://localhost/phpmyadmin`.
   - Pilih database SIBHP Anda, lalu klik menu **Export**. Kosongkan pengaturan, langsung klik Export/Go. Anda akan mendapat file `database_anda.sql`.

2. **Siapkan Project Laravel Anda (di *Local*):**
   - Buka folder `C:\xampp\htdocs\sibhpujikom`.
   - **PENTING:** Hapus file instalasi berat seperti folder `node_modules` (jika ada, sifatnya *development* saja) untuk mengurangi ukuran file.
   - Blok semua file di dalam folder `sibhpujikom` (termasuk folder `app, database, public, resources`), lalu `Klik Kanan -> Send to -> Compressed (zipped) folder`. Beri nama `sibhp-deploy.zip`.

3. **Operasi di cPanel Server Ujian:**
   - Biasanya Penguji akan memberikan link CPanel beserta Username & Password Anda. (Contoh login ke: `hpanel.hostinger.com` atau `namadomainanda.com/cpanel`).
   - Cari menu **MySQL Databases**. Buat pelan-pelan: (1) Nama Database Baru, (2) User Database Baru, & Password. Hubungkan (*Add User to Database*) dengan izin *"All Privileges"*.
   - Cari menu **phpMyAdmin** di pannel hosting, klik nama database baru Anda tersebut, lalu klik menu **Import** -> Pilih file `database_anda.sql` yang didownload di Langkah 1 tadi.
   
4. **Mengunggah File Project Anda:**
   - Cari dan buka menu **File Manager** (Manajer File) di cPanel.
   - Buka folder `public_html`.
   - Klik **Upload** di menu atas, dan unggah file `sibhp-deploy.zip` Anda tadi.
   - Setelah selesai, ekstrak (*Extract*) file zip tersebut langsung ke dalam direktori `public_html`.

5. **Konfigurasi Folder Public Laravel:**
   - (*Karakteristik spesifik Laravel*): Agar web tampil tanpa perlu ribet membuka `website.com/public`, pindahkan **semua isi** di dalam folder `public/` (index.php, CSS, logo, dsb) langsung ke dalam akar `public_html/`. 
   - Edit file `index.php` yang dipindahkan tadi:
      - Ubah `require __DIR__.'/../vendor/autoload.php';` menjadi `require __DIR__.'/vendor/autoload.php';`
      - Ubah `$app = require_once __DIR__.'/../bootstrap/app.php';` menjadi `$app = require_once __DIR__.'/bootstrap/app.php';`

6. **Konfigurasi `.env`:**
   - Buka (Edit) file `.env` di cPanel.
   - Ubah empat baris ini menyesuaikan dengan nama database dan akun di Langkah 3:
     ```env
     DB_HOST=127.0.0.1
     DB_DATABASE=nama_database_yang_dibuat_di_cpanel_tadi
     DB_USERNAME=user_database_yang_dibuat_tadi
     DB_PASSWORD=password_yang_dibuat_tadi
     ```

Web Anda kini sudah Online! Kuncinya ada di *konfigurasi koneksi MySQL* dan pengalihan file `public` Laravelnya.

---

## 🐙 Kompetensi N-7: Menyimpan Source Code di GitHub

GitHub adalah tempat para *programmer* berkolaborasi dan mem-*backup* setiap perubahan kode sumber secara historis tanpa takut datanya "ter-replace" atau rusak. Keterampilan ini disebut dengan Version Control System (VCS/Git).

### Prasyarat:
Pastikan Anda sudah mengunduh dan menginstal **Git for Windows** di komputer sebelum ujian. 

### Langkah-langkah Tutorial:

**Langkah 1: Membuat Rumah di GitHub**
- Buka `https://github.com`, lalu Login/Register.
- Klik tombol Hijau di pojok kanan atas **"New"** atau ketuk ikon **"+" -> New repository**.
- Beri **Repository name** (misal: `sibhp-ujikom-2026`).
- Pilih **Public / Private**.
- Perhatian: *Jangan* centang kotak *"Add a README file"*. Biarkan kosong melompong. Lalu klik tombol **Create Repository**.

**Langkah 2: Menyiapkan Terminal Komputer**
- Buka kembali komputer Anda di folder `c:\xampp\htdocs\sibhpujikom`
- Klik Kanan pada tanah kosong (di dalam folder tsb), lalu pilih **"Open Git Bash Here"** (atau lewat terminal VS Code Anda).

Jika ini pertama kali menggunakan Git di PC itu, ketik "identitas KTP" Git Anda (lakukan sekali seumur hidup):
```bash
git config --global user.name "Nama Lengkap Anda"
git config --global user.email "email.anda@gmail.com"
```

**Langkah 3: Perintah Inti Git (Harap Dihafal Logika Ini!)**
Ketikkan ke lima baris sakti (kombo Git) berturut-turut di layar hitam tersebut:

1. Modalkan folder Anda menjadi format Versioning Git:
   ```bash
   git init
   ```
2. Titipkan SEMUA status perubahan file saat ini (titik berati 'semua'):
   ```bash
   git add .
   ```
3. "Bungkus" file tadi dengan pesan memori (*commit*). Semua file tersimpan historis di PC Anda!
   ```bash
   git commit -m "Commit pertama, menyelesaikan aplikasi SIBHP Ujikom"
   ```
4. Hubungkan PC Anda ke server/rumah GitHub online yang tadi kita buat. *(Copy Paste Link URL Github dari Repositori Anda yang dibuat di Langkah 1)*:
   ```bash
   git remote add origin https://github.com/USERNAME-ANDA/sibhp-ujikom-2026.git
   ```
   *(Harap disesuaikan URL-nya mengkopi langkah yang ada di GitHub)*
   
5. Luncurkan Kodenya ke Udara (Cloud):
   ```bash
   git push -u origin master
   ```
   *(Catatan: Kadang cabang utama dinamai 'main'. Bila menggunakan main, jalankan: `git branch -M main` lalu `git push -u origin main`).*

Jika nanti sistem meminta konfirmasi Login/Sign-In setelah Anda mengetik *`git push`*, ikuti saja instruksinya untuk Login ke Browser.

---

### Misi Sukses!
Dengan menyelesaikan langkah Hosting dan GitHub di atas, Anda membuktikan bahwa aplikasi Anda tidak hanya "karya lokal" di depan laptop sendiri—tapi siap merambah pengguna massal via Online dan siap di-audit secara sistematis! Semangat menghadapi rabu/kamis! 🚀
