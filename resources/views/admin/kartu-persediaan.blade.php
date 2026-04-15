{{-- resources/views/admin/kartu-persediaan.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kartu Persediaan')
@section('page-title', 'Kartu Persediaan')
@section('page-subtitle', 'Riwayat mutasi stok barang')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('admin.kartu.persediaan') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang</label>
                    <select name="barang_id" id="barang_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                        <option value="">-- Semua Barang --</option>
                        @foreach($barangList as $item)
                            <option value="{{ $item->id }}" {{ request('barang_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->kode_barang }} - {{ $item->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                    <select name="periode" id="periode" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                        <option value="bulan_ini" {{ request('periode', 'bulan_ini') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="3_bulan" {{ request('periode') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        <option value="6_bulan" {{ request('periode') == '6_bulan' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                        <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Kustom</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" 
                           value="{{ request('tanggal_awal', $tanggalAwal instanceof \Carbon\Carbon ? $tanggalAwal->format('Y-m-d') : '') }}" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" 
                           {{ request('periode') != 'custom' ? 'disabled' : '' }}>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" 
                           value="{{ request('tanggal_akhir', $tanggalAkhir instanceof \Carbon\Carbon ? $tanggalAkhir->format('Y-m-d') : '') }}" 
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" 
                           {{ request('periode') != 'custom' ? 'disabled' : '' }}>
                </div>
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <a href="{{ route('admin.kartu.persediaan') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
                <a href="{{ route('admin.kartu.persediaan.print', request()->all()) }}" target="_blank" class="px-6 py-2 bg-green-600 text-white rounded-xl text-sm font-medium hover:bg-green-700 transition">
                    <i class="fas fa-print mr-2"></i>Cetak Laporan
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>

    @if(request('barang_id') == '')
        <!-- Tampilan Semua Barang -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Rekapitulasi Stok Semua Barang</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Periode: {{ $tanggalAwal instanceof \Carbon\Carbon ? $tanggalAwal->format('d M Y') : date('d M Y', strtotime($tanggalAwal)) }} - 
                            {{ $tanggalAkhir instanceof \Carbon\Carbon ? $tanggalAkhir->format('d M Y') : date('d M Y', strtotime($tanggalAkhir)) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Nilai Persediaan</p>
                        <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalNilaiPersediaan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Satuan</th>
                            <th class="text-right py-4 px-6 text-xs font-medium text-gray-500 uppercase">Stok Awal</th>
                            <th class="text-right py-4 px-6 text-xs font-medium text-gray-500 uppercase">Masuk</th>
                            <th class="text-right py-4 px-6 text-xs font-medium text-gray-500 uppercase">Keluar</th>
                            <th class="text-right py-4 px-6 text-xs font-medium text-gray-500 uppercase">Stok Akhir</th>
                            <th class="text-right py-4 px-6 text-xs font-medium text-gray-500 uppercase">Nilai (Rp)</th>
                            <th class="text-center py-4 px-6 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allBarangStats as $index => $stat)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-4 px-6 text-sm">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 text-sm font-medium">{{ $stat['kode_barang'] }}</td>
                            <td class="py-4 px-6 text-sm">{{ $stat['nama_barang'] }}</td>
                            <td class="py-4 px-6 text-sm">{{ $stat['kategori'] }}</td>
                            <td class="py-4 px-6 text-sm">{{ $stat['satuan'] }}</td>
                            <td class="py-4 px-6 text-sm text-right">{{ number_format($stat['stok_awal'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right text-green-600">{{ number_format($stat['total_masuk'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right text-red-600">{{ number_format($stat['total_keluar'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right font-bold text-blue-600">{{ number_format($stat['stok_akhir'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right">Rp {{ number_format($stat['nilai_total'], 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-center">
                                <a href="{{ route('admin.kartu.persediaan', ['barang_id' => $stat['id']] + request()->except('barang_id')) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                <p>Tidak ada data barang</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($allBarangStats) > 0)
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr class="font-bold">
                            <td colspan="5" class="py-4 px-6 text-sm">Total Keseluruhan</td>
                            <td class="py-4 px-6 text-sm text-right">{{ number_format($allBarangStats->sum('stok_awal'), 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right text-green-600">{{ number_format($allBarangStats->sum('total_masuk'), 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right text-red-600">{{ number_format($allBarangStats->sum('total_keluar'), 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right text-blue-600">{{ number_format($allBarangStats->sum('stok_akhir'), 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right">Rp {{ number_format($allBarangStats->sum('nilai_total'), 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Grafik Top 10 Barang -->
        @if(count($allBarangStats) > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-6">Top 10 Barang dengan Stok Terbanyak</h3>
            <canvas id="topBarangChart" class="w-full" style="height: 400px;"></canvas>
        </div>
        @endif

    @elseif($selectedBarang)
        <!-- Tampilan Detail Satu Barang -->
        <!-- Info Barang -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex-1">
                    <p class="text-blue-100 text-sm">Kode Barang</p>
                    <p class="text-2xl font-bold">{{ $selectedBarang->kode_barang }} - {{ $selectedBarang->nama_barang }}</p>
                    <div class="flex flex-wrap gap-4 mt-2">
                        <p class="text-blue-100 text-sm">
                            <i class="fas fa-tag mr-1"></i>Kategori: {{ $selectedBarang->kategori->name ?? '-' }}
                        </p>
                        <p class="text-blue-100 text-sm">
                            <i class="fas fa-balance-scale mr-1"></i>Satuan: {{ $selectedBarang->satuan->name ?? $selectedBarang->satuan_nama ?? 'pcs' }}
                        </p>
                    </div>
                </div>
                <div class="text-center md:text-right mt-4 md:mt-0">
                    <p class="text-blue-100 text-sm">Stok Akhir</p>
                    <p class="text-4xl font-bold">{{ number_format($stokAkhir, 0, ',', '.') }}</p>
                    <p class="text-blue-100 text-sm mt-2">
                        <i class="fas fa-calendar-alt mr-1"></i>Per {{ $tanggalAkhir instanceof \Carbon\Carbon ? $tanggalAkhir->format('d M Y') : date('d M Y', strtotime($tanggalAkhir)) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Ringkasan Mutasi -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Barang Masuk</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalMasuk, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Barang Keluar</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($totalKeluar, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Saldo Akhir</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($stokAkhir, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Mutasi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">No. Referensi</th>
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Jenis</th>
                            <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                            <th class="text-right py-4 px-6 text-xs font-medium text-gray-500 uppercase">Masuk</th>
                            <th class="text-right py-4 px-6 text-xs font-medium text-gray-500 uppercase">Keluar</th>
                            <th class="text-right py-4 px-6 text-xs font-medium text-gray-500 uppercase">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mutasi as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td class="py-4 px-6 text-sm font-medium">
                                @if($item->jenis == 'masuk')
                                    {{ $item->nomor_dokumen ?? '-' }}
                                @else
                                    {{ $item->nomor_surat ?? '-' }}
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm">
                                @if($item->jenis == 'masuk')
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                        <i class="fas fa-arrow-down mr-1"></i>Barang Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">
                                        <i class="fas fa-arrow-up mr-1"></i>Barang Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm">
                                @if($item->jenis == 'masuk')
                                    {{ $item->keterangan ?? 'Pembelian Barang' }}
                                @else
                                    {{ $item->keperluan ?? 'Permintaan Barang' }}
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm text-right">
                                @if($item->jenis == 'masuk')
                                    <span class="text-green-600 font-medium">{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm text-right">
                                @if($item->jenis == 'keluar')
                                    <span class="text-red-600 font-medium">{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm text-right font-bold">
                                {{ number_format($item->saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                <p>Tidak ada data mutasi untuk periode yang dipilih</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($mutasi) > 0)
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr class="font-bold">
                            <td colspan="4" class="py-4 px-6 text-sm">Total Mutasi</td>
                            <td class="py-4 px-6 text-sm text-right text-green-600">{{ number_format($totalMasuk, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right text-red-600">{{ number_format($totalKeluar, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-sm text-right">{{ number_format($stokAkhir, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Grafik Mutasi -->
        @if(count($chartData['labels']) > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-6">Grafik Mutasi Barang</h3>
            <canvas id="mutasiChart" class="w-full" style="height: 400px;"></canvas>
        </div>
        @endif
    @else
        <!-- Peringatan jika belum memilih barang -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-12 text-center">
            <i class="fas fa-chart-line text-5xl text-yellow-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Pilih Barang Terlebih Dahulu</h3>
            <p class="text-yellow-600">Silakan pilih barang dari filter di atas untuk melihat kartu persediaan detail</p>
            <p class="text-yellow-600 mt-2">Atau biarkan kosong untuk melihat rekap semua barang</p>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Fungsi untuk update date range berdasarkan periode
function updateDateRange(periode) {
    const tanggalAwal = document.getElementById('tanggal_awal');
    const tanggalAkhir = document.getElementById('tanggal_akhir');
    const today = new Date();
    
    if (periode === 'custom') {
        tanggalAwal.disabled = false;
        tanggalAkhir.disabled = false;
    } else {
        tanggalAwal.disabled = true;
        tanggalAkhir.disabled = true;
        
        let startDate = new Date();
        
        switch(periode) {
            case 'bulan_ini':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                break;
            case '3_bulan':
                startDate = new Date(today.getFullYear(), today.getMonth() - 2, 1);
                break;
            case '6_bulan':
                startDate = new Date(today.getFullYear(), today.getMonth() - 5, 1);
                break;
            case 'tahun_ini':
                startDate = new Date(today.getFullYear(), 0, 1);
                break;
            default:
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
        }
        
        tanggalAwal.value = startDate.toISOString().split('T')[0];
        tanggalAkhir.value = today.toISOString().split('T')[0];
    }
}

// Inisialisasi chart untuk detail barang
@if(isset($selectedBarang) && $selectedBarang && count($chartData['labels']) > 0)
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('mutasiChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: {!! json_encode($chartData['masuk']) !!},
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
                        data: {!! json_encode($chartData['keluar']) !!},
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Saldo Akhir',
                        data: {!! json_encode($chartData['saldo']) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: '#3b82f6',
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
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Barang'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                }
            }
        });
    }
});
@endif

// Inisialisasi chart untuk top 10 barang
@if(isset($allBarangStats) && count($allBarangStats) > 0)
document.addEventListener('DOMContentLoaded', function() {
    const topBarangChart = document.getElementById('topBarangChart');
    if (topBarangChart) {
        const top10 = @json($allBarangStats->take(10));
        const labels = top10.map(item => item.nama_barang);
        const stokData = top10.map(item => item.stok_akhir);
        
        new Chart(topBarangChart, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Stok Akhir',
                    data: stokData,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Stok: ${context.raw.toLocaleString('id-ID')} unit`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Stok'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Nama Barang'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
});
@endif

// Event listener untuk perubahan periode
document.getElementById('periode')?.addEventListener('change', function() {
    updateDateRange(this.value);
});

// Inisialisasi saat load
document.addEventListener('DOMContentLoaded', function() {
    const periode = document.getElementById('periode')?.value;
    if (periode) {
        updateDateRange(periode);
    }
});
</script>
@endpush

@push('styles')
<style>
    canvas {
        max-height: 400px;
        width: 100%;
    }
    
    .transition {
        transition: all 0.2s ease;
    }
    
    .hover\:shadow-md:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    tfoot tr {
        background-color: #f9fafb;
    }
</style>
@endpush
@endsection