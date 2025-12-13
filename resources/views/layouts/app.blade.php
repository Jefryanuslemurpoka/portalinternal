<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Meta Robots: Prevent indexing for superadmin pages --}}
    @if(Request::is('superadmin/*'))
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex, nofollow">
    @endif
    
    <title>@yield('title', 'Portal Internal')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    <style>
        /* Responsive Sidebar */
        #sidebar {
            transition: transform 0.3s ease-in-out;
        }
        
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 40;
                transform: translateX(-100%);
            }
            
            #sidebar.active {
                transform: translateX(0);
            }
            
            #sidebarOverlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 30;
            }
            
            #sidebarOverlay.active {
                display: block;
            }
        }
        
        @media (min-width: 1024px) {
            #sidebar {
                position: static;
                transform: translateX(0) !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-teal-50 to-cyan-50">
    
    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay"></div>
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Dinamis Berdasarkan Role & Divisi -->
        @if(Auth::check())
            @if(Auth::user()->isSuperAdmin())
                @include('layouts.sidebar_superadmin')
            @elseif(Auth::user()->isKaryawan())
                @if(strtoupper(Auth::user()->divisi) === 'FINANCE')
                    @include('layouts.sidebar_finance')
                @else
                    @include('layouts.sidebar_karyawan')
                @endif
            @endif
        @endif

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header -->
            @include('layouts.header')

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-teal-50 to-cyan-50 p-3 sm:p-4 md:p-6">

                <!-- Page Content -->
                @yield('content')
                
            </main>

        </div>

    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Sidebar Toggle for Mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            }
            
            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
            
            // Close sidebar when clicking a menu item on mobile
            if (sidebar) {
                const links = sidebar.querySelectorAll('a');
                links.forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 1024) {
                            sidebar.classList.remove('active');
                            overlay.classList.remove('active');
                        }
                    });
                });
            }
        });
    </script>

    <!-- Flash Messages Handler -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#10b981',
            timer: 3000,
            timerProgressBar: true,
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#ef4444',
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: '<ul class="text-left text-sm">' +
                @foreach($errors->all() as $error)
                    '<li class="mb-1">• {{ $error }}</li>' +
                @endforeach
                '</ul>',
            confirmButtonColor: '#ef4444',
        });
    </script>
    @endif

    @stack('scripts')

</body>
</html>