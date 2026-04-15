@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-emerald-600 mr-3"></i>
            <p class="text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-600 mr-3 mt-0.5"></i>
            <div>
                <p class="text-red-700 font-medium mb-1">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sidebar Profil -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-user text-5xl text-white"></i>
                    </div>
                    <h3 class="mt-6 text-2xl font-bold text-gray-900">{{ Auth::user()->nama }}</h3>
                    <p class="text-gray-500 mt-1">{{ Auth::user()->role == 'admin' ? 'Administrator' : 'Pengguna' }}</p>
                    <div class="mt-4 flex items-center space-x-2">
                        <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Aktif</span>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 text-gray-600">
                            <i class="fas fa-envelope w-6 text-lg"></i>
                            <span class="text-base">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex items-center space-x-4 text-gray-600">
                            <i class="fas fa-user-tag w-6 text-lg"></i>
                            <span class="text-base">{{ Auth::user()->username }}</span>
                        </div>
                        <div class="flex items-center space-x-4 text-gray-600">
                            <i class="fas fa-id-card w-6 text-lg"></i>
                            <span class="text-base">{{ Auth::user()->nip }}</span>
                        </div>
                        <div class="flex items-center space-x-4 text-gray-600">
                            <i class="fas fa-building w-6 text-lg"></i>
                            <span class="text-base">{{ Auth::user()->bidang }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Bergabung</span>
                            <span class="font-medium">{{ Auth::user()->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Terakhir Update</span>
                            <span class="font-medium">{{ Auth::user()->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Profil -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
                <div class="border-b border-gray-200 px-8 py-6">
                    <h3 class="text-2xl font-bold text-gray-900">Informasi Pribadi</h3>
                    <p class="text-gray-500 mt-1">Perbarui informasi akun Anda</p>
                </div>
                
                <form action="{{ route('user.profile.update') }}" method="POST" class="p-8">
                    @csrf
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" 
                                       name="nama" 
                                       value="{{ old('nama', Auth::user()->nama) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                                <input type="text" 
                                       name="username" 
                                       value="{{ old('username', Auth::user()->username) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', Auth::user()->email) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                                       required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIP *</label>
                                <input type="text" 
                                       name="nip" 
                                       value="{{ old('nip', Auth::user()->nip) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                                       required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bidang/Divisi *</label>
                                <input type="text" 
                                       name="bidang" 
                                       value="{{ old('bidang', Auth::user()->bidang) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base"
                                       required>
                            </div>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-200">
                            <button type="submit" 
                                    class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition text-base">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection