{{-- resources/views/admin/dokumen/sppb.blade.php --}}
@extends('layouts.admin')

@section('title', 'Surat Perintah Pembayaran Barang')
@section('page-title', 'Surat Perintah Pembayaran Barang (SPPB)')
@section('page-subtitle', 'Kelola dokumen SPPB')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Daftar SPPB</h3>
                <p class="text-sm text-gray-500 mt-1">Total 28 dokumen SPPB</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Buat SPPB Baru
            </button>
        </div>
    </div>

    <!-- Tabel SPPB -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">No. SPPB</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">No. SPB</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Vendor</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Nilai</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 px-6 text-sm font-medium">SPPB/001/XII/24</td>
                        <td class="py-4 px-6 text-sm">12 Des 2024</td>
                        <td class="py-4 px-6 text-sm">SPB/001/XII/24</td>
                        <td class="py-4 px-6 text-sm">PT. Maju Jaya</td>
                        <td class="py-4 px-6 text-sm">Rp 450.000</td>
                        <td class="py-4 px-6 text-sm">
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Dibayar</span>
                        </td>
                        <td class="py-4 px-6">
                            <button class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection