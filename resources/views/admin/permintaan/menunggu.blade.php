{{-- resources/views/admin/permintaan/menunggu.blade.php --}}
@extends('layouts.admin')

@section('title', 'Menunggu Persetujuan')
@section('page-title', 'Menunggu Persetujuan')
@section('page-subtitle', 'Permintaan yang perlu disetujui')

@section('content')
<div class="space-y-6">
    <!-- Header dengan Count -->
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-amber-800">Permintaan Menunggu Persetujuan</h3>
                <p class="text-amber-600 mt-1">Terdapat {{ $permintaans->count() }} permintaan yang perlu Anda setujui</p>
            </div>
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">
                <span class="text-2xl font-bold">{{ $permintaans->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.permintaan.menunggu') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
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
                    <a href="{{ route('admin.permintaan.menunggu') }}" class="flex-1 md:flex-none px-4 py-2 border border-gray-200 rounded-xl text-gray-600 text-center hover:bg-gray-50 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Daftar Permintaan -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($permintaans as $permintaan)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3">
                            <h4 class="font-semibold text-gray-900">{{ $permintaan->nomor_surat }}</h4>
                            @if($permintaan->prioritas == 'sangat_segera')
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Sangat Segera</span>
                            @elseif($permintaan->prioritas == 'segera')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Segera</span>
                            @else
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Biasa</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $permintaan->user->nama ?? $permintaan->user->name }} ({{ $permintaan->divisi }})</p>
                        <p class="text-xs text-gray-500 mt-2">Diajukan: {{ \Carbon\Carbon::parse($permintaan->created_at)->format('d M Y H:i') }}</p>
                        <p class="text-xs text-gray-500">Dibutuhkan: {{ \Carbon\Carbon::parse($permintaan->tanggal_dibutuhkan)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex gap-2">
                    <button onclick="openApprovalModal({{ $permintaan->id }})" 
                            class="px-4 py-2 text-sm text-white bg-green-600 rounded-xl hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i>Proses Persetujuan
                    </button>
                    <button onclick="openRejectModal({{ $permintaan->id }})" 
                            class="px-4 py-2 text-sm text-white bg-red-600 rounded-xl hover:bg-red-700 transition">
                        <i class="fas fa-times mr-2"></i>Tolak
                    </button>
                </div>
            </div>
            
            <!-- Preview Barang -->
            <div class="mt-4 border-t border-gray-100 pt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Ringkasan Permintaan:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($permintaan->details as $detail)
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                            {{ $detail->nama_barang }} ({{ $detail->pengajuan_jumlah }} {{ $detail->satuan }})
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Permintaan Menunggu</h3>
            <p class="text-gray-500">Semua permintaan telah diproses</p>
        </div>
        @endforelse
    </div>
    
    @if($permintaans->hasPages())
    <div class="mt-6">
        {{ $permintaans->links() }}
    </div>
    @endif
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Persetujuan Permintaan Barang</h3>
                <button onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="approvalForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 space-y-6 overflow-y-auto flex-1" id="approvalContent">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                        <p class="mt-2 text-gray-500">Memuat data permintaan...</p>
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

<!-- Reject Modal -->
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
                                  placeholder="Berikan alasan penolakan dengan jelas (minimal 10 karakter)..."
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

function openApprovalModal(permintaanId) {
    console.log('Opening approval modal for ID:', permintaanId);
    currentPermintaanId = permintaanId;
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
    
    // Tampilkan loading
    content.innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
            <p class="mt-2 text-gray-500">Memuat data permintaan...</p>
        </div>
    `;
    
    // Set form action
    form.action = `/admin/permintaan/${permintaanId}/approve`;
    
    // Fetch data
    fetch(`/admin/permintaan/${permintaanId}/detail-json`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success && data.permintaan) {
            renderApprovalContent(data.permintaan);
        } else {
            content.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                    <p>Gagal memuat data: ${data.message || 'Data tidak valid'}</p>
                    <button onclick="closeApprovalModal()" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded-lg">Tutup</button>
                </div>
            `;
            window.showToast('error', data.message || 'Gagal memuat data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        content.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                <p>Gagal memuat data. Silakan coba lagi.</p>
                <p class="text-sm mt-2">${error.message}</p>
                <button onclick="closeApprovalModal()" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded-lg">Tutup</button>
            </div>
        `;
        window.showToast('error', 'Gagal memuat data permintaan');
    });
}

function renderApprovalContent(permintaan) {
    const content = document.getElementById('approvalContent');
    if (!content) return;
    
    // Format tanggal
    let tanggalDibutuhkan = permintaan.tanggal_dibutuhkan || '-';
    try {
        if (tanggalDibutuhkan !== '-') {
            tanggalDibutuhkan = new Date(permintaan.tanggal_dibutuhkan).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
    } catch(e) {
        // keep original
    }
    
    let html = `
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">No. Surat</p>
                    <p class="font-bold text-lg">${escapeHtml(permintaan.nomor_surat)}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pemohon</p>
                    <p class="font-bold">${escapeHtml(permintaan.user.nama)}</p>
                    <p class="text-xs text-gray-500">${escapeHtml(permintaan.divisi)}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Dibutuhkan</p>
                    <p class="font-bold">${escapeHtml(tanggalDibutuhkan)}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Prioritas</p>
                    <p class="font-bold">
                        ${getPrioritasBadge(permintaan.prioritas)}
                    </p>
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
            const satuanName = detail.satuan || 'pcs';
            
            html += `
                <tr class="border-b border-gray-300">
                    <td class="text-center p-3 border-r border-gray-300">${index + 1}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.nama_barang)}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.spesifikasi || '-')}</td>
                    <td class="text-center p-3 border-r border-gray-300">${detail.pengajuan_jumlah}</td>
                    <td class="text-center p-3 border-r border-gray-300">${escapeHtml(satuanName)}</td>
                    <td class="p-3 border-r border-gray-300">${escapeHtml(detail.keperluan)}</td>
                    <td class="text-center p-3 border-r border-gray-300">
                        <input type="number" 
                               name="details[${detail.id}][disetujui_jumlah]" 
                               class="disetujui-jumlah w-20 px-2 py-1 border border-gray-300 rounded text-center"
                               value="${jumlahDisetujui}"
                               min="0"
                               max="${detail.pengajuan_jumlah}"
                               required>
                        <input type="hidden" name="details[${detail.id}][status]" class="detail-status" value="${jumlahDisetujui > 0 ? 'disetujui' : 'disesuaikan'}">
                        <input type="hidden" name="details[${detail.id}][satuan]" value="${escapeHtml(satuanName)}">
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
                <p class="text-gray-800 font-medium"><i class="fas fa-sticky-note mr-2"></i>Catatan Pemohon:</p>
                <p class="text-gray-600 mt-1">${escapeHtml(permintaan.catatan)}</p>
            </div>
        `;
    }
    
    content.innerHTML = html;
    
    // Tambahkan event listener untuk update status
    document.querySelectorAll('.disetujui-jumlah').forEach(input => {
        input.removeEventListener('input', handleJumlahChange);
        input.addEventListener('input', handleJumlahChange);
    });
}

function handleJumlahChange(e) {
    const row = this.closest('tr');
    const statusInput = row.querySelector('.detail-status');
    if (statusInput) {
        const jumlah = parseInt(this.value) || 0;
        statusInput.value = jumlah > 0 ? 'disetujui' : 'disesuaikan';
    }
}

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

function openRejectModal(permintaanId) {
    console.log('Opening reject modal for ID:', permintaanId);
    currentPermintaanId = permintaanId;
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
    form.action = `/admin/permintaan/${permintaanId}/reject`;
    
    if (textarea) {
        textarea.value = '';
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

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Handle form submission untuk approval
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
            }, 1500);
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

// Handle form submission untuk reject
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
            }, 1500);
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
document.getElementById('approvalModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeApprovalModal();
    }
});

document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApprovalModal();
        closeRejectModal();
    }
});
</script>
@endsection