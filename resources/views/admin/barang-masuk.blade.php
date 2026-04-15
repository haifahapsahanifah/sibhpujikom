{{-- resources/views/admin/barang-masuk.blade.php --}}
@extends('layouts.admin')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')
@section('page-subtitle', 'Catat barang masuk ke gudang')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Barang Masuk -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Tambah Barang Masuk</h3>
            
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form action="{{ route('admin.barang-masuk.store') }}" method="POST" id="formBarangMasuk">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_masuk" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 @error('tanggal_masuk') border-red-500 @enderror" 
                               value="{{ old('tanggal_masuk', date('Y-m-d')) }}" 
                               required>
                        @error('tanggal_masuk')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomor_dokumen" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 @error('nomor_dokumen') border-red-500 @enderror" 
                               placeholder="INV-0001" 
                               value="{{ old('nomor_dokumen') }}"
                               required>
                        @error('nomor_dokumen')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Supplier <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_supplier" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 @error('nama_supplier') border-red-500 @enderror" 
                               placeholder="CV. Deaz Panca Karya" 
                               value="{{ old('nama_supplier') }}"
                               required>
                        @error('nama_supplier')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Barang <span class="text-red-500">*</span>
                        </label>
                        <select name="barang_id" id="barang_id" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 @error('barang_id') border-red-500 @enderror" 
                                required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" 
                                        data-satuan="{{ $barang->satuan->name ?? '-' }}" 
                                        data-kode="{{ $barang->kode_barang }}" 
                                        data-harga="{{ $barang->harga_satuan }}"
                                        data-spesifikasi="{{ $barang->description }}"
                                        {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            NUSP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nusp" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 @error('nusp') border-red-500 @enderror" 
                               placeholder="421/084 A/SMAN1BTR/CADISDIKWIL.VI/2024" 
                               value="{{ old('nusp') }}"
                               required>
                        @error('nusp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Spesifikasi Nama Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="spesifikasi_nama_barang" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 @error('spesifikasi_nama_barang') border-red-500 @enderror" 
                               placeholder="Standar" 
                               value="{{ old('spesifikasi_nama_barang') }}"
                               required>
                        @error('spesifikasi_nama_barang')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah" id="jumlah" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 @error('jumlah') border-red-500 @enderror" 
                               placeholder="Masukkan jumlah" 
                               value="{{ old('jumlah') }}"
                               required min="1">
                        @error('jumlah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                        <input type="text" id="satuan_tampil" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-50" 
                               readonly>
                        <input type="hidden" name="satuan_id" id="satuan_id">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Satuan (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="harga_satuan" id="harga_satuan" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 @error('harga_satuan') border-red-500 @enderror" 
                               placeholder="0" 
                               value="{{ old('harga_satuan') }}"
                               required min="0" step="1000">
                        @error('harga_satuan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Total (Rp)</label>
                        <input type="text" id="nilai_total_tampil" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-50" 
                               readonly>
                        <input type="hidden" name="nilai_total" id="nilai_total">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" 
                                  placeholder="Catatan... (Opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200">
                        Simpan Barang Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Riwayat Barang Masuk -->
    <div class="lg:col-span-2">
        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="col-span-full md:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="filter_search" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Cari Nomor Dokumen, Nama Barang, Supplier..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select id="filter_bulan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ isset($filters['bulan']) && $filters['bulan'] == $i ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select id="filter_tahun" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Semua Tahun</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ isset($filters['tahun']) && $filters['tahun'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Awal</label>
                    <input type="date" id="tanggal_awal" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" value="{{ $filters['tanggal_awal'] ?? '' }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" id="tanggal_akhir" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" value="{{ $filters['tanggal_akhir'] ?? '' }}">
                </div>
                <div class="col-span-full flex gap-2">
                    <button type="button" onclick="applyFilter()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200">
                        <i class="fas fa-search mr-2"></i> Terapkan Filter
                    </button>
                    <button type="button" onclick="printTable()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200">
                        <i class="fas fa-print mr-2"></i> Cetak Laporan
                    </button>
                    <button type="button" onclick="resetFilter()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200">
                        <i class="fas fa-undo-alt mr-2"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Barang Masuk -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Barang Masuk</h3>
                        <p class="text-sm text-gray-500 mt-1">Total {{ number_format($totalBarangMasuk) }} barang masuk, nilai total Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full min-w-[1200px]" id="data-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Dokumen</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Supplier</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NUSP</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spesifikasi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Satuan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($barangMasuks as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $index + $barangMasuks->firstItem() }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ date('d/m/Y', strtotime($item->tanggal_masuk)) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nomor_dokumen ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nama_supplier ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->kode_barang }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->nusp ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->spesifikasi_nama_barang ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($item->jumlah) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->satuan_nama }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-green-600">Rp {{ number_format($item->nilai_total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <button onclick="deleteBarangMasuk({{ $item->id }})" class="text-red-600 hover:text-red-800 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-2 block"></i>
                                Belum ada data barang masuk
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if(method_exists($barangMasuks, 'links'))
                <div class="mt-6">
                    {{ $barangMasuks->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@push('scripts')
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

// Print function
window.printTable = function() {
    const search = document.getElementById('filter_search')?.value || '';
    const bulan = document.getElementById('filter_bulan')?.value || '';
    const tahun = document.getElementById('filter_tahun')?.value || '';
    const tanggalAwal = document.getElementById('tanggal_awal')?.value || '';
    const tanggalAkhir = document.getElementById('tanggal_akhir')?.value || '';
    
    let url = '{{ route("admin.barang-masuk.print") }}';
    let params = [];
    
    if (search) params.push(`search=${encodeURIComponent(search)}`);
    if (bulan) params.push(`bulan=${bulan}`);
    if (tahun) params.push(`tahun=${tahun}`);
    if (tanggalAwal) params.push(`tanggal_awal=${tanggalAwal}`);
    if (tanggalAkhir) params.push(`tanggal_akhir=${tanggalAkhir}`);
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    window.open(url, '_blank');
};

// Main initialization
(function() {
    'use strict';
    
    if (window.barangMasukInitialized) return;
    window.barangMasukInitialized = true;
    
    const jumlahInput = document.querySelector('#jumlah');
    const hargaSatuanInput = document.querySelector('#harga_satuan');
    const nilaiTotalTampil = document.getElementById('nilai_total_tampil');
    const nilaiTotalHidden = document.getElementById('nilai_total');
    const barangSelect = document.querySelector('#barang_id');
    const satuanTampil = document.getElementById('satuan_tampil');
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function calculateTotal() {
        let jumlah = parseFloat(jumlahInput ? jumlahInput.value : 0) || 0;
        let harga = parseFloat(hargaSatuanInput ? hargaSatuanInput.value : 0) || 0;
        let total = jumlah * harga;
        
        if (nilaiTotalTampil) {
            nilaiTotalTampil.value = 'Rp ' + formatNumber(Math.floor(total));
        }
        if (nilaiTotalHidden) {
            nilaiTotalHidden.value = total;
        }
        
        return total;
    }
    
    if (barangSelect) {
        barangSelect.addEventListener('change', function() {
            let selected = this.options[this.selectedIndex];
            let satuan = selected.getAttribute('data-satuan');
            let harga = selected.getAttribute('data-harga');
            let spesifikasi = selected.getAttribute('data-spesifikasi');
            
            if (satuanTampil) {
                satuanTampil.value = satuan || '';
            }
            
            let spesifikasiInput = document.querySelector('input[name="spesifikasi_nama_barang"]');
            if (spesifikasiInput && spesifikasi) {
                spesifikasiInput.value = spesifikasi;
            }
            
            if (harga && parseFloat(harga) > 0 && hargaSatuanInput) {
                hargaSatuanInput.value = harga;
                calculateTotal();
            }
        });
    }
    
    if (jumlahInput) {
        jumlahInput.addEventListener('input', calculateTotal);
    }
    
    if (hargaSatuanInput) {
        hargaSatuanInput.addEventListener('input', calculateTotal);
    }
    
    calculateTotal();
    
    const form = document.getElementById('formBarangMasuk');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = [
                'tanggal_masuk', 'nomor_dokumen', 'nama_supplier', 
                'barang_id', 'nusp', 'spesifikasi_nama_barang', 
                'jumlah', 'harga_satuan'
            ];
            
            let isValid = true;
            let errorMessages = [];
            
            requiredFields.forEach(field => {
                const element = this.querySelector(`[name="${field}"]`);
                if (element && !element.value.trim()) {
                    isValid = false;
                    const label = element.closest('div')?.querySelector('label')?.innerText || field;
                    errorMessages.push(`${label} wajib diisi`);
                    element.classList.add('border-red-500');
                } else if (element) {
                    element.classList.remove('border-red-500');
                }
            });
            
            const jumlahElement = this.querySelector('[name="jumlah"]');
            if (jumlahElement && parseInt(jumlahElement.value) < 1) {
                isValid = false;
                errorMessages.push('Jumlah minimal 1');
                jumlahElement.classList.add('border-red-500');
            }
            
            const hargaElement = this.querySelector('[name="harga_satuan"]');
            if (hargaElement && parseFloat(hargaElement.value) < 0) {
                isValid = false;
                errorMessages.push('Harga satuan tidak boleh negatif');
                hargaElement.classList.add('border-red-500');
            }
            
            if (!isValid) {
                e.preventDefault();
                window.showToast('error', errorMessages.join('\n'));
                return false;
            }
            
            e.preventDefault();
            calculateTotal();
            
            let formData = new FormData(this);
            
            if (formData.has('nilai_total')) {
                formData.delete('nilai_total');
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                    let errorMessage = '';
                    if (data.errors) {
                        Object.values(data.errors).forEach(error => {
                            errorMessage += error[0] + '\n';
                        });
                    } else {
                        errorMessage = data.message || 'Terjadi kesalahan';
                    }
                    window.showToast('error', errorMessage);
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
    }
    
    window.deleteBarangMasuk = function(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            window.showToast('info', 'Menghapus data...');
            
            fetch(`/admin/barang-masuk/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
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
                    window.showToast('error', data.message || 'Gagal menghapus data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.showToast('error', 'Terjadi kesalahan pada server');
            });
        }
    };
    
    window.applyFilter = function() {
        const search = document.getElementById('filter_search')?.value || '';
        const bulan = document.getElementById('filter_bulan')?.value || '';
        const tahun = document.getElementById('filter_tahun')?.value || '';
        const tanggalAwal = document.getElementById('tanggal_awal')?.value || '';
        const tanggalAkhir = document.getElementById('tanggal_akhir')?.value || '';
        
        let url = new URL(window.location.href);
        
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (bulan) url.searchParams.set('bulan', bulan);
        else url.searchParams.delete('bulan');
        
        if (tahun) url.searchParams.set('tahun', tahun);
        else url.searchParams.delete('tahun');
        
        if (tanggalAwal) url.searchParams.set('tanggal_awal', tanggalAwal);
        else url.searchParams.delete('tanggal_awal');
        
        if (tanggalAkhir) url.searchParams.set('tanggal_akhir', tanggalAkhir);
        else url.searchParams.delete('tanggal_akhir');
        
        window.location.href = url.toString();
    };
    
    window.resetFilter = function() {
        const searchInput = document.getElementById('filter_search');
        const bulanSelect = document.getElementById('filter_bulan');
        const tahunSelect = document.getElementById('filter_tahun');
        const tanggalAwalInput = document.getElementById('tanggal_awal');
        const tanggalAkhirInput = document.getElementById('tanggal_akhir');
        
        if (searchInput) searchInput.value = '';
        if (bulanSelect) bulanSelect.value = '';
        if (tahunSelect) tahunSelect.value = '';
        if (tanggalAwalInput) tanggalAwalInput.value = '';
        if (tanggalAkhirInput) tanggalAkhirInput.value = '';
        
        window.showToast('info', 'Filter telah direset');
        
        let url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.delete('bulan');
        url.searchParams.delete('tahun');
        url.searchParams.delete('tanggal_awal');
        url.searchParams.delete('tanggal_akhir');
        window.location.href = url.toString();
    };
    
    const tanggalAwalInput = document.getElementById('tanggal_awal');
    const tanggalAkhirInput = document.getElementById('tanggal_akhir');
    
    if (tanggalAwalInput) {
        tanggalAwalInput.addEventListener('change', function() {
            if (tanggalAwalInput.value && tanggalAkhirInput && tanggalAkhirInput.value && 
                tanggalAwalInput.value > tanggalAkhirInput.value) {
                window.showToast('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                tanggalAwalInput.value = '';
            }
        });
    }
    
    if (tanggalAkhirInput) {
        tanggalAkhirInput.addEventListener('change', function() {
            if (tanggalAwalInput && tanggalAwalInput.value && tanggalAkhirInput.value && 
                tanggalAwalInput.value > tanggalAkhirInput.value) {
                window.showToast('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                tanggalAkhirInput.value = '';
            }
        });
    }
})();
</script>
@endpush
@endsection