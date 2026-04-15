{{-- resources/views/admin/settings.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('page-subtitle', 'Konfigurasi sistem aplikasi')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
    @csrf
    
    <!-- Pengaturan Umum -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Pengaturan Identitas & Instansi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Instansi</label>
                <input type="text" name="settings[instansi_nama]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['instansi_nama']->value ?? 'BPS Provinsi X' }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Instansi</label>
                <input type="text" name="settings[instansi_alamat]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['instansi_alamat']->value ?? 'Jl. Contoh No 123' }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                <input type="text" name="settings[instansi_website]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['instansi_website']->value ?? 'www.bps.go.id' }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telp</label>
                <input type="text" name="settings[instansi_telp]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['instansi_telp']->value ?? '(021) 123456' }}">
            </div>
        </div>
    </div>

    <!-- Pengaturan Pejabat Penandatangan Cetakan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Pejabat & Penandatangan Cetakan Laporan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Pimpinan (Kiri)</label>
                <input type="text" name="settings[ttd_pimpinan_jabatan]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['ttd_pimpinan_jabatan']->value ?? 'Kepala BPS' }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pimpinan</label>
                <input type="text" name="settings[ttd_pimpinan_nama]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['ttd_pimpinan_nama']->value ?? 'Budi Santoso, S.ST., M.Si' }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIP Pimpinan</label>
                <input type="text" name="settings[ttd_pimpinan_nip]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['ttd_pimpinan_nip']->value ?? '19800101 200501 1 001' }}">
            </div>
            <div class="md:col-span-2 border-t pt-4"></div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan Petugas (Kanan)</label>
                <input type="text" name="settings[ttd_petugas_jabatan]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['ttd_petugas_jabatan']->value ?? 'Petugas Gudang' }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Petugas Gudang</label>
                <input type="text" name="settings[ttd_petugas_nama]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['ttd_petugas_nama']->value ?? 'Dwi Mutiara' }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIP Petugas</label>
                <input type="text" name="settings[ttd_petugas_nip]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500" value="{{ $settings['ttd_petugas_nip']->value ?? '19950201 201903 2 004' }}">
            </div>
        </div>
    </div>

    <!-- Pengaturan Aplikasi Khusus Gudang -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Preferensi Gudang & Stok</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-900">Notifikasi Permintaan via Email</p>
                    <p class="text-sm text-gray-500">Kirim email ke admin bila ada permintaan baru</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="settings[email_notification]" value="0">
                    <input type="checkbox" name="settings[email_notification]" value="1" class="sr-only peer" {{ ($settings['email_notification']->value ?? '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-900">Peringatan Defisit Barang</p>
                    <p class="text-sm text-gray-500">Tolak otomatis jika jumlah disetujui melebihi sisa stok fisik</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="settings[strict_stock]" value="0">
                    <input type="checkbox" name="settings[strict_stock]" value="1" class="sr-only peer" {{ ($settings['strict_stock']->value ?? '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Tombol Simpan -->
    <div class="flex justify-end">
        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 shadow-md flex items-center">
            <i class="fas fa-save mr-2"></i> Simpan Pengaturan
        </button>
    </div>
</form>
@endsection