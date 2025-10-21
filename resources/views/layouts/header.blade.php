<header class="bg-white shadow-md">
    <div class="flex items-center justify-between px-6 py-4">
        
        <!-- Left: Menu Toggle & Title -->
        <div class="flex items-center space-x-4">
            <button id="sidebarToggle" class="text-gray-600 hover:text-gray-800 focus:outline-none lg:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        </div>

        <!-- Right: Notifications & Profile -->
        <div class="flex items-center space-x-4">
            
            <!-- Notifications -->
            <div class="relative">
                <button id="notificationBtn" class="relative text-gray-600 hover:text-gray-800 focus:outline-none">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full transform translate-x-1/2 -translate-y-1/2">
                        3
                    </span>
                </button>
                
                <!-- Dropdown Notifikasi -->
                <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Notifikasi</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Notifikasi Item -->
                        <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">Pengumuman Baru</p>
                                    <p class="text-xs text-gray-600 mt-1">Rapat koordinasi bulanan hari Jumat</p>
                                    <p class="text-xs text-gray-400 mt-1">2 jam yang lalu</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">Cuti Disetujui</p>
                                    <p class="text-xs text-gray-600 mt-1">Pengajuan cuti Anda telah disetujui</p>
                                    <p class="text-xs text-gray-400 mt-1">5 jam yang lalu</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block p-4 hover:bg-gray-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">Reminder Absensi</p>
                                    <p class="text-xs text-gray-600 mt-1">Jangan lupa check-out hari ini</p>
                                    <p class="text-xs text-gray-400 mt-1">1 hari yang lalu</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-3 text-center border-t border-gray-200">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profileBtn" class="flex items-center space-x-3 focus:outline-none">
                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4F46E5&color=fff' }}" 
                         alt="Profile" 
                         class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                    <div class="text-left hidden md:block">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->role == 'super_admin' ? 'Super Admin' : 'Karyawan' }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
                </button>

                <!-- Dropdown Profile -->
                <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-2">
                        @if(Auth::user()->isKaryawan())
                        <a href="{{ route('karyawan.profil.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i>Profil Saya
                        </a>
                        @endif
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i>Pengaturan
                        </a>
                    </div>
                    <div class="border-t border-gray-200">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<script>
    // Toggle Notification Dropdown
    document.getElementById('notificationBtn').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('notificationDropdown').classList.toggle('hidden');
        document.getElementById('profileDropdown').classList.add('hidden');
    });

    // Toggle Profile Dropdown
    document.getElementById('profileBtn').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('profileDropdown').classList.toggle('hidden');
        document.getElementById('notificationDropdown').classList.add('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.getElementById('notificationDropdown').classList.add('hidden');
        document.getElementById('profileDropdown').classList.add('hidden');
    });
</script>