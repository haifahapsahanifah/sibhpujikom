<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@sibhp.go.id',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'bidang' => 'Administrasi',
            'nip' => '123456789012345678',
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@instansi.go.id',
            'username' => 'budi',
            'password' => Hash::make('user123'),
            'bidang' => 'Keuangan',
            'nip' => '198765432109876543',
            'role' => 'pengguna',
        ]);
    }
}