<aside id="sidebar" class="bg-gradient-to-b from-teal-600 to-cyan-700 text-white w-64 flex-shrink-0 overflow-y-auto flex flex-col">
    
    <!-- Logo -->
    <div class="p-4 sm:p-6 border-b border-teal-500/30">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center shadow-md flex-shrink-0 p-1">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div class="min-w-0">
                <h2 class="text-lg sm:text-xl font-bold truncate">Portal Internal</h2>
                <p class="text-xs text-teal-100 truncate">Super Admin Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 p-3 sm:p-4 space-y-1 sm:space-y-2">
        
        <!-- Dashboard -->
        <a href="{{ route('superadmin.dashboard') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.dashboard') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-home text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Dashboard</span>
        </a>

        <!-- Manajemen Karyawan -->
        <a href="{{ route('superadmin.karyawan.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.karyawan.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-users text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Manajemen Karyawan</span>
        </a>

        <!-- Manajemen Absensi -->
        <a href="{{ route('superadmin.absensi.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.absensi.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-clipboard-check text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Manajemen Absensi</span>
        </a>

        <!-- Persetujuan Cuti/Izin -->
        <a href="{{ route('superadmin.cutiizin.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.cutiizin.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-calendar-check text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Persetujuan Cuti/Izin</span>
        </a>

        <!-- Log Book Surat -->
        <a href="{{ route('superadmin.surat.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.surat.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-envelope text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Log Book Surat</span>
        </a>

        <!-- Log Book Server -->
        <a href="{{ route('superadmin.serverlog.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.serverlog.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-server text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Log Book Server</span>
        </a>

        <!-- Pengumuman -->
        <a href="{{ route('superadmin.pengumuman.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.pengumuman.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-bullhorn text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Pengumuman</span>
        </a>

        <!-- Laporan -->
        <a href="{{ route('superadmin.laporan.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.laporan.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-file-alt text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Laporan</span>
        </a>

        <!-- Pengaturan Sistem -->
        <a href="{{ route('superadmin.pengaturan.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('superadmin.pengaturan.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-cog text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Pengaturan Sistem</span>
        </a>

    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-teal-500/30 mt-auto">
        <div class="text-center">
            <p class="text-xs text-teal-100 font-medium mb-1">&copy; 2025 PT Puri Digital Output</p>
            <p class="text-xs text-teal-200/70">Version 1.1.2</p>
        </div>
    </div>

</aside>