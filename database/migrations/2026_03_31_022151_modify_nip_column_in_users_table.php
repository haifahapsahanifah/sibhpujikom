<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom nip menjadi varchar dengan panjang 18
            $table->string('nip', 18)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke varchar dengan panjang default
            $table->string('nip')->change();
        });
    }
};