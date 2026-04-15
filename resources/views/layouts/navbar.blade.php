<nav class="navbar text-white shadow-lg sticky top-0 z-50">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-4">
                <a href="{{ url('/') }}" class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">SIPBHP</h1>
                        <p class="text-xs text-blue-200">Sistem Informasi Pengajuan Barang Habis Pakai</p>
                    </div>
                </a>
            </div>
            
            <!-- User Info & Navigation -->
            <div class="flex items-center space-x-6">
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-2">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <!-- Admin Navigation -->
                            <a href="{{ route('admin.dashboard') }}" 
                               class="nav-link px-4 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                <span>Dashboard</span>
                            </a>
                            
                            <a href="{{ route('admin.manajemen-pengguna') }}" 
                               class="nav-link px-4 py-2 rounded-lg {{ request()->routeIs('admin.manajemen-pengguna') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-users mr-2"></i>
                                <span>Manajemen Pengguna</span>
                            </a>
                            
                            <a href="{{ route('admin.manajemen-barang') }}" 
                               class="nav-link px-4 py-2 rounded-lg {{ request()->routeIs('admin.manajemen-barang') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-boxes mr-2"></i>
                                <span>Manajemen Barang</span>
                            </a>
                            
                            <a href="{{ route('admin.laporan') }}" 
                               class="nav-link px-4 py-2 rounded-lg {{ request()->routeIs('admin.laporan') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-chart-bar mr-2"></i>
                                <span>Laporan</span>
                            </a>
                            
                        @else
                            <!-- User Navigation -->
                            <a href="{{ route('user.dashboard') }}" 
                               class="nav-link px-4 py-2 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                <span>Dashboard</span>
                            </a>
                            
                            <a href="{{ route('user.permintaan.create') }}" 
                               class="nav-link px-4 py-2 rounded-lg {{ request()->routeIs('user.permintaan.create') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-clipboard-list mr-2"></i>
                                <span>Permintaan Barang</span>
                            </a>
                            
                            <a href="{{ route('user.permintaan.riwayat') }}" 
                               class="nav-link px-4 py-2 rounded-lg {{ request()->routeIs('user.permintaan.riwayat') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                                <i class="fas fa-history mr-2"></i>
                                <span>Riwayat</span>
                            </a>
                        @endif
                        
                        <!-- Common Navigation -->
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.profile') : route('user.profile') }}" 
                           class="nav-link px-4 py-2 rounded-lg {{ request()->routeIs('*.profile') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-user-cog mr-2"></i>
                            <span>Profil</span>
                        </a>
                    @endauth
                </div>
                
                <!-- Auth Section -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- User Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center space-x-3 bg-white/10 px-4 py-2 rounded-lg hover:bg-white/15 transition">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="text-left hidden md:block">
                                    <p class="font-semibold text-sm">{{ Auth::user()->nama }}</p>
                                    <p class="text-xs text-blue-200">{{ Auth::user()->bidang }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-blue-200"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-56 dropdown-menu text-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-10">
                                <div class="p-4 border-b border-gray-700">
                                    <p class="font-semibold">{{ Auth::user()->nama }}</p>
                                    <p class="text-sm text-blue-200">{{ Auth::user()->email }}</p>
                                    <p class="text-xs text-gray-400">{{ Auth::user()->nip }}</p>
                                    <span class="inline-block mt-1 px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded">
                                        {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Pengguna' }}
                                    </span>
                                </div>
                                <div class="py-2">
                                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.profile') : route('user.profile') }}" 
                                       class="flex items-center space-x-3 px-4 py-3 hover:bg-white/5">
                                        <i class="fas fa-user-edit text-blue-300 w-5"></i>
                                        <span>Edit Profil</span>
                                    </a>
                                    <div class="border-t border-gray-700 my-2"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center space-x-3 px-4 py-3 hover:bg-red-500/10 hover:text-red-200 w-full text-left">
                                            <i class="fas fa-sign-out-alt text-red-300 w-5"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Login Button for Guests -->
                        <a href="{{ route('login') }}" 
                           class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-blue-700 transition">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span>Login</span>
                        </a>
                    @endauth
                    
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-button" class="md:hidden text-blue-200 hover:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden mt-4 hidden bg-gray-900/50 rounded-lg">
            <div class="space-y-1 p-2">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <!-- Admin Mobile Navigation -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-blue-300"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('admin.manajemen-pengguna') }}" 
                           class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.manajemen-pengguna') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-users w-5 text-blue-300"></i>
                            <span>Manajemen Pengguna</span>
                        </a>
                        
                        <a href="{{ route('admin.manajemen-barang') }}" 
                           class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.manajemen-barang') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-boxes w-5 text-blue-300"></i>
                            <span>Manajemen Barang</span>
                        </a>
                        
                        <a href="{{ route('admin.laporan') }}" 
                           class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('admin.laporan') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-chart-bar w-5 text-blue-300"></i>
                            <span>Laporan</span>
                        </a>
                        
                    @else
                        <!-- User Mobile Navigation -->
                        <a href="{{ route('user.dashboard') }}" 
                           class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-blue-300"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('user.permintaan.create') }}" 
                           class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('user.permintaan.create') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-clipboard-list w-5 text-blue-300"></i>
                            <span>Permintaan Barang</span>
                        </a>
                        
                        <a href="{{ route('user.permintaan.riwayat') }}" 
                           class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('user.permintaan.riwayat') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                            <i class="fas fa-history w-5 text-blue-300"></i>
                            <span>Riwayat</span>
                        </a>
                    @endif
                    
                    <!-- Common Mobile Navigation -->
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.profile') : route('user.profile') }}" 
                       class="nav-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('*.profile') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                        <i class="fas fa-user-cog w-5 text-blue-300"></i>
                        <span>Profil</span>
                    </a>
                    
                    <div class="border-t border-gray-700 my-2"></div>
                    
                    <div class="px-3 py-2">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">{{ Auth::user()->nama }}</p>
                                <p class="text-xs text-blue-200">{{ Auth::user()->bidang }}</p>
                                <span class="inline-block mt-1 px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded">
                                    {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Pengguna' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Guest Mobile Navigation -->
                    <a href="{{ route('login') }}" 
                       class="nav-link flex items-center space-x-3 p-3 rounded-lg">
                        <i class="fas fa-sign-in-alt w-5 text-blue-300"></i>
                        <span>Login</span>
                    </a>
                    
                    <a href="{{ url('/') }}" 
                       class="nav-link flex items-center space-x-3 p-3 rounded-lg">
                        <i class="fas fa-home w-5 text-blue-300"></i>
                        <span>Beranda</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>