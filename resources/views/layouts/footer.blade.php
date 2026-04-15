<footer class="bg-gray-900 text-white">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Logo & Description -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-boxes text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">SIPBHP</h2>
                        <p class="text-xs text-blue-300">Sistem Informasi Pengajuan Barang Habis Pakai</p>
                    </div>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Sistem untuk mengelola barang habis pakai secara efisien dan terintegrasi.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-blue-300">Menu Cepat</h3>
                <ul class="space-y-2">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Dashboard Admin</a></li>
                            <li><a href="{{ route('admin.manajemen-pengguna') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Manajemen Pengguna</a></li>
                            <li><a href="{{ route('admin.manajemen-barang') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Manajemen Barang</a></li>
                            <li><a href="{{ route('admin.laporan') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Laporan</a></li>
                        @else
                            <li><a href="{{ route('user.dashboard') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Dashboard</a></li>
                            <li><a href="{{ route('user.permintaan.create') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Permintaan Barang</a></li>
                            <li><a href="{{ route('user.permintaan.riwayat') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Riwayat Permintaan</a></li>
                        @endif
                        <li class="pt-2">
                            <a href="{{ Auth::user()->role === 'admin' ? route('admin.profile') : route('user.profile') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">
                                <i class="fas fa-user-circle mr-2"></i>Profil Saya
                            </a>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Login</a></li>
                        <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition-colors hover:translate-x-1 inline-block">Beranda</a></li>
                    @endauth
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-blue-300">Kontak Kami</h3>
                <ul class="space-y-3">
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-blue-400 mt-1"></i>
                        <span class="text-gray-400 text-sm">Jl. Raya Cisarua No. 123, Loby SMKN 1 Cisarua, Bandung Barat</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-phone text-blue-400"></i>
                        <span class="text-gray-400">(022) 1234-5678</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-whatsapp text-blue-400"></i>
                        <span class="text-gray-400">+62 812-3456-7890</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-blue-400"></i>
                        <a href="mailto:sibhp@gmail.com" class="text-gray-400 hover:text-white transition-colors">sibhp@gmail.com</a>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fas fa-clock text-blue-400"></i>
                        <span class="text-gray-400">Senin - Jumat, 08:00 - 16:00</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="border-t border-gray-800 mt-10 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <p class="text-gray-500 text-sm">
                        &copy; {{ date('Y') }} SIBHP - Sistem Informasi Barang Habis Pakai.
                    </p>
                    <p class="text-gray-600 text-xs mt-1">
                        Hak Cipta Dilindungi. All rights reserved.
                    </p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors">Bantuan</a>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-gray-600 text-xs">
                        Dibangun dengan 
                        <i class="fas fa-heart text-red-500 mx-1"></i> 
                        menggunakan Laravel & Tailwind CSS
                    </p>
                    <p class="text-gray-700 text-xs mt-1">
                        Version 1.0.0
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>