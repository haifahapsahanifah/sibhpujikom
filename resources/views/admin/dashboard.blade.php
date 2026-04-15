@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Selamat datang')

@section('content')
    <!-- Stats Barang -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalUsers ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        <span class="text-green-600">{{ $totalPengguna ?? 0 }}</span> pengguna aktif
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center border border-blue-100">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Barang</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalBarang ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ number_format($barangMasukBulanIni ?? 0, 0, ',', '.') }} masuk bulan ini
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-lg flex items-center justify-center border border-emerald-100">
                    <i class="fas fa-boxes text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Menunggu Persetujuan</p>
                    <p class="text-3xl font-bold {{ ($pendingRequests ?? 0) > 0 ? 'text-amber-600' : 'text-gray-900' }} mt-2">
                        {{ number_format($pendingRequests ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        perlu segera diproses
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 rounded-lg flex items-center justify-center border border-amber-100">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Stok Rendah</p>
                    <p class="text-3xl font-bold {{ ($lowStock ?? 0) > 0 ? 'text-red-600' : 'text-gray-900' }} mt-2">
                        {{ number_format($lowStock ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        perlu segera restock
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-50 rounded-lg flex items-center justify-center border border-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafik & Quick Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Permintaan Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Permintaan Terbaru</h3>
                <a href="{{ route('admin.permintaan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">No. SPB</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Pemohon</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Divisi</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Tanggal</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Status</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-medium text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests ?? [] as $request)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-3 px-4 font-medium text-sm">{{ $request->nomor_surat }}</td>
                            <td class="py-3 px-4 text-sm">{{ $request->user->nama ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm">{{ $request->divisi ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm">{{ $request->created_at->format('d M Y') }}</td>
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
                                        'menunggu_admin' => 'Menunggu',
                                        'menunggu_user' => 'Konfirmasi User',
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        'selesai' => 'Selesai',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $statusClass[$request->status] ?? 'bg-gray-100' }} text-xs font-medium rounded-full">
                                    {{ $statusText[$request->status] ?? $request->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.permintaan.show', $request->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                                <p>Belum ada permintaan barang</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Barang Stok Rendah -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Stok Rendah</h3>
                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                    {{ $lowStock ?? 0 }} item
                </span>
            </div>
            
            <div class="space-y-4">
                @forelse($lowStockItems ?? [] as $item)
                <div class="flex items-center justify-between p-3 {{ $item->stok <= ($item->stok_minimal / 2) ? 'bg-red-50 border-red-100' : 'bg-amber-50 border-amber-100' }} rounded-lg border">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 {{ $item->stok <= ($item->stok_minimal / 2) ? 'bg-red-100' : 'bg-amber-100' }} rounded-lg flex items-center justify-center">
                            <i class="fas fa-box {{ $item->stok <= ($item->stok_minimal / 2) ? 'text-red-600' : 'text-amber-600' }}"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $item->nama_barang }}</p>
                            <p class="text-xs {{ $item->stok <= ($item->stok_minimal / 2) ? 'text-red-600' : 'text-amber-600' }}">
                                Stok: {{ number_format($item->stok, 0, ',', '.') }} {{ $item->satuan->name ?? 'pcs' }}
                                @if($item->stok_minimal)
                                <span class="text-gray-400">(Min: {{ $item->stok_minimal }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.barang-masuk.create', ['barang_id' => $item->id]) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-check-circle text-3xl mb-2 text-green-500"></i>
                    <p class="text-sm">Semua barang dalam kondisi stok aman</p>
                </div>
                @endforelse
                
                @if(($lowStockItems ?? [])->count() > 0)
                <div class="pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.manajemen-barang') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                        Lihat semua barang <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Grafik Mutasi Barang -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Grafik Mutasi Barang (6 Bulan Terakhir)</h3>
            <div class="flex space-x-2">
                <button onclick="changeChartPeriod('6_bulan')" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">6 Bulan</button>
                <button onclick="changeChartPeriod('tahun_ini')" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">Tahun Ini</button>
            </div>
        </div>
        <canvas id="mutasiChart" style="height: 350px; width: 100%;"></canvas>
    </div>
    
    <!-- Quick Actions & System Status -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Aksi Cepat -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl p-6">
            <h4 class="text-lg font-bold mb-4">Aksi Cepat</h4>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.barang-masuk.index') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-white/20 rounded-lg hover:bg-white/30 transition">
                    <i class="fas fa-inbox text-2xl mb-2"></i>
                    <span class="text-sm font-medium">Barang Masuk</span>
                </a>
                
                <a href="{{ route('admin.barang-keluar.index') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-white/20 rounded-lg hover:bg-white/30 transition">
                    <i class="fas fa-box-open text-2xl mb-2"></i>
                    <span class="text-sm font-medium">Barang Keluar</span>
                </a>
                
                <a href="{{ route('admin.permintaan.menunggu') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-white/20 rounded-lg hover:bg-white/30 transition">
                    <i class="fas fa-clipboard-check text-2xl mb-2"></i>
                    <span class="text-sm font-medium">Review Permintaan</span>
                </a>
                
                <a href="{{ route('admin.laporan') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-white/20 rounded-lg hover:bg-white/30 transition">
                    <i class="fas fa-file-pdf text-2xl mb-2"></i>
                    <span class="text-sm font-medium">Cetak Laporan</span>
                </a>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-bold text-gray-900">Status Sistem</h4>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-emerald-600">Aktif</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-database text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Database</p>
                            <p class="text-sm text-gray-500">{{ number_format($totalBarang ?? 0, 0, ',', '.') }} barang terdaftar</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-medium rounded-full">
                        Connected
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Pengguna Aktif</p>
                            <p class="text-sm text-gray-500">{{ number_format($totalUsers ?? 0, 0, ',', '.') }} total pengguna</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                        {{ $activeUsers ?? 0 }} online
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sync-alt text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Last Update</p>
                            <p class="text-sm text-gray-500">{{ now()->format('d M Y H:i:s') }}</p>
                        </div>
                    </div>
                    <span class="text-gray-600 text-sm">Real-time</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-amber-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Total Transaksi</p>
                            <p class="text-sm text-gray-500">{{ number_format($totalTransaksi ?? 0, 0, ',', '.') }} transaksi</p>
                        </div>
                    </div>
                    <span class="text-gray-600 text-sm">Bulan ini</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informasi Tambahan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-lg font-bold text-gray-900 mb-4">Alur Kerja Sistem</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-100 transition hover:shadow-md">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-inbox text-blue-600"></i>
                </div>
                <h5 class="font-medium text-gray-900 mb-1">Barang Masuk</h5>
                <p class="text-sm text-gray-600">Input BAST penerimaan barang</p>
            </div>
            
            <div class="text-center p-4 bg-amber-50 rounded-lg border border-amber-100 transition hover:shadow-md">
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-clipboard-list text-amber-600"></i>
                </div>
                <h5 class="font-medium text-gray-900 mb-1">SPB User</h5>
                <p class="text-sm text-gray-600">Terima dan review permintaan</p>
            </div>
            
            <div class="text-center p-4 bg-emerald-50 rounded-lg border border-emerald-100 transition hover:shadow-md">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
                <h5 class="font-medium text-gray-900 mb-1">Review & ACC</h5>
                <p class="text-sm text-gray-600">Cek stok & persetujuan admin</p>
            </div>
            
            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-100 transition hover:shadow-md">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-outbox text-purple-600"></i>
                </div>
                <h5 class="font-medium text-gray-900 mb-1">Barang Keluar</h5>
                <p class="text-sm text-gray-600">BAST pengeluaran barang</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart;

function initChart(data) {
    const ctx = document.getElementById('mutasiChart').getContext('2d');
    
    if (chart) {
        chart.destroy();
    }
    
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Barang Masuk',
                    data: data.masuk,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Barang Keluar',
                    data: data.keluar,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Barang',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: '#e5e7eb'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function changeChartPeriod(period) {
    fetch(`/admin/dashboard/chart-data?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                initChart(data);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Inisialisasi chart saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($chartData) && count($chartData['labels']) > 0)
    initChart({!! json_encode($chartData) !!});
    @endif
});
</script>
@endpush

@push('styles')
<style>
    .transition {
        transition: all 0.2s ease;
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .hover\:shadow-md:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
@endpush