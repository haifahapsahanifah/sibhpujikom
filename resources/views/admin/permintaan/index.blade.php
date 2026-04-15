{{-- resources/views/admin/permintaan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Daftar Permintaan')
@section('page-title', 'Daftar Permintaan')
@section('page-subtitle', 'Kelola permintaan barang')

@section('content')
<div class="space-y-6">
    <!-- Statistik Permintaan -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Permintaan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Menunggu Admin</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['menunggu_admin'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Menunggu User</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['menunggu_user'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Disetujui</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['disetujui'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Ditolak</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['ditolak'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Selesai</p>
                    <p class="text-2xl font-bold text-gray-600 mt-1">{{ $stats['selesai'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.permintaan.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1 w-full">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" 
                       placeholder="Cari No. Surat, Divisi, atau Nama Pemohon...">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.permintaan.index') }}" class="flex-1 md:flex-none px-4 py-2 border border-gray-200 rounded-xl text-gray-600 text-center hover:bg-gray-50 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Permintaan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">No. SPB</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Pemohon</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Divisi</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Barang</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Prioritas</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left py-4 px-6 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaans as $permintaan)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm font-medium">{{ $permintaan->nomor_surat }}</td>
                        <td class="py-4 px-6 text-sm">{{ \Carbon\Carbon::parse($permintaan->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="py-4 px-6 text-sm">{{ $permintaan->user->nama ?? $permintaan->user->name }}</td>
                        <td class="py-4 px-6 text-sm">{{ $permintaan->divisi }}</td>
                        <td class="py-4 px-6 text-sm">
                            <div class="space-y-1">
                                @foreach($permintaan->details as $detail)
                                    <div class="text-xs">
                                        {{ $detail->nama_barang }} 
                                        <span class="text-gray-500">({{ $detail->pengajuan_jumlah }} {{ $detail->satuan }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm">
                            @php
                                $prioritasClass = [
                                    'biasa' => 'bg-blue-100 text-blue-800',
                                    'segera' => 'bg-yellow-100 text-yellow-800',
                                    'sangat_segera' => 'bg-red-100 text-red-800',
                                ];
                                $prioritasText = [
                                    'biasa' => 'Biasa',
                                    'segera' => 'Segera',
                                    'sangat_segera' => 'Sangat Segera',
                                ];
                            @endphp
                            <span class="px-3 py-1 {{ $prioritasClass[$permintaan->prioritas] ?? 'bg-gray-100 text-gray-800' }} text-xs rounded-full">
                                {{ $prioritasText[$permintaan->prioritas] ?? 'Biasa' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-sm">
                            @php
                                $statusClass = [
                                    'menunggu_admin' => 'bg-amber-100 text-amber-800',
                                    'menunggu_user' => 'bg-purple-100 text-purple-800',
                                    'disetujui' => 'bg-green-100 text-green-800',
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
                                    'menunggu_user' => 'Menunggu Konfirmasi User',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'selesai' => 'Selesai',
                                ];
                            @endphp
                            <span class="px-3 py-1 {{ $statusClass[$permintaan->status] ?? 'bg-gray-100 text-gray-800' }} text-xs rounded-full">
                                <i class="fas {{ $statusIcon[$permintaan->status] ?? 'fa-question' }} mr-1"></i>
                                {{ $statusText[$permintaan->status] ?? $permintaan->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex space-x-2">
                                <button onclick="showDetail({{ $permintaan->id }})"
                                        class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($permintaan->status == 'menunggu_admin')
                                <button onclick="openApprovalModal({{ $permintaan->id }})"
                                        class="text-green-600 hover:text-green-800 transition" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="openRejectModal({{ $permintaan->id }})"
                                        class="text-red-600 hover:text-red-800 transition" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                                @elseif($permintaan->status == 'disetujui')
                                <a href="{{ route('admin.permintaan.cetak-struk', $permintaan->id) }}" target="_blank"
                                        class="text-emerald-600 hover:text-emerald-800 transition" title="Cetak Struk & Selesaikan">
                                    <i class="fas fa-print"></i>
                                </a>
                                @elseif($permintaan->status == 'selesai')
                                <a href="{{ route('admin.permintaan.cetak-struk', $permintaan->id) }}" target="_blank"
                                        class="text-gray-600 hover:text-gray-800 transition" title="Cetak Ulang Struk">
                                    <i class="fas fa-receipt"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada permintaan barang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($permintaans, 'links'))
        <div class="p-6 border-t border-gray-100">
            {{ $permintaans->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Detail Surat Permintaan Barang</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-6 overflow-y-auto flex-1" id="detailContent">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                    <p class="mt-2 text-gray-500">Memuat data...</p>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 flex justify-end">
                <button onclick="closeDetailModal()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Approval -->
<div id="approvalModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Persetujuan Permintaan Barang</h3>
                <button onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="approvalForm" method="POST">
                @csrf
                <div class="p-6 space-y-6 overflow-y-auto flex-1" id="approvalContent">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                        <p class="mt-2 text-gray-500">Memuat data...</p>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeApprovalModal()"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Persetujuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Tolak Permintaan</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="alasan" id="alasan_penolakan" rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                  placeholder="Berikan alasan penolakan dengan jelas..."
                                  required></textarea>
                        <p class="mt-2 text-xs text-gray-500">Alasan akan dikirimkan ke pemohon</p>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-times mr-2"></i>Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toast Notification System
window.showToast = function(type, message) {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        info: 'fa-info-circle',
        warning: 'fa-exclamation-triangle'
    };
    
    toast.className = `${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 transform transition-all duration-300 translate-x-full`;
    toast.innerHTML = `
        <i class="fas ${icons[type]}"></i>
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="hover:opacity-75">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
    }, 10);
    
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
};

let currentPermintaanId = null;

// Fungsi untuk menampilkan detail permintaan
function showDetail(id) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    
    if (!modal || !content) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-500">Memuat data...</p></div>';
    
    fetch(`/admin/permintaan/${id}/detail-json`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.permintaan) {
            renderDetailContent(data.permintaan);
        } else {
            content.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data: ${data.message || 'Data tidak valid'}</div>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        content.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data: ${error.message}</div>`;
        window.showToast('error', 'Gagal memuat detail permintaan');
    });
}

// Render konten detail
function renderDetailContent(permintaan) {
    const content = document.getElementById('detailContent');
    if (!content) return;
    
    let tanggalDibutuhkan = permintaan.tanggal_dibutuhkan || '-';
    let tanggalDiajukan = permintaan.created_at || '-';
    
    let html = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">No. Surat</p>
                <p class="font-bold text-lg">${escapeHtml(permintaan.nomor_surat)}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Tanggal Diajukan</p>
                <p class="font-bold">${escapeHtml(tanggalDiajukan)}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Tanggal Dibutuhkan</p>
                <p class="font-bold">${escapeHtml(tanggalDibutuhkan)}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Divisi</p>
                <p class="font-bold">${escapeHtml(permintaan.divisi)}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Pemohon</p>
                <p class="font-bold">${escapeHtml(permintaan.user.nama)}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Prioritas</p>
                <p class="font-bold">
                    ${getPrioritasBadge(permintaan.prioritas)}
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-bold">
                    ${getStatusBadge(permintaan.status)}
                </p>
            </div>
        </div>
        
        <div>
            <h4 class="text-lg font-bold text-gray-900 mb-4">Detail Barang yang Diminta</h4>
            <div class="overflow-x-auto border border-gray-300 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-300">
                            <th class="text-center p-3 border-r border-gray-300 w-12">No</th>
                            <th class="text-left p-3 border-r border-gray-300">Kode Barang</th>
                            <th class="text-left p-3 border-r border-gray-300">Nama Barang</th>
                            <th class="text-center p-3 border-r border-gray-300 w-24">Jumlah Diminta</th>
                            <th class="text-center p-3 border-r border-gray-300 w-24">Satuan</th>
                            <th class="text-left p-3 border-r border-gray-300">Spesifikasi</th>
                            <th class="text-left p-3">Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
    `;
    
    if (permintaan.details && permintaan.details.length > 0) {
        permintaan.details.forEach((detail, index) => {
            const satuanName = detail.satuan || 'pcs';
            html += `
                <tr class="border-b border-gray-300">
                    <td class="text-center p-3 border-r border-gray-300">${index + 1}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.kode_barang || '-')}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.nama_barang)}</td>
                    <td class="text-center p-3 border-r border-gray-300">${detail.pengajuan_jumlah}</td>
                    <td class="text-center p-3 border-r border-gray-300">${escapeHtml(satuanName)}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.spesifikasi || '-')}</td>
                    <td class="p-3">${escapeHtml(detail.keperluan)}</td>
                </tr>
            `;
            
            if (detail.disetujui_jumlah && detail.disetujui_jumlah > 0) {
                html += `
                    <tr class="bg-green-50">
                        <td colspan="7" class="p-3 text-sm text-green-700">
                            <i class="fas fa-check-circle mr-2"></i>Disetujui: ${detail.disetujui_jumlah} ${detail.disetujui_satuan || satuanName}
                            ${detail.catatan_admin ? `<br><i class="fas fa-sticky-note mr-2"></i>Catatan Admin: ${escapeHtml(detail.catatan_admin)}` : ''}
                        </td>
                    </tr>
                `;
            } else if (detail.catatan_admin) {
                html += `
                    <tr class="bg-yellow-50">
                        <td colspan="7" class="p-3 text-sm text-yellow-700">
                            <i class="fas fa-info-circle mr-2"></i>Catatan Admin: ${escapeHtml(detail.catatan_admin)}
                        </td>
                    </tr>
                `;
            }
        });
    } else {
        html += `
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-500">
                    Tidak ada detail barang
                </td>
            </tr>
        `;
    }
    
    html += `
                    </tbody>
                </table>
            </div>
        </div>
    `;
    
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
                <p class="text-gray-800 font-medium"><i class="fas fa-sticky-note mr-2"></i>Catatan Pemohon:</p>
                <p class="text-gray-600 mt-1">${escapeHtml(permintaan.catatan)}</p>
            </div>
        `;
    }
    
    content.innerHTML = html;
}

// Fungsi untuk membuka modal approval
function openApprovalModal(id) {
    currentPermintaanId = id;
    const modal = document.getElementById('approvalModal');
    const content = document.getElementById('approvalContent');
    const form = document.getElementById('approvalForm');
    
    if (!modal || !content || !form) {
        console.error('Modal elements not found');
        window.showToast('error', 'Terjadi kesalahan: Modal tidak ditemukan');
        return;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><p class="mt-2 text-gray-500">Memuat data...</p></div>';
    
    form.action = `/admin/permintaan/${id}/approve`;
    
    fetch(`/admin/permintaan/${id}/detail-json`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.permintaan) {
            renderApprovalContent(data.permintaan);
        } else {
            content.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data: ${data.message || 'Data tidak valid'}</div>`;
            window.showToast('error', 'Gagal memuat data permintaan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        content.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data: ${error.message}</div>`;
        window.showToast('error', 'Gagal memuat data permintaan');
    });
}

// Render konten approval
function renderApprovalContent(permintaan) {
    const content = document.getElementById('approvalContent');
    if (!content) return;
    
    let html = `
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">No. Surat</p>
                    <p class="font-bold">${escapeHtml(permintaan.nomor_surat)}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pemohon</p>
                    <p class="font-bold">${escapeHtml(permintaan.user.nama)}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Divisi</p>
                    <p class="font-bold">${escapeHtml(permintaan.divisi)}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Dibutuhkan</p>
                    <p class="font-bold">${escapeHtml(permintaan.tanggal_dibutuhkan)}</p>
                </div>
            </div>
        </div>
        
        <div>
            <h4 class="text-lg font-bold text-gray-900 mb-4">Detail Barang Permintaan</h4>
            <div class="overflow-x-auto border border-gray-300 rounded-lg">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-300">
                            <th class="text-center p-3 border-r border-gray-300 w-12">No</th>
                            <th class="text-left p-3 border-r border-gray-300">Nama Barang</th>
                            <th class="text-left p-3 border-r border-gray-300">Spesifikasi</th>
                            <th class="text-center p-3 border-r border-gray-300 w-24">Diminta</th>
                            <th class="text-center p-3 border-r border-gray-300 w-20">Satuan</th>
                            <th class="text-left p-3 border-r border-gray-300">Keperluan</th>
                            <th class="text-center p-3 border-r border-gray-300 w-28">Disetujui</th>
                            <th class="text-left p-3">Catatan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
    `;
    
    if (permintaan.details && permintaan.details.length > 0) {
        permintaan.details.forEach((detail, index) => {
            const jumlahDisetujui = detail.disetujui_jumlah || detail.pengajuan_jumlah;
            
            html += `
                <tr class="border-b border-gray-300">
                    <td class="text-center p-3 border-r border-gray-300">${index + 1}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.nama_barang)}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.spesifikasi || '-')}</td>
                    <td class="text-center p-3 border-r border-gray-300">${detail.pengajuan_jumlah}</td>
                    <td class="text-center p-3 border-r border-gray-300">${escapeHtml(detail.satuan)}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.keperluan)}</td>
                    <td class="text-center p-3 border-r border-gray-300">
                        <input type="number" 
                               name="details[${detail.id}][disetujui_jumlah]" 
                               class="w-20 px-2 py-1 border border-gray-300 rounded text-center"
                               value="${jumlahDisetujui}"
                               min="0"
                               max="${detail.pengajuan_jumlah}"
                               required
                               onchange="updateStatus(this)">
                        <input type="hidden" name="details[${detail.id}][status]" class="detail-status" value="${jumlahDisetujui > 0 ? 'disetujui' : 'disesuaikan'}">
                        <input type="hidden" name="details[${detail.id}][satuan]" value="${escapeHtml(detail.satuan)}">
                    </td>
                    <td class="p-3">
                        <textarea name="details[${detail.id}][catatan_admin]" 
                                  class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                  rows="2"
                                  placeholder="Catatan (opsional)">${escapeHtml(detail.catatan_admin || '')}</textarea>
                    </td>
                </tr>
            `;
        });
    } else {
        html += `
            <tr>
                <td colspan="8" class="p-8 text-center text-gray-500">
                    Tidak ada detail barang
                </td>
            </tr>
        `;
    }
    
    html += `
                    </tbody>
                </table>
            </div>
            <p class="mt-2 text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Jika jumlah disetujui diisi 0, item tersebut akan dianggap tidak disetujui.
            </p>
        </div>
    `;
    
    if (permintaan.catatan) {
        html += `
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-800 font-medium">Catatan Pemohon:</p>
                <p class="text-gray-600 mt-1">${escapeHtml(permintaan.catatan)}</p>
            </div>
        `;
    }
    
    content.innerHTML = html;
}

// Fungsi untuk update status berdasarkan jumlah
function updateStatus(input) {
    const row = input.closest('tr');
    const statusInput = row.querySelector('.detail-status');
    if (statusInput) {
        const jumlah = parseInt(input.value) || 0;
        statusInput.value = jumlah > 0 ? 'disetujui' : 'disesuaikan';
    }
}

// Fungsi untuk membuka modal reject
function openRejectModal(id) {
    currentPermintaanId = id;
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const textarea = document.getElementById('alasan_penolakan');
    
    if (!modal || !form) {
        console.error('Reject modal elements not found');
        window.showToast('error', 'Terjadi kesalahan: Modal tidak ditemukan');
        return;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    form.action = `/admin/permintaan/${id}/reject`;
    
    if (textarea) {
        textarea.value = '';
    }
}

// Fungsi untuk mendapatkan badge prioritas
function getPrioritasBadge(prioritas) {
    const config = {
        'biasa': 'bg-blue-100 text-blue-800',
        'segera': 'bg-yellow-100 text-yellow-800',
        'sangat_segera': 'bg-red-100 text-red-800'
    };
    const text = {
        'biasa': 'Biasa',
        'segera': 'Segera',
        'sangat_segera': 'Sangat Segera'
    };
    const className = config[prioritas] || config['biasa'];
    return `<span class="px-3 py-1 ${className} text-xs font-medium rounded-full">${text[prioritas] || 'Biasa'}</span>`;
}

// Fungsi untuk mendapatkan badge status
function getStatusBadge(status) {
    const statusConfig = {
        'menunggu_admin': { class: 'bg-amber-100 text-amber-800', icon: 'fa-clock', text: 'Menunggu Admin' },
        'menunggu_user': { class: 'bg-purple-100 text-purple-800', icon: 'fa-user-check', text: 'Menunggu Konfirmasi User' },
        'disetujui': { class: 'bg-green-100 text-green-800', icon: 'fa-check-circle', text: 'Disetujui' },
        'ditolak': { class: 'bg-red-100 text-red-800', icon: 'fa-times-circle', text: 'Ditolak' },
        'selesai': { class: 'bg-gray-100 text-gray-800', icon: 'fa-check-double', text: 'Selesai' }
    };
    const config = statusConfig[status] || statusConfig['menunggu_admin'];
    return `<span class="px-3 py-1 ${config.class} text-xs font-medium rounded-full">
                <i class="fas ${config.icon} mr-1"></i>${config.text}
            </span>`;
}

// Fungsi untuk escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Fungsi untuk menutup modal
function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function closeApprovalModal() {
    const modal = document.getElementById('approvalModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentPermintaanId = null;
    }
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentPermintaanId = null;
    }
}

// Submit form approval dengan AJAX
document.getElementById('approvalForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showToast('success', data.message);
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            window.showToast('error', data.message || 'Terjadi kesalahan');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast('error', 'Terjadi kesalahan pada server');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Submit form reject dengan AJAX
document.getElementById('rejectForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    const alasan = document.getElementById('alasan_penolakan')?.value.trim();
    
    if (!alasan || alasan.length < 10) {
        window.showToast('error', 'Alasan penolakan harus diisi minimal 10 karakter');
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showToast('success', data.message);
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            window.showToast('error', data.message || 'Terjadi kesalahan');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showToast('error', 'Terjadi kesalahan pada server');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Close modal when clicking outside
document.getElementById('detailModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeDetailModal();
});

document.getElementById('approvalModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeApprovalModal();
});

document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});

// Event listener untuk tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
        closeApprovalModal();
        closeRejectModal();
    }
});
</script>
@endsection