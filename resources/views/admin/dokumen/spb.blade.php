{{-- resources/views/admin/dokumen/spb.blade.php --}}
@extends('layouts.admin')

@section('title', 'Surat Permintaan Barang')
@section('page-title', 'Surat Permintaan Barang (SPB)')
@section('page-subtitle', 'Kelola dokumen SPB')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Daftar SPB</h3>
                <p class="text-sm text-gray-500 mt-1">Total 45 dokumen SPB</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Buat SPB Baru
            </button>
        </div>
    </div>

    <!-- Tabel SPB -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">No. SPB</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Pemohon</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Divisi</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Jumlah Item</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 px-6 text-sm font-medium">SPB/001/XII/24</td>
                        <td class="py-4 px-6 text-sm">12 Des 2024</td>
                        <td class="py-4 px-6 text-sm">Budi Santoso</td>
                        <td class="py-4 px-6 text-sm">Keuangan</td>
                        <td class="py-4 px-6 text-sm">3 Item</td>
                        <td class="py-4 px-6 text-sm">
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Disetujui</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex space-x-2">
                                <button class="p-2 hover:bg-blue-50 rounded-lg text-blue-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="p-2 hover:bg-green-50 rounded-lg text-green-600">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button class="p-2 hover:bg-red-50 rounded-lg text-red-600">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection