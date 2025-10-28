<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Internal')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
        
        <!-- Sidebar -->
        @if(Auth::user()->isSuperAdmin())
            @include('layouts.sidebar_superadmin')
        @else
            @include('layouts.sidebar_karyawan')
        @endif

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header -->
            @include('layouts.header')

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-teal-50 to-cyan-50 p-3 sm:p-4 md:p-6">
                
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-teal-50 border border-teal-200 text-teal-700 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-teal-500"></i>
                        <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-teal-600 hover:text-teal-800 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                        <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif

                <!-- Page Content -->
                @yield('content')
                
            </main>

        </div>

    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    
    <script>
        // Sidebar Toggle for Mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
        
        // Close sidebar when clicking a menu item on mobile
        document.querySelectorAll('#sidebar a').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });
        });
    </script>
    
    @stack('scripts')

</body>
</html>