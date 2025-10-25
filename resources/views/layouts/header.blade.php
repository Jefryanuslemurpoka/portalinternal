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
                    <span id="notificationBadge" class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full transform translate-x-1/2 -translate-y-1/2" style="display: {{ Auth::user()->unreadNotificationsCount() > 0 ? 'inline-flex' : 'none' }}">
                        {{ Auth::user()->unreadNotificationsCount() }}
                    </span>
                </button>
                
                <!-- Dropdown Notifikasi -->
                <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border border-gray-200">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Notifikasi</h3>
                        <button onclick="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-800" title="Tandai semua sudah dibaca">
                            <i class="fas fa-check-double"></i> Tandai Semua
                        </button>
                    </div>
                    
                    <div id="notificationList" class="max-h-96 overflow-y-auto">
                        <!-- Loading State -->
                        <div class="p-4 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="text-sm text-gray-500 mt-2">Memuat notifikasi...</p>
                        </div>
                    </div>
                    
                    <div class="p-3 text-center border-t border-gray-200">
                        <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
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
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
        document.getElementById('profileDropdown').classList.add('hidden');
        
        // Load notifications if dropdown is shown
        if (!dropdown.classList.contains('hidden')) {
            loadNotifications();
        }
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

    // Load notifications via AJAX
    function loadNotifications() {
        fetch('{{ route("notifications.unread") }}')
            .then(response => response.json())
            .then(data => {
                const notificationList = document.getElementById('notificationList');
                const badge = document.getElementById('notificationBadge');
                
                // Update badge
                badge.textContent = data.unread_count;
                if (data.unread_count === 0) {
                    badge.style.display = 'none';
                } else {
                    badge.style.display = 'inline-flex';
                }
                
                // Display notifications
                if (data.notifications.length === 0) {
                    notificationList.innerHTML = `
                        <div class="p-8 text-center">
                            <i class="fas fa-bell-slash text-gray-300 text-4xl mb-2"></i>
                            <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                        </div>
                    `;
                } else {
                    let html = '';
                    data.notifications.forEach(notif => {
                        const iconConfig = getNotificationIcon(notif.type);
                        const timeAgo = getTimeAgo(notif.created_at);
                        const unreadBg = !notif.is_read ? 'bg-blue-50' : '';
                        
                        html += `
                            <a href="{{ url('/') }}/notifications/${notif.id}/read" class="block p-4 hover:bg-gray-50 border-b border-gray-100 ${unreadBg}">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas ${iconConfig.icon} ${iconConfig.color} text-xl"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-start justify-between">
                                            <p class="text-sm font-medium text-gray-800">${notif.title}</p>
                                            ${!notif.is_read ? '<span class="ml-2 inline-block w-2 h-2 bg-blue-500 rounded-full"></span>' : ''}
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">${notif.message}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-clock"></i> ${timeAgo}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    notificationList.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                document.getElementById('notificationList').innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <i class="fas fa-exclamation-circle"></i>
                        <p class="text-sm mt-2">Gagal memuat notifikasi</p>
                    </div>
                `;
            });
    }

    // Mark all as read
    function markAllAsRead() {
        fetch('{{ route("notifications.markAllAsRead") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Helper: Get notification icon config
    function getNotificationIcon(type) {
        const configs = {
            'cuti': { icon: 'fa-calendar-alt', color: 'text-yellow-500' },
            'approval': { icon: 'fa-check-circle', color: 'text-green-500' },
            'pengumuman': { icon: 'fa-info-circle', color: 'text-blue-500' },
            'absensi': { icon: 'fa-clock', color: 'text-purple-500' }
        };
        return configs[type] || { icon: 'fa-bell', color: 'text-gray-500' };
    }

    // Helper: Time ago
    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'Baru saja';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' menit yang lalu';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam yang lalu';
        if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari yang lalu';
        
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    // Auto refresh notifications every 30 seconds
    setInterval(() => {
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown.classList.contains('hidden')) {
            loadNotifications();
        }
    }, 30000);
</script>