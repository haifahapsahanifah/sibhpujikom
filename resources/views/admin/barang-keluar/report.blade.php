@extends('layouts.admin')

@section('title', 'Laporan Barang Keluar')
@section('page-title', 'Laporan Barang Keluar')
@section('page-subtitle', 'Statistik dan Analisis Barang Keluar')

@section('content')
<div class="space-y-6">
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Barang Keluar per Bulan</h3>
            <canvas id="monthlyChart" height="250"></canvas>
        </div>
        
        <!-- Divisi Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi per Divisi</h3>
            <canvas id="divisiChart" height="250"></canvas>
        </div>
    </div>
    
    <!-- Top Barang -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">10 Barang Paling Sering Keluar</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-3 px-6 text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="text-left py-3 px-6 text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="text-right py-3 px-6 text-xs font-medium text-gray-500 uppercase">Total Keluar</th>
                        <th class="text-right py-3 px-6 text-xs font-medium text-gray-500 uppercase">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalAll = $topBarang->sum('total_jumlah');
                    @endphp
                    @foreach($topBarang as $index => $item)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-6 text-sm">{{ $index + 1 }}</td>
                        <td class="py-3 px-6 text-sm font-medium">{{ $item->nama_barang }}</td>
                        <td class="py-3 px-6 text-sm text-right">{{ number_format($item->total_jumlah) }}</td>
                        <td class="py-3 px-6 text-sm text-right">
                            @php
                                $percent = $totalAll > 0 ? ($item->total_jumlah / $totalAll) * 100 : 0;
                            @endphp
                            {{ number_format($percent, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.bulan),
            datasets: [{
                label: 'Jumlah Barang Keluar',
                data: monthlyData.map(item => item.total),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Jumlah Transaksi',
                data: monthlyData.map(item => item.transaksi),
                borderColor: 'rgb(245, 158, 11)',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah Barang'
                    }
                },
                y1: {
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
    
    // Divisi Chart
    const divisiCtx = document.getElementById('divisiChart').getContext('2d');
    const divisiData = @json($divisiData);
    
    new Chart(divisiCtx, {
        type: 'pie',
        data: {
            labels: divisiData.map(item => item.divisi),
            datasets: [{
                data: divisiData.map(item => item.total_jumlah),
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(139, 92, 246)',
                    'rgb(236, 72, 153)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection