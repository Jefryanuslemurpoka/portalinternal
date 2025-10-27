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
    
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-teal-50 to-cyan-50">
    
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
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-teal-50 to-cyan-50 p-6">
                
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 bg-teal-50 border border-teal-200 text-teal-700 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-teal-500"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-teal-600 hover:text-teal-800 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
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
    
    @stack('scripts')

</body>
</html>