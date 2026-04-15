<?php
// database/migrations/2026_03_30_200551_create_permintaan_barang_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermintaanBarangTable extends Migration
{
    public function up()
    {
        // Tabel permintaan_barang
        Schema::create('permintaan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('divisi');
            $table->date('tanggal_dibutuhkan');
            $table->enum('prioritas', ['biasa', 'segera', 'sangat_segera'])->default('biasa');
            $table->enum('status', ['menunggu_admin', 'menunggu_user', 'disetujui', 'ditolak', 'selesai'])->default('menunggu_admin');
            $table->text('catatan')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamp('disetujui_admin_at')->nullable();
            $table->foreignId('disetujui_admin_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('disetujui_user_at')->nullable();
            $table->timestamp('ditolak_at')->nullable();
            $table->text('alasan_ditolak')->nullable();
            $table->timestamps();
        });

        // Tabel detail_permintaan_barang
        Schema::create('detail_permintaan_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_barang_id')->constrained('permintaan_barang')->onDelete('cascade');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->integer('pengajuan_jumlah');
            $table->string('satuan');
            $table->text('keperluan');
            $table->integer('disetujui_jumlah')->nullable();
            $table->string('disetujui_satuan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'disesuaikan'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        // Tabel pengeluaran_barang
        Schema::create('pengeluaran_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_barang_id')->constrained('permintaan_barang')->onDelete('cascade');
            $table->foreignId('detail_permintaan_id')->constrained('detail_permintaan_barang')->onDelete('cascade');
            $table->foreignId('barang_id')->nullable()->constrained('barang')->onDelete('set null');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->string('penerima');
            $table->string('divisi');
            $table->date('tanggal_keluar');
            $table->text('keperluan');
            $table->string('nomor_surat');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_barang');
        Schema::dropIfExists('detail_permintaan_barang');
        Schema::dropIfExists('permintaan_barang');
    }
}