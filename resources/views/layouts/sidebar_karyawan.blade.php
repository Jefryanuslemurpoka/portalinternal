<aside id="sidebar" class="bg-gradient-to-b from-teal-600 to-cyan-700 text-white w-64 flex-shrink-0 overflow-y-auto">
    
    <!-- Logo -->
    <div class="p-4 sm:p-6 border-b border-teal-500/30">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                <i class="fas fa-building text-teal-600 text-lg sm:text-xl"></i>
            </div>
            <div class="min-w-0">
                <h2 class="text-lg sm:text-xl font-bold truncate">Portal Internal</h2>
                <p class="text-xs text-teal-100 truncate">Karyawan Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-3 sm:p-4 space-y-1 sm:space-y-2">
        
        <!-- Dashboard -->
        <a href="{{ route('karyawan.dashboard') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.dashboard') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-home text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Dashboard</span>
        </a>

        <!-- Absensi -->
        <a href="{{ route('karyawan.absensi.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.absensi.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-clipboard-check text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Absensi</span>
        </a>

        <!-- Cuti/Izin -->
        <a href="{{ route('karyawan.cutiizin.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.cutiizin.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-calendar-alt text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Cuti/Izin</span>
        </a>

        <!-- Log Book Surat -->
        <a href="{{ route('karyawan.surat.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.surat.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-envelope text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Log Book Surat</span>
        </a>

        <!-- Log Book Server -->
        <a href="{{ route('karyawan.serverlog.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.serverlog.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-server text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Log Book Server</span>
        </a>

        <!-- Pengumuman -->
        <a href="{{ route('karyawan.pengumuman.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.pengumuman.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-bullhorn text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Pengumuman</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('karyawan.profil.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.profil.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-user text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Profil Saya</span>
        </a>

    </nav>

</aside>