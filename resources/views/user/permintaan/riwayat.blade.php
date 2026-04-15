{{-- resources/views/user/permintaan/riwayat.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Permintaan')

@section('header')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center">
    <div class="mb-4 md:mb-0">
        <h2 class="text-2xl font-bold text-gray-900">Riwayat Permintaan Barang</h2>
        <p class="text-gray-600">Daftar surat permintaan barang yang telah diajukan</p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="{{ route('user.dashboard') }}" 
           class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <a href="{{ route('user.permintaan.create') }}"
           class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus"></i>
            <span>Permintaan Baru</span>
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Menunggu Admin</p>
                    <p class="text-2xl font-bold text-amber-600 mt-2">{{ $stats['menunggu_admin'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Menunggu Konfirmasi</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">{{ $stats['menunggu_user'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Disetujui</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-2">{{ $stats['disetujui'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ditolak</p>
                    <p class="text-2xl font-bold text-red-600 mt-2">{{ $stats['ditolak'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('user.permintaan.riwayat') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Cari No. Surat...">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('user.permintaan.riwayat') }}" class="flex-1 md:flex-none px-4 py-2 border border-gray-200 rounded-lg text-gray-600 text-center hover:bg-gray-50 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-4 px-6 text-gray-600 font-medium text-sm">No. Surat</th>
                        <th class="text-left py-4 px-6 text-gray-600 font-medium text-sm">Tanggal</th>
                        <th class="text-left py-4 px-6 text-gray-600 font-medium text-sm">Divisi</th>
                        <th class="text-left py-4 px-6 text-gray-600 font-medium text-sm">Jumlah Barang</th>
                        <th class="text-left py-4 px-6 text-gray-600 font-medium text-sm">Status</th>
                        <th class="text-left py-4 px-6 text-gray-600 font-medium text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($permintaans as $permintaan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6">
                            <div class="font-medium text-gray-900">{{ $permintaan->nomor_surat }}</div>
                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($permintaan->created_at)->format('d M Y H:i') }}</div>
                        </td>
                        <td class="py-4 px-6">
                            {{ \Carbon\Carbon::parse($permintaan->created_at)->format('d/m/Y') }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $permintaan->divisi }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-medium">{{ $permintaan->details->count() }} barang</span>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $statusClass = [
                                    'menunggu_admin' => 'bg-amber-100 text-amber-800',
                                    'menunggu_user' => 'bg-purple-100 text-purple-800',
                                    'disetujui' => 'bg-emerald-100 text-emerald-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'selesai' => 'bg-gray-100 text-gray-800',
                                ];
                                $statusIcon = [
                                    'menunggu_admin' => 'fa-clock',
                                    'menunggu_user' => 'fa-user-check',
                                    'disetujui' => 'fa-check-circle',
                                    'ditolak' => 'fa-times-circle',
                                    'selesai' => 'fa-check-double',
                                ];
                                $statusText = [
                                    'menunggu_admin' => 'Menunggu Admin',
                                    'menunggu_user' => 'Menunggu Konfirmasi',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'selesai' => 'Selesai',
                                ];
                            @endphp
                            <span class="px-3 py-1 {{ $statusClass[$permintaan->status] }} text-xs font-medium rounded-full">
                                <i class="fas {{ $statusIcon[$permintaan->status] }} mr-1"></i>
                                {{ $statusText[$permintaan->status] }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex space-x-2">
                                <button onclick="showDetail({{ $permintaan->id }})"
                                        class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded hover:bg-blue-200 transition">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                                
                                @if($permintaan->status == 'menunggu_user')
                                <form action="{{ route('user.permintaan.approve', $permintaan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin ingin menyetujui permintaan ini?')"
                                            class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded hover:bg-emerald-200 transition">
                                        <i class="fas fa-check mr-1"></i>Konfirmasi
                                    </button>
                                </form>
                                @elseif($permintaan->status == 'selesai')
                                <a href="{{ route('user.permintaan.cetak-struk', $permintaan->id) }}" target="_blank"
                                        class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200 transition">
                                    <i class="fas fa-receipt mr-1"></i>Lihat Bukti
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada permintaan barang</p>
                            <a href="{{ route('user.permintaan.create') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                                Buat permintaan baru
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $permintaans->links() }}
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Detail Surat Permintaan Barang</h3>
                <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-6" id="detailContent">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="mt-2 text-gray-500">Memuat data...</p>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex justify-end">
                <button onclick="closeDetail()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(id) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-500">Memuat data...</p></div>';
    
    fetch(`/user/permintaan/${id}/detail-json`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderDetailContent(data.permintaan);
            } else {
                content.innerHTML = '<div class="text-center py-8 text-red-500">Gagal memuat data</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = '<div class="text-center py-8 text-red-500">Gagal memuat data</div>';
        });
}

function renderDetailContent(permintaan) {
    const content = document.getElementById('detailContent');
    
    const tanggalDibutuhkan = new Date(permintaan.tanggal_dibutuhkan).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
    
    let html = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">No. Surat</p>
                <p class="font-bold text-lg">${escapeHtml(permintaan.nomor_surat)}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Tanggal Dibutuhkan</p>
                <p class="font-bold">${tanggalDibutuhkan}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Divisi</p>
                <p class="font-bold">${escapeHtml(permintaan.divisi)}</p>
            </div>
        </div>
        
        <div>
            <h4 class="text-lg font-bold text-gray-900 mb-4">Detail Barang</h4>
            <div class="overflow-x-auto border border-gray-300 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-300">
                            <th class="text-center p-3 border-r border-gray-300 w-12">No</th>
                            <th class="text-left p-3 border-r border-gray-300">Nama Barang</th>
                            <th class="text-center p-3 border-r border-gray-300 w-24">Jumlah Diajukan</th>
                            <th class="text-center p-3 border-r border-gray-300 w-24">Jumlah Disetujui</th>
                            <th class="text-center p-3 border-r border-gray-300 w-24">Satuan</th>
                            <th class="text-left p-3">Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
    `;
    
    permintaan.details.forEach((detail, index) => {
        // Tampilkan jumlah disetujui jika ada, jika tidak tampilkan "-"
        const disetujuiJumlah = detail.disetujui_jumlah ? detail.disetujui_jumlah : '-';
        
        html += `
            <tr class="border-b border-gray-300">
                <td class="text-center p-3 border-r border-gray-300">${index + 1}</td>
                <td class="p-3 border-r border-gray-300">${escapeHtml(detail.nama_barang)}</td>
                <td class="text-center p-3 border-r border-gray-300">${detail.pengajuan_jumlah}</td>
                <td class="text-center p-3 border-r border-gray-300">
                    <span class="${detail.disetujui_jumlah ? 'text-emerald-600 font-medium' : 'text-gray-500'}">
                        ${disetujuiJumlah}
                    </span>
                </td>
                <td class="text-center p-3 border-r border-gray-300">${escapeHtml(detail.satuan)}</td>
                <td class="p-3">${escapeHtml(detail.keperluan)}</td>
            </tr>
        `;
        
        // Tampilkan catatan admin jika ada
        if (detail.catatan_admin) {
            html += `
                <tr class="bg-yellow-50">
                    <td colspan="6" class="p-3 text-sm text-yellow-700">
                        <i class="fas fa-info-circle mr-2"></i>Catatan Admin: ${escapeHtml(detail.catatan_admin)}
                    </td>
                </tr>
            `;
        }
    });
    
    html += `
                    </tbody>
                </table>
            </div>
        </div>
    `;
    
    // Tampilkan informasi persetujuan dengan detail jumlah
    if (permintaan.details.some(d => d.disetujui_jumlah)) {
        const totalDisetujui = permintaan.details.reduce((sum, d) => sum + (parseInt(d.disetujui_jumlah) || 0), 0);
        const totalDiajukan = permintaan.details.reduce((sum, d) => sum + parseInt(d.pengajuan_jumlah), 0);
        
        html += `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-800 font-medium"><i class="fas fa-check-circle mr-2"></i>Ringkasan Persetujuan:</p>
                <p class="text-green-700 mt-1">
                    Total barang diajukan: <strong>${totalDiajukan}</strong> unit<br>
                    Total barang disetujui: <strong>${totalDisetujui}</strong> unit
                </p>
            </div>
        `;
    }
    
    if (permintaan.alasan_ditolak) {
        html += `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-800 font-medium"><i class="fas fa-exclamation-circle mr-2"></i>Alasan Ditolak:</p>
                <p class="text-red-700 mt-1">${escapeHtml(permintaan.alasan_ditolak)}</p>
            </div>
        `;
    }
    
    if (permintaan.catatan) {
        html += `
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-800 font-medium"><i class="fas fa-sticky-note mr-2"></i>Catatan Tambahan:</p>
                <p class="text-gray-600 mt-1">${escapeHtml(permintaan.catatan)}</p>
            </div>
        `;
    }
    
    if (permintaan.disetujui_admin_at) {
        const tanggalAdmin = new Date(permintaan.disetujui_admin_at).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        html += `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-800 font-medium"><i class="fas fa-user-check mr-2"></i>Disetujui oleh Admin:</p>
                <p class="text-blue-700 mt-1">${permintaan.admin ? permintaan.admin.nama : 'Admin'} pada ${tanggalAdmin}</p>
            </div>
        `;
    }
    
    content.innerHTML = html;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetail();
    }
});
</script>
@endsection