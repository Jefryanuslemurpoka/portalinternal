<header class="bg-white shadow-sm border-b border-teal-100">
    <div class="flex items-center justify-between px-3 sm:px-4 md:px-6 py-3 md:py-4">
        
        <!-- Left: Menu Toggle & Title -->
        <div class="flex items-center space-x-2 sm:space-x-4 flex-1 min-w-0">
            <button id="sidebarToggle" class="text-gray-600 hover:text-teal-600 focus:outline-none lg:hidden transition flex-shrink-0">
                <i class="fas fa-bars text-lg sm:text-xl"></i>
            </button>
            <h1 class="text-base sm:text-lg md:text-xl font-semibold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h1>
        </div>

        <!-- Right: Notifications & Profile -->
        <div class="flex items-center space-x-2 sm:space-x-4">
            
            <!-- Notifications -->
            <div class="relative">
                <button id="notificationBtn" class="relative text-gray-600 hover:text-teal-600 focus:outline-none transition">
                    <i class="fas fa-bell text-lg sm:text-xl"></i>
                    <span id="notificationBadge" class="absolute top-0 right-0 inline-flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 text-xs font-bold text-white bg-gradient-to-r from-teal-500 to-cyan-600 rounded-full transform translate-x-1/2 -translate-y-1/2 shadow-sm" style="display: none">
                        0
                    </span>
                </button>
                
                <!-- Dropdown Notifikasi -->
                <div id="notificationDropdown" class="hidden fixed sm:absolute left-3 right-3 sm:left-auto sm:right-0 top-16 sm:top-auto sm:mt-2 w-auto sm:w-80 md:w-96 bg-white rounded-xl shadow-xl z-50 border border-teal-100 max-h-[85vh] sm:max-h-[600px] flex flex-col">
                    <div class="p-3 sm:p-4 border-b border-gray-100 flex justify-between items-center flex-shrink-0">
                        <h3 class="text-sm sm:text-base font-semibold text-gray-800">Notifikasi</h3>
                        <button onclick="markAllAsRead()" class="text-xs text-teal-600 hover:text-teal-700 font-medium transition flex items-center gap-1" title="Tandai semua sudah dibaca">
                            <i class="fas fa-check-double"></i> 
                            <span class="hidden xs:inline">Tandai Semua</span>
                        </button>
                    </div>
                    
                    <div id="notificationList" class="overflow-y-auto flex-1">
                        <!-- Loading State -->
                        <div class="p-6 sm:p-8 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-teal-500"></div>
                            <p class="text-xs sm:text-sm text-gray-500 mt-2">Memuat notifikasi...</p>
                        </div>
                    </div>
                    
                    <div class="p-2 sm:p-3 text-center border-t border-gray-100 flex-shrink-0">
                        <a href="{{ route('notifications.index') }}" class="text-xs sm:text-sm text-teal-600 hover:text-teal-700 font-medium transition inline-block py-1">
                            Lihat Semua Notifikasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profileBtn" class="flex items-center space-x-1 sm:space-x-3 focus:outline-none hover:opacity-80 transition">
                    <img src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=14b8a6&color=fff' }}" 
                         alt="Profile" 
                         class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-teal-400 shadow-sm flex-shrink-0">
                    <div class="text-left hidden md:block min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate max-w-[100px] lg:max-w-[150px]">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-teal-600 truncate">{{ Auth::user()->role == 'super_admin' ? 'Super Admin' : 'Karyawan' }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-600 text-xs sm:text-sm flex-shrink-0"></i>
                </button>

                <!-- Dropdown Profile -->
                <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-52 sm:w-56 bg-white rounded-xl shadow-xl z-50 border border-teal-100">
                    <div class="p-3 sm:p-4 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-2">
                        @if(Auth::user()->isKaryawan())
                        <a href="{{ route('karyawan.profil.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-teal-50 transition rounded-lg mx-2">
                            <i class="fas fa-user mr-2 text-teal-500 w-4"></i>Profil Saya
                        </a>
                        @endif
                        <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-teal-50 transition rounded-lg mx-2">
                            <i class="fas fa-cog mr-2 text-teal-500 w-4"></i>Pengaturan
                        </a>
                    </div>
                    <div class="border-t border-gray-100 mt-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition rounded-lg mx-2 my-2">
                                <i class="fas fa-sign-out-alt mr-2 w-4"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Audio dari file lokal -->
<audio id="notificationSound" preload="auto">
    <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
</audio>

