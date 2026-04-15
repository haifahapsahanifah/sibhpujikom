{{-- resources/views/user/permintaan/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Surat Permintaan Barang')

@section('header')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center">
    <div class="mb-4 md:mb-0">
        <h2 class="text-2xl font-bold text-gray-900">Surat Permintaan Barang</h2>
        <p class="text-gray-600">Formulir permintaan barang resmi</p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="{{ route('user.permintaan.riwayat') }}" 
           class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Riwayat</span>
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">FORMULIR PERMINTAAN BARANG</h3>
                <p class="text-gray-600 text-sm">Sistem Informasi Barang Habis Pakai</p>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <div class="text-sm text-gray-500">Tanggal: {{ date('d F Y') }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('user.permintaan.store') }}" method="POST" id="suratPermintaanForm">
            @csrf
            
            <div class="p-6 border-b border-gray-200">
                <h4 class="text-md font-bold text-gray-900 mb-4">INFORMASI PENGAJU</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengaju</label>
                        <input type="text" 
                               value="{{ Auth::user()->nama }}"
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg"
                               readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Divisi/Bagian</label>
                        <input type="text" 
                               name="divisi"
                               value="{{ Auth::user()->bidang }}"
                               class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg"
                               readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibutuhkan</label>
                        <input type="date" 
                               name="tanggal_dibutuhkan"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               required>
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-gray-200">
                <h4 class="text-md font-bold text-gray-900 mb-4">DETAIL BARANG YANG DIMINTA</h4>
                
                <div class="overflow-x-auto border border-gray-300 rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr class="border-b border-gray-300">
                                <th class="text-center p-3 border-r border-gray-300 w-12">No.</th>
                                <th class="text-center p-3 border-r border-gray-300">Barang</th>
                                <th class="text-center p-3 w-32 border-r border-gray-300">Jumlah</th>
                                <th class="text-center p-3 border-r border-gray-300">Keperluan</th>
                                <th class="text-center p-3 w-16">Aksi</th>
                             </tr>
                        </thead>
                        <tbody id="barangTable">
                            <tr class="border-b border-gray-300">
                                <td class="text-center p-3 border-r border-gray-300">1</td>
                                <td class="p-2 border-r border-gray-300">
                                    <select class="pilih-barang w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                            name="barang[0][barang_id]"
                                            required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($barangMasuk as $item)
                                            <option value="{{ $item->barang_id }}" 
                                                    data-kode="{{ $item->kode_barang }}"
                                                    data-nama="{{ $item->nama_barang }}"
                                                    data-satuan="{{ $item->satuan_nama ?? ($item->barang->satuan->name ?? 'pcs') }}"
                                                    data-stok="{{ $item->sisa_stok }}">
                                                {{ $item->nama_barang }} (Sisa Stok: {{ $item->sisa_stok }} {{ $item->satuan_nama ?? ($item->barang->satuan->name ?? 'pcs') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="barang[0][kode]" class="kode-barang">
                                    <input type="hidden" name="barang[0][nama]" class="nama-barang">
                                </td>
                                <td class="p-2 border-r border-gray-300">
                                    <input type="number" 
                                           name="barang[0][pengajuan_jumlah]" 
                                           class="w-24 px-2 py-1 border border-gray-300 rounded text-center"
                                           min="1"
                                           value="1"
                                           required>
                                </td>
                                <td class="p-2 border-r border-gray-300">
                                    <textarea name="barang[0][keperluan]"  
                                              class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                                              rows="2"
                                              placeholder="Keperluan penggunaan"
                                              required></textarea>
                                </td>
                                <td class="p-2 text-center">
                                    <button type="button" 
                                            onclick="hapusBarang(this)"
                                            class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 flex justify-between items-center">
                    <button type="button" 
                            onclick="tambahBarang()"
                            class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Barang</span>
                    </button>
                    
                    <div class="text-sm text-gray-600">
                        Total Barang: <span id="totalBarang">1</span>
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="persetujuan_pengaju" 
                           id="persetujuan_pengaju"
                           class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" 
                           required>
                    <label for="persetujuan_pengaju" class="ml-2 text-sm text-gray-700">
                        Saya sebagai pengaju menyatakan bahwa barang-barang tersebut diatas benar-benar diperlukan
                    </label>
                </div>
            </div>

            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="catatan" 
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Catatan atau penjelasan tambahan mengenai permintaan ini..."></textarea>
                </div>
                
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <p><i class="fas fa-info-circle mr-1"></i> Pastikan semua data telah terisi dengan benar</p>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="resetForm()"
                                class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            Reset
                        </button>
                        <button type="submit" 
                                id="submitBtn"
                                class="px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition flex items-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Kirim Surat Permintaan</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let barangCount = 1;
    
    // Data barang dari PHP
    @php
        $barangDataMap = [];
        foreach($barangMasuk as $item) {
            $barangDataMap[$item->barang_id] = [
                'kode' => $item->kode_barang,
                'nama' => $item->nama_barang,
                'satuan' => $item->satuan_nama ?? ($item->barang->satuan->name ?? 'pcs'),
                'stok' => $item->sisa_stok
            ];
        }
    @endphp
    const barangData = @json($barangDataMap);
    
    // Fungsi untuk memilih barang
    window.pilihBarang = function(selectElement) {
        const row = selectElement.closest('tr');
        const barangId = selectElement.value;
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        
        if (barangId && barangData[barangId]) {
            row.querySelector('.kode-barang').value = barangData[barangId].kode;
            row.querySelector('.nama-barang').value = barangData[barangId].nama;
            
            // Set max quantity based on stock
            const jumlahInput = row.querySelector('input[name$="[pengajuan_jumlah]"]');
            jumlahInput.max = barangData[barangId].stok;
            if (parseInt(jumlahInput.value) > barangData[barangId].stok) {
                jumlahInput.value = barangData[barangId].stok;
            }
        } else {
            row.querySelector('.kode-barang').value = '';
            row.querySelector('.nama-barang').value = '';
            row.querySelector('input[name$="[pengajuan_jumlah]"]').max = '';
        }
    };
    
    // Fungsi untuk menambah barang
    window.tambahBarang = function() {
        barangCount++;
        
        const newRow = document.createElement('tr');
        newRow.className = 'border-b border-gray-300';
        
        // Use the existing options to prevent rendering issues
        const selectHTML = document.querySelector('.pilih-barang').innerHTML;
        
        newRow.innerHTML = `
            <td class="text-center p-3 border-r border-gray-300">${barangCount}</td>
            <td class="p-2 border-r border-gray-300">
                <select class="pilih-barang w-full px-2 py-1 border border-gray-300 rounded text-sm"
                        name="barang[${barangCount-1}][barang_id]"
                        required>
                    ${selectHTML}
                </select>
                <input type="hidden" name="barang[${barangCount-1}][kode]" class="kode-barang">
                <input type="hidden" name="barang[${barangCount-1}][nama]" class="nama-barang">
            </td>
            <td class="p-2 border-r border-gray-300">
                <input type="number" 
                       name="barang[${barangCount-1}][pengajuan_jumlah]" 
                       class="w-24 px-2 py-1 border border-gray-300 rounded text-center"
                       min="1"
                       value="1"
                       required>
            </td>
            <td class="p-2 border-r border-gray-300">
                <textarea name="barang[${barangCount-1}][keperluan]"  
                          class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                          rows="2"
                          placeholder="Keperluan penggunaan"
                          required></textarea>
            </td>
            <td class="p-2 text-center">
                <button type="button" 
                        onclick="hapusBarang(this)"
                        class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        document.getElementById('barangTable').appendChild(newRow);
        
        // Attach event listener ke select baru
        const newSelect = newRow.querySelector('.pilih-barang');
        newSelect.addEventListener('change', function() {
            pilihBarang(this);
        });
        
        document.getElementById('totalBarang').textContent = barangCount;
    };
    
    // Fungsi untuk menghapus barang
    window.hapusBarang = function(button) {
        const row = button.closest('tr');
        if (barangCount > 1) {
            row.remove();
            barangCount--;
            updateNomorUrut();
            document.getElementById('totalBarang').textContent = barangCount;
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Minimal harus ada 1 barang dalam permintaan'
            });
        }
    };
    
    // Fungsi untuk update nomor urut
    function updateNomorUrut() {
        const rows = document.querySelectorAll('#barangTable tr');
        rows.forEach((row, index) => {
            const firstCell = row.querySelector('td:first-child');
            if (firstCell) {
                firstCell.textContent = index + 1;
            }
        });
    }
    
    // Fungsi untuk reset form
    window.resetForm = function() {
        Swal.fire({
            title: 'Reset Form?',
            text: 'Semua data yang telah diisi akan hilang',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('suratPermintaanForm').reset();
                
                // Reset tabel ke 1 baris
                const tbody = document.getElementById('barangTable');
                const rows = tbody.querySelectorAll('tr');
                for (let i = 1; i < rows.length; i++) {
                    rows[i].remove();
                }
                barangCount = 1;
                
                // Reset baris pertama
                const firstRow = tbody.querySelector('tr');
                if (firstRow) {
                    const firstSelect = firstRow.querySelector('.pilih-barang');
                    if (firstSelect) firstSelect.value = '';
                    firstRow.querySelector('.kode-barang').value = '';
                    firstRow.querySelector('.nama-barang').value = '';
                    firstRow.querySelector('input[name$="[pengajuan_jumlah]"]').value = '1';
                    firstRow.querySelector('textarea[name$="[keperluan]"]').value = '';
                }
                
                document.getElementById('totalBarang').textContent = '1';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Form telah direset',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    };
    
    // Attach event listeners ke select yang sudah ada
    document.querySelectorAll('.pilih-barang').forEach(select => {
        select.addEventListener('change', function() {
            pilihBarang(this);
        });
        // Trigger untuk baris pertama
        if (select.value) {
            pilihBarang(select);
        }
    });
    
    // Form submit handler dengan AJAX
    const form = document.getElementById('suratPermintaanForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi checkbox
        const checkbox = document.getElementById('persetujuan_pengaju');
        if (!checkbox.checked) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Anda harus menyetujui pernyataan pengajuan'
            });
            return;
        }
        
        // Validasi minimal satu barang
        const rows = document.querySelectorAll('#barangTable tr');
        if (rows.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Minimal harus ada 1 barang yang diminta'
            });
            return;
        }
        
        // Validasi semua field terisi
        let isValid = true;
        let errorMessage = '';
        
        rows.forEach((row, index) => {
            const select = row.querySelector('.pilih-barang');
            const jumlah = row.querySelector('input[name$="[pengajuan_jumlah]"]');
            const keperluan = row.querySelector('textarea[name$="[keperluan]"]');
            
            if (!select.value) {
                isValid = false;
                errorMessage = `Barang ke-${index + 1}: Pilih barang terlebih dahulu`;
                return;
            }
            
            if (!jumlah.value || parseInt(jumlah.value) < 1) {
                isValid = false;
                errorMessage = `Barang ke-${index + 1}: Jumlah harus diisi minimal 1`;
                return;
            }
            
            if (parseInt(jumlah.value) > parseInt(jumlah.max)) {
                isValid = false;
                errorMessage = `Barang ke-${index + 1}: Stok tidak mencukupi. Sisa stok hanya ${jumlah.max}`;
                return;
            }
            
            if (!keperluan.value.trim()) {
                isValid = false;
                errorMessage = `Barang ke-${index + 1}: Keperluan harus diisi`;
                return;
            }
        });
        
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: errorMessage
            });
            return;
        }
        
        // Tampilkan loading
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        
        // Submit form dengan AJAX
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Surat permintaan berhasil dikirim',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.href = data.redirect_url || '{{ route("user.permintaan.riwayat") }}';
                });
            } else {
                let errorText = data.message || 'Terjadi kesalahan';
                if (data.errors) {
                    errorText = Object.values(data.errors).flat().join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorText,
                    confirmButtonColor: '#d33'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                confirmButtonColor: '#d33'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
});
</script>
@endsection