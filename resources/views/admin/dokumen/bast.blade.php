{{-- resources/views/admin/dokumen/bast.blade.php --}}
@extends('layouts.admin')

@section('title', 'Berita Acara Serah Terima')
@section('page-title', 'Berita Acara Serah Terima (BAST)')
@section('page-subtitle', 'Kelola dokumen BAST')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Daftar BAST</h3>
                <p class="text-sm text-gray-500 mt-1">Total 32 dokumen BAST</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Buat BAST Baru
            </button>
        </div>
    </div>

    <!-- Grid BAST -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card BAST -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                        <i class="fas fa-file-contract text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">BAST/001/XII/24</h4>
                        <p class="text-xs text-gray-500">Tanggal: 12 Des 2024</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Aktif</span>
            </div>
            <div class="mt-4 space-y-2">
                <p class="text-sm"><span class="text-gray-500">Penerima:</span> Budi Santoso</p>
                <p class="text-sm"><span class="text-gray-500">Divisi:</span> Keuangan</p>
                <p class="text-sm"><span class="text-gray-500">Barang:</span> Kertas A4 (5 rim)</p>
            </div>
            <div class="mt-4 flex space-x-2">
                <button class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-eye mr-2"></i>Lihat
                </button>
                <button class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm text-blue-600 hover:bg-blue-50">
                    <i class="fas fa-print mr-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>
@endsection