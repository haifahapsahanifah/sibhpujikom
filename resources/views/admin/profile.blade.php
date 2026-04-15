{{-- resources/views/admin/profile.blade.php --}}
@extends('layouts.admin')

@section('title', 'Profil Admin')
@section('page-title', 'Profil Admin')
@section('page-subtitle', 'Kelola informasi profil Anda')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mx-auto flex items-center justify-center text-white text-4xl font-bold">
                    AD
                </div>
                <h3 class="text-xl font-bold text-gray-900 mt-4">Admin</h3>
                <p class="text-sm text-gray-500">Administrator</p>
                <div class="mt-4">
                    <span class="px-4 py-2 bg-green-100 text-green-800 text-sm rounded-full">Aktif</span>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="space-y-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <span class="ml-3 text-gray-600">admin@sibhp.com</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <span class="ml-3 text-gray-600">081234567890</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                        <span class="ml-3 text-gray-600">Jakarta, Indonesia</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-calendar w-5 text-gray-400"></i>
                        <span class="ml-3 text-gray-600">Bergabung: 01 Jan 2024</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <button class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm hover:bg-gray-50">
                    <i class="fas fa-camera mr-2"></i>Ganti Foto
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Profil</h3>
            <form>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="Admin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="198001012010011001">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="admin@sibhp.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                        <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="081234567890">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">Jl. Contoh No. 123, Jakarta</textarea>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Ubah Password</h3>
            <form>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                        <input type="password" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" placeholder="Masukkan password lama">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" placeholder="Masukkan password baru">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" placeholder="Konfirmasi password baru">
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection