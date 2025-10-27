<aside id="sidebar" class="bg-gradient-to-b from-teal-600 to-cyan-700 text-white w-64 flex-shrink-0 overflow-y-auto">
    
    <!-- Logo -->
    <div class="p-6 border-b border-teal-500/30">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-building text-teal-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">Portal Internal</h2>
                <p class="text-xs text-teal-100">Karyawan Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4 space-y-2">
        
        <!-- Dashboard -->
        <a href="{{ route('karyawan.dashboard') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.dashboard') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-home text-lg w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Absensi -->
        <a href="{{ route('karyawan.absensi.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.absensi.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-clipboard-check text-lg w-5"></i>
            <span class="font-medium">Absensi</span>
        </a>

        <!-- Rekap Absensi -->
        <a href="{{ route('karyawan.rekap.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.rekap.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-chart-bar text-lg w-5"></i>
            <span class="font-medium">Rekap Absensi</span>
        </a>

        <!-- Pengajuan Cuti/Izin -->
        <a href="{{ route('karyawan.cutiizin.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.cutiizin.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-calendar-alt text-lg w-5"></i>
            <span class="font-medium">Pengajuan Cuti/Izin</span>
        </a>

        <!-- Pengumuman -->
        <a href="{{ route('karyawan.pengumuman.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.pengumuman.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-bullhorn text-lg w-5"></i>
            <span class="font-medium">Pengumuman</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('karyawan.profil.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.profil.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-user text-lg w-5"></i>
            <span class="font-medium">Profil Saya</span>
        </a>

    </nav>

</aside>