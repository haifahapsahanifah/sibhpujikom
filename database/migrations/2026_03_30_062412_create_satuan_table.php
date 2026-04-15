<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi
     */
    public function up(): void
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('Nama satuan');
            $table->string('code', 20)->unique()->comment('Kode satuan');
            $table->text('description')->nullable()->comment('Deskripsi satuan');
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi
     */
    public function down(): void
    {
        Schema::dropIfExists('satuan');
    }
};