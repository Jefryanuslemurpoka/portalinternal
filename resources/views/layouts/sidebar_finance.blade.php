<aside id="sidebar" class="bg-gradient-to-b from-teal-600 to-cyan-700 text-white w-64 flex-shrink-0 overflow-y-auto flex flex-col">
    
    <!-- Logo -->
    <div class="p-4 sm:p-6 border-b border-teal-500/30">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center shadow-md flex-shrink-0 p-1">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div class="min-w-0">
                <h2 class="text-lg sm:text-xl font-bold truncate">Portal Internal</h2>
                <p class="text-xs text-teal-100 truncate">Finance Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 p-3 sm:p-4 space-y-1 sm:space-y-2">
        
        <!-- Dashboard -->
        <a href="{{ route('finance.dashboard') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('finance.dashboard') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-home text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Dashboard</span>
        </a>

        <!-- Divider -->
        <div class="pt-2 pb-1">
            <hr class="border-t border-teal-500/30">
        </div>

        <!-- Section: Menu Umum -->
        <div class="px-2 pt-2 pb-1">
            <p class="text-xs font-semibold text-teal-200 uppercase tracking-wider">Menu Umum</p>
        </div>

        <!-- Absensi -->
        <a href="{{ route('karyawan.absensi.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.absensi.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-clipboard-check text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Absensi</span>
        </a>

        <!-- Rekap Absensi -->
        <a href="{{ route('karyawan.rekap.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.rekap.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-chart-bar text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Rekap Absensi</span>
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

        <!-- Workspace -->
        <a href="{{ route('karyawan.workspace.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.workspace.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-briefcase text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Workspace</span>
        </a>

        <!-- Pengumuman -->
        <a href="{{ route('karyawan.pengumuman.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.pengumuman.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-bullhorn text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Pengumuman</span>
        </a>

        <!-- Divider -->
        <div class="pt-2 pb-1">
            <hr class="border-t border-teal-500/30">
        </div>

        <!-- Section: Manajemen Keuangan -->
        <div class="px-2 pt-2 pb-1">
            <p class="text-xs font-semibold text-teal-200 uppercase tracking-wider">Manajemen Keuangan</p>
        </div>

        <!-- Gaji Karyawan -->
        <a href="{{ route('finance.gaji.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('finance.gaji.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-wallet text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Gaji Karyawan</span>
        </a>

        <!-- Pengeluaran -->
        <a href="{{ route('finance.pengeluaran.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('finance.pengeluaran.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-arrow-down text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Pengeluaran</span>
        </a>

        <!-- Pemasukan -->
        <a href="{{ route('finance.pemasukan.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('finance.pemasukan.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-arrow-up text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Pemasukan</span>
        </a>

        <!-- Anggaran -->
        <a href="{{ route('finance.anggaran.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('finance.anggaran.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-chart-pie text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Anggaran</span>
        </a>

        <!-- Divider -->
        <div class="pt-2 pb-1">
            <hr class="border-t border-teal-500/30">
        </div>

        <!-- Section: Laporan & Dokumen -->
        <div class="px-2 pt-2 pb-1">
            <p class="text-xs font-semibold text-teal-200 uppercase tracking-wider">Laporan & Dokumen</p>
        </div>

        <!-- Laporan Keuangan -->
        <a href="{{ route('finance.laporan.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('finance.laporan.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-file-invoice-dollar text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Laporan Keuangan</span>
        </a>

        <!-- Invoice -->
        <a href="{{ route('finance.invoice.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('finance.invoice.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-receipt text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Invoice</span>
        </a>

        <!-- Divider -->
        <div class="pt-2 pb-1">
            <hr class="border-t border-teal-500/30">
        </div>

        <!-- Profil -->
        <a href="{{ route('karyawan.profil.index') }}" 
           class="flex items-center space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('karyawan.profil.*') ? 'bg-white/20 shadow-md' : '' }}">
            <i class="fas fa-user text-base sm:text-lg w-5 flex-shrink-0"></i>
            <span class="font-medium text-sm sm:text-base truncate">Profil</span>
        </a>

    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-teal-500/30 mt-auto">
        <div class="text-center">
            <p class="text-xs text-teal-100 font-medium mb-1">&copy; 2025 PT Puri Digital Output</p>
            <p class="text-xs text-teal-200/70">Version 1.2.9</p>
        </div>
    </div>

</aside>