<script>
    (() => {
        'use strict';
        
        // Config
        const CONFIG = {
            CHECK_INTERVAL: 5000,
            REFRESH_INTERVAL: 10000,
            DEBOUNCE_DELAY: 150,
            LOAD_DELAY: 100,
            BATCH_SIZE: 10 // Process notifications in batches
        };
        
        // State
        const state = {
            audio: null,
            audioUnlocked: false,
            lastCount: 0,
            request: null,
            isFirstLoad: true,
            intervals: [],
            timeouts: []
        };
        
        // Cache
        let elementsCache = null;
        const getElements = () => {
            if (!elementsCache) {
                elementsCache = {
                    notificationBtn: document.getElementById('notificationBtn'),
                    profileBtn: document.getElementById('profileBtn'),
                    notificationDropdown: document.getElementById('notificationDropdown'),
                    profileDropdown: document.getElementById('profileDropdown'),
                    notificationList: document.getElementById('notificationList'),
                    notificationBadge: document.getElementById('notificationBadge')
                };
            }
            return elementsCache;
        };
        
        // Utilities
        const utils = {
            debounce(func, wait) {
                let timeout;
                return (...args) => {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func(...args), wait);
                    state.timeouts.push(timeout);
                };
            },
            
            raf(callback) {
                requestAnimationFrame(callback);
            },
            
            abortRequest() {
                if (state.request) {
                    state.request.abort();
                    state.request = null;
                }
            },
            
            clearTimeouts() {
                state.timeouts.forEach(t => clearTimeout(t));
                state.timeouts = [];
            }
        };
        
        // Icon config (cached)
        const ICON_CONFIG = {
            'cuti': { icon: 'fa-calendar-alt', color: 'text-amber-500' },
            'approval': { icon: 'fa-check-circle', color: 'text-teal-500' },
            'pengumuman': { icon: 'fa-info-circle', color: 'text-cyan-500' },
            'absensi': { icon: 'fa-clock', color: 'text-teal-600' },
            'default': { icon: 'fa-bell', color: 'text-gray-500' }
        };
        
        // Time formatting
        function formatTime(dateString) {
            const seconds = Math.floor((Date.now() - new Date(dateString)) / 1000);
            
            if (seconds < 60) return 'Baru saja';
            if (seconds < 3600) return `${Math.floor(seconds / 60)} menit yang lalu`;
            if (seconds < 86400) return `${Math.floor(seconds / 3600)} jam yang lalu`;
            if (seconds < 604800) return `${Math.floor(seconds / 86400)} hari yang lalu`;
            
            return new Date(dateString).toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
        }
        
        // Build notification HTML
        function buildNotificationItem(notif) {
            const icon = ICON_CONFIG[notif.type] || ICON_CONFIG.default;
            const time = formatTime(notif.created_at);
            const bgClass = notif.is_read ? '' : 'bg-teal-50';
            const dot = notif.is_read ? '' : '<span class="inline-block w-2 h-2 bg-teal-500 rounded-full flex-shrink-0 mt-1.5"></span>';
            
            return `<a href="{{ url('/') }}/notifications/${notif.id}/read" class="block p-3 sm:p-4 hover:bg-gray-50 border-b border-gray-100 ${bgClass} transition"><div class="flex items-start gap-2 sm:gap-3"><div class="flex-shrink-0 mt-0.5"><i class="fas ${icon.icon} ${icon.color} text-base sm:text-lg"></i></div><div class="flex-1 min-w-0"><div class="flex items-start justify-between gap-2"><p class="text-xs sm:text-sm font-medium text-gray-800 line-clamp-2 flex-1">${notif.title}</p>${dot}</div><p class="text-xs text-gray-600 mt-1 line-clamp-2 break-words">${notif.message}</p><p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><i class="fas fa-clock text-[10px]"></i> <span class="truncate">${time}</span></p></div></div></a>`;
        }
        
        // Render notifications in batches
        function renderNotifications(notifications, list) {
            if (notifications.length === 0) {
                list.innerHTML = '<div class="p-6 sm:p-8 text-center"><i class="fas fa-bell-slash text-gray-300 text-3xl sm:text-4xl mb-2"></i><p class="text-xs sm:text-sm text-gray-500">Tidak ada notifikasi</p></div>';
                return;
            }
            
            // Build all HTML at once (faster than batching for small lists)
            const html = notifications.map(buildNotificationItem).join('');
            list.innerHTML = html;
        }
        
        // Update badge
        function updateBadge(badge, count) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-flex' : 'none';
        }
        
        // Play sound
        function playSound() {
            if (!state.audio || !state.audioUnlocked) return;
            
            utils.raf(() => {
                try {
                    state.audio.currentTime = 0;
                    state.audio.volume = 1.0;
                    state.audio.play().catch(() => {});
                } catch (e) {}
            });
        }
        
        // Load notifications
        function loadNotifications() {
            utils.abortRequest();
            
            const controller = new AbortController();
            state.request = controller;
            
            fetch('{{ route("notifications.unread") }}', {
                signal: controller.signal,
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(data => {
                const els = getElements();
                if (!els.notificationList || !els.notificationBadge) return;
                
                utils.raf(() => {
                    updateBadge(els.notificationBadge, data.unread_count);
                    renderNotifications(data.notifications, els.notificationList);
                });
                
                state.request = null;
            })
            .catch(err => {
                if (err.name === 'AbortError') return;
                
                const els = getElements();
                if (els.notificationList) {
                    utils.raf(() => {
                        els.notificationList.innerHTML = '<div class="p-4 sm:p-6 text-center text-red-500"><i class="fas fa-exclamation-circle text-2xl sm:text-3xl"></i><p class="text-xs sm:text-sm mt-2">Gagal memuat notifikasi</p></div>';
                    });
                }
                state.request = null;
            });
        }
        
        // Check new notifications
        function checkNotifications() {
            if (state.request) return;
            
            const controller = new AbortController();
            state.request = controller;
            
            fetch('{{ route("notifications.unread") }}', {
                signal: controller.signal,
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                const count = data.unread_count;
                const els = getElements();
                
                if (!els.notificationBadge) return;
                
                const shouldPlaySound = !state.isFirstLoad && count > state.lastCount && count > 0;
                
                utils.raf(() => {
                    updateBadge(els.notificationBadge, count);
                    
                    if (shouldPlaySound) {
                        playSound();
                        els.notificationBadge.classList.add('animate-bounce');
                        const timeout = setTimeout(() => {
                            utils.raf(() => els.notificationBadge.classList.remove('animate-bounce'));
                        }, 1000);
                        state.timeouts.push(timeout);
                    }
                });
                
                state.lastCount = count;
                if (state.isFirstLoad) state.isFirstLoad = false;
                state.request = null;
            })
            .catch(err => {
                if (err.name !== 'AbortError') state.request = null;
            });
        }
        
        // Mark all as read
        window.markAllAsRead = function() {
            fetch('{{ route("notifications.markAllAsRead") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    state.lastCount = 0;
                    const timeout = setTimeout(loadNotifications, 50);
                    state.timeouts.push(timeout);
                }
            })
            .catch(() => {});
        };
        
        // Event handlers
        function setupEventHandlers() {
            const els = getElements();
            
            // Unlock audio
            document.addEventListener('click', () => {
                if (state.audio && !state.audioUnlocked) {
                    state.audio.play().then(() => {
                        state.audio.pause();
                        state.audio.currentTime = 0;
                        state.audioUnlocked = true;
                    }).catch(() => {});
                }
            }, { once: true });
            
            // Notification button
            if (els.notificationBtn) {
                els.notificationBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    
                    const isHidden = els.notificationDropdown?.classList.contains('hidden');
                    
                    utils.raf(() => {
                        els.notificationDropdown?.classList.toggle('hidden');
                        els.profileDropdown?.classList.add('hidden');
                        
                        if (isHidden) {
                            const timeout = setTimeout(loadNotifications, CONFIG.LOAD_DELAY);
                            state.timeouts.push(timeout);
                        }
                    });
                });
            }
            
            // Profile button
            if (els.profileBtn) {
                els.profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    utils.raf(() => {
                        els.profileDropdown?.classList.toggle('hidden');
                        els.notificationDropdown?.classList.add('hidden');
                    });
                });
            }
            
            // Close dropdowns (debounced)
            document.addEventListener('click', utils.debounce(() => {
                utils.raf(() => {
                    els.notificationDropdown?.classList.add('hidden');
                    els.profileDropdown?.classList.add('hidden');
                });
            }, CONFIG.DEBOUNCE_DELAY));
        }
        
        // Initialize
        function init() {
            state.audio = document.getElementById('notificationSound');
            
            setupEventHandlers();
            
            // Initial check (delayed)
            const initialTimeout = setTimeout(checkNotifications, CONFIG.LOAD_DELAY);
            state.timeouts.push(initialTimeout);
            
            // Auto check interval
            state.intervals.push(setInterval(checkNotifications, CONFIG.CHECK_INTERVAL));
            
            // Auto refresh dropdown interval
            state.intervals.push(setInterval(() => {
                const els = getElements();
                if (els.notificationDropdown && !els.notificationDropdown.classList.contains('hidden')) {
                    loadNotifications();
                }
            }, CONFIG.REFRESH_INTERVAL));
        }
        
        // Cleanup
        function cleanup() {
            state.intervals.forEach(clearInterval);
            utils.clearTimeouts();
            utils.abortRequest();
        }
        
        // Start when DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
        
        // Cleanup on unload
        window.addEventListener('beforeunload', cleanup);
        
    })();
</script>