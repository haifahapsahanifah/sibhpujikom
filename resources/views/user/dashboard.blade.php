{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('header')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div class="mb-4 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-900">Dashboard Pengguna</h2>
            <p class="text-gray-600">Selamat datang, {{ Auth::user()->nama }}</p>
        </div>
        <div class="flex items-center space-x-4">
            <span class="px-3 py-1 bg-gradient-to-r from-blue-100 to-blue-50 text-blue-800 text-sm font-medium rounded-full border border-blue-200">
                <i class="fas fa-user mr-1"></i>Pengguna
            </span>
            <span class="text-gray-500">{{ now()->format('d F Y') }}</span>
        </div>
    </div>
@endsection

@section('content')
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl p-8 mb-8 shadow-lg">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-4">Selamat Datang di SIPBHP</h3>
                <p class="text-blue-100 mb-6 max-w-xl">
                    Sistem Informasi Pengajuan Barang Habis Pakai membantu Anda dalam mengelola permintaan barang 
                    dengan mudah dan efisien.
                </p>
                <a href="{{ route('user.permintaan.create') }}" 
                   class="inline-flex items-center space-x-2 bg-white text-blue-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition shadow-md">
                    <i class="fas fa-plus"></i>
                    <span>Buat Permintaan Barang</span>
                </a>
            </div>
            <div class="mt-6 md:mt-0">
                <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-box-open text-4xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Permintaan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPermintaan ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center border border-blue-100">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Disetujui</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $disetujui ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-lg flex items-center justify-center border border-emerald-100">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Menunggu</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $menunggu ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-lg flex items-center justify-center border border-amber-100">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Permintaan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Permintaan Terbaru</h3>
            <a href="{{ route('user.permintaan.riwayat') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Tanggal</th>
                        <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Barang</th>
                        <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Jumlah</th>
                        <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPermintaan ?? [] as $permintaan)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($permintaan->created_at)->format('d M Y') }}</td>
                        <td class="py-3 px-4">
                            @foreach($permintaan->details as $detail)
                                <div class="text-sm">{{ $detail->nama_barang }}</div>
                            @endforeach
                        </td>
                        <td class="py-3 px-4">
                            @foreach($permintaan->details as $detail)
                                <div class="text-sm">{{ $detail->pengajuan_jumlah }} {{ $detail->satuan }}</div>
                            @endforeach
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusClass = [
                                    'menunggu_admin' => 'bg-amber-100 text-amber-800',
                                    'menunggu_user' => 'bg-purple-100 text-purple-800',
                                    'disetujui' => 'bg-emerald-100 text-emerald-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'selesai' => 'bg-gray-100 text-gray-800',
                                ];
                                $statusText = [
                                    'menunggu_admin' => 'Menunggu Admin',
                                    'menunggu_user' => 'Menunggu Konfirmasi',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'selesai' => 'Selesai',
                                ];
                            @endphp
                            <span class="px-3 py-1 {{ $statusClass[$permintaan->status] ?? 'bg-gray-100 text-gray-800' }} text-xs font-medium rounded-full">
                                {{ $statusText[$permintaan->status] ?? ucfirst($permintaan->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                            <p>Belum ada permintaan barang</p>
                            <a href="{{ route('user.permintaan.create') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                                Buat permintaan pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-900 mb-4">Panduan Cepat</h4>
            <ul class="space-y-4">
                <li class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mt-1 shrink-0">
                        <i class="fas fa-clipboard-list text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">1. Ajukan Permintaan</p>
                        <p class="text-sm text-gray-600">Klik "Buat Permintaan", pilih barang yang dibutuhkan beserta jumlahnya (pastikan stok tersedia), lalu kirim pengajuan.</p>
                    </div>
                </li>
                <li class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mt-1 shrink-0">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">2. Tunggu Evaluasi Admin</p>
                        <p class="text-sm text-gray-600">Admin akan meninjau permintaan Anda. Jumlah barang yang disetujui bisa sama atau disesuaikan dengan stok riil.</p>
                    </div>
                </li>
                <li class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mt-1 shrink-0">
                        <i class="fas fa-tasks text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">3. Lakukan Konfirmasi</p>
                        <p class="text-sm text-gray-600">Jika sudah dievaluasi admin (berstatus <i>Menunggu Konfirmasi</i>), buka menu Riwayat dan klik tombol <b>Konfirmasi</b> agar barang bisa disiapkan.</p>
                    </div>
                </li>
                <li class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mt-1 shrink-0">
                        <i class="fas fa-box-open text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">4. Serah Terima & Selesai</p>
                        <p class="text-sm text-gray-600">Temui admin untuk mengambil fisik barang. Admin akan mencetak struk pengambilan, dan status permintaan otomatis menjadi <b>Selesai</b>. Anda dapat meninjau struk tersebut kapan saja di menu Riwayat.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endsection