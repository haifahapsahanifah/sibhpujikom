<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIBHP') - Sistem Informasi Barang Habis Pakai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .navbar {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        
        .nav-link {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(59, 130, 246, 0.1);
        }
        
        .stat-card {
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
        }
        
        .dropdown-menu {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #334155;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Include Navbar -->
    @include('layouts.navbar')
    
    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Page Header -->
        @hasSection('header')
            <header class="bg-white shadow-sm border-b px-6 py-4">
                @yield('header')
            </header>
        @endif
        
        <!-- Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>
    
    <!-- Include Footer -->
    @include('layouts.footer')
    
    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
                
                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>