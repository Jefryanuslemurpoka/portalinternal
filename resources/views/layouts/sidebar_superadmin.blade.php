<aside id="sidebar" class="bg-gradient-to-b from-blue-600 to-blue-800 text-white w-64 flex-shrink-0 overflow-y-auto">
    
    <!-- Logo -->
    <div class="p-6 border-b border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-building text-blue-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold">Portal Internal</h2>
                <p class="text-xs text-blue-200">Super Admin Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4 space-y-2">
        
        <!-- Dashboard -->
        <a href="{{ route('superadmin.dashboard') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.dashboard') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-home text-lg w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Manajemen Karyawan -->
        <a href="{{ route('superadmin.karyawan.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.karyawan.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-users text-lg w-5"></i>
            <span class="font-medium">Manajemen Karyawan</span>
        </a>

        <!-- Manajemen Absensi -->
        <a href="{{ route('superadmin.absensi.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.absensi.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-clipboard-check text-lg w-5"></i>
            <span class="font-medium">Manajemen Absensi</span>
        </a>

        <!-- Persetujuan Cuti/Izin -->
        <a href="{{ route('superadmin.cutiizin.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.cutiizin.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-calendar-check text-lg w-5"></i>
            <span class="font-medium">Persetujuan Cuti/Izin</span>
        </a>

        <!-- Log Book Surat -->
        <a href="{{ route('superadmin.surat.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.surat.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-envelope text-lg w-5"></i>
            <span class="font-medium">Log Book Surat</span>
        </a>

        <!-- Log Book Server -->
        <a href="{{ route('superadmin.serverlog.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.serverlog.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-server text-lg w-5"></i>
            <span class="font-medium">Log Book Server</span>
        </a>

        <!-- Pengumuman -->
        <a href="{{ route('superadmin.pengumuman.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.pengumuman.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-bullhorn text-lg w-5"></i>
            <span class="font-medium">Pengumuman</span>
        </a>

        <!-- Laporan -->
        <a href="{{ route('superadmin.laporan.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.laporan.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-file-alt text-lg w-5"></i>
            <span class="font-medium">Laporan</span>
        </a>

        <!-- Pengaturan Sistem -->
        <a href="{{ route('superadmin.pengaturan.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superadmin.pengaturan.*') ? 'bg-blue-700' : '' }}">
            <i class="fas fa-cog text-lg w-5"></i>
            <span class="font-medium">Pengaturan Sistem</span>
        </a>

    </nav>

</aside>