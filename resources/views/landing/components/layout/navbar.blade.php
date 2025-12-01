<nav x-data="{ 
    mobileMenu: false, 
    activeLink: 'home',
    isScrolled: false,
    showDropdown: false 
}" 
x-init="
    window.addEventListener('scroll', () => {
        isScrolled = window.pageYOffset > 20;
    });
    
    const sections = document.querySelectorAll('section[id]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                activeLink = entry.target.getAttribute('id');
            }
        });
    }, { threshold: 0.5 });
    
    sections.forEach(section => observer.observe(section));
"
:class="isScrolled ? 'bg-slate-950/90 backdrop-blur-2xl shadow-2xl shadow-cyan-500/10 border-b border-cyan-500/20' : 'bg-gradient-to-b from-slate-950/50 to-transparent backdrop-blur-sm'"
class="fixed w-full top-0 z-50 transition-all duration-700">

    <!-- Animated Gradient Border -->
    <div class="absolute top-0 left-0 right-0 h-[3px] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-shimmer"></div>
    </div>
    
    <!-- Floating Particles Effect -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-30">
        <div class="particle-1"></div>
        <div class="particle-2"></div>
        <div class="particle-3"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="flex items-center justify-between h-20">
            
            <!-- Logo - Ultra Enhanced dengan Custom Image -->
            <div class="flex-shrink-0 group cursor-pointer">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3">
                    <div class="relative">
                        <!-- Multi-layer Glow -->
                        <div class="absolute -inset-2 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-600 rounded-2xl blur-xl opacity-40 group-hover:opacity-70 animate-pulse-slow"></div>
                        <div class="absolute -inset-1 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-xl blur-md opacity-50 group-hover:opacity-80 transition-all duration-500"></div>
                        
                        <!-- Logo Container with 3D Effect -->
                        <div class="relative w-14 h-14 bg-white rounded-full flex items-center justify-center transform group-hover:scale-110 transition-all duration-500 shadow-2xl shadow-cyan-500/50 border-2 border-cyan-400/40 overflow-hidden">
                            <!-- Inner Glow -->
                            <div class="absolute inset-1 bg-gradient-to-br from-cyan-50 to-blue-50 rounded-full"></div>
                            
                            <!-- Custom Logo Image -->
                            <img src="{{ asset('images/logo.png') }}" 
                                 alt="Puri Digital Output Logo" 
                                 class="w-10 h-10 object-contain relative z-10 drop-shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                        </div>
                    </div>
                    
                    <div class="hidden md:block">
                        <div class="text-xl font-bold bg-gradient-to-r from-white via-cyan-200 to-blue-300 bg-clip-text text-transparent drop-shadow-lg group-hover:from-cyan-300 group-hover:to-blue-400 transition-all duration-300">
                            PURI DIGITAL OUTPUT
                        </div>
                        <div class="text-xs font-medium bg-gradient-to-r from-cyan-400/80 to-blue-400/80 bg-clip-text text-transparent tracking-widest">
                            DIGITAL TECHNOLOGY SOLUTION
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Menu - Premium Enhanced -->
            <div class="hidden lg:block">
                <div class="flex items-center space-x-1">
                    <!-- Menu Items -->
                    <template x-for="(item, index) in [
                        {id: 'home', label: 'Home', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'},
                        {id: 'about', label: 'About', icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'},
                        {id: 'services', label: 'Services', icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'},
                        {id: 'industries', label: 'Industries', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'},
                        {id: 'security', label: 'Security', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'},
                        {id: 'contact', label: 'Contact', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'}
                    ]" :key="item.id">
                        <a :href="`#${item.id}`" 
                           @click="activeLink = item.id"
                           :class="activeLink === item.id ? 'text-cyan-400 scale-105' : 'text-gray-300 hover:text-white'"
                           class="relative px-5 py-2.5 text-sm font-semibold transition-all duration-300 rounded-xl group"
                           x-transition>
                            
                            <!-- Hover Glow Effect -->
                            <span class="absolute -inset-1 bg-gradient-to-r from-cyan-500/20 via-blue-500/20 to-purple-500/20 rounded-xl opacity-0 group-hover:opacity-100 blur-sm transition-all duration-300"></span>
                            
                            <!-- Hover Background -->
                            <span class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 via-blue-500/10 to-purple-500/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            
                            <!-- Active Background with Animation -->
                            <span x-show="activeLink === item.id" 
                                  x-transition
                                  class="absolute inset-0 bg-gradient-to-r from-cyan-500/25 via-blue-500/25 to-purple-500/25 rounded-xl shadow-lg shadow-cyan-500/20"></span>
                            
                            <!-- Content with Icon -->
                            <span class="relative z-10 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-html="`<path stroke-linecap='round' stroke-linejoin='round' d='${item.icon}'/>`"></svg>
                                <span x-text="item.label"></span>
                            </span>
                            
                            <!-- Active Indicator Line -->
                            <span x-show="activeLink === item.id" 
                                  x-transition:enter="transition ease-out duration-300"
                                  x-transition:enter-start="w-0"
                                  x-transition:enter-end="w-3/4"
                                  class="absolute -bottom-1 left-1/2 -translate-x-1/2 h-1 bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 rounded-full shadow-lg shadow-cyan-400/50"></span>
                            
                            <!-- Sparkle Effect on Hover -->
                            <span class="absolute top-0 right-0 w-2 h-2 bg-cyan-400 rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping"></span>
                        </a>
                    </template>
                </div>
            </div>
            
            <!-- Mobile Menu Button - Ultra Enhanced -->
            <div class="lg:hidden">
                <button @click="mobileMenu = !mobileMenu" class="relative w-12 h-12 text-gray-300 hover:text-white focus:outline-none group">
                    <!-- Background Glow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/30 to-blue-500/30 rounded-xl opacity-0 group-hover:opacity-100 blur-md transition-all duration-300"></div>
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <!-- Icons with Animation -->
                    <div class="relative w-full h-full flex items-center justify-center">
                        <svg x-show="!mobileMenu" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 rotate-90 scale-50"
                             x-transition:enter-end="opacity-100 rotate-0 scale-100"
                             class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenu" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 rotate-90 scale-50"
                             x-transition:enter-end="opacity-100 rotate-0 scale-100"
                             class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </button>
            </div>
            
        </div>
    </div>
    
    <!-- Mobile Menu - Ultra Premium -->
    <div x-show="mobileMenu" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden">
        
        <!-- Premium Mobile Menu Background -->
        <div class="bg-gradient-to-b from-slate-950/98 to-slate-900/98 backdrop-blur-2xl border-t border-cyan-500/20 shadow-2xl shadow-cyan-500/10">
            <div class="px-4 pt-4 pb-6 space-y-2">
                
                <!-- Mobile Menu Items -->
                <template x-for="(item, index) in [
                    {id: 'home', label: 'Home', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'},
                    {id: 'about', label: 'About', icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'},
                    {id: 'services', label: 'Services', icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'},
                    {id: 'industries', label: 'Industries', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'},
                    {id: 'security', label: 'Security', icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'},
                    {id: 'contact', label: 'Contact', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'}
                ]" :key="item.id">
                    <a :href="`#${item.id}`" 
                       @click="activeLink = item.id; mobileMenu = false"
                       :class="activeLink === item.id ? 'bg-gradient-to-r from-cyan-500/30 via-blue-500/30 to-purple-500/30 text-cyan-300 shadow-lg shadow-cyan-500/20 border-l-4 border-cyan-400' : 'text-gray-300 hover:text-white hover:bg-slate-800/70 border-l-4 border-transparent hover:border-cyan-500/50'"
                       class="group flex items-center justify-between px-5 py-4 rounded-xl transition-all duration-300 transform hover:translate-x-2"
                       :style="`animation-delay: ${index * 50}ms`">
                        
                        <span class="flex items-center space-x-3">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-html="`<path stroke-linecap='round' stroke-linejoin='round' d='${item.icon}'/>`"></svg>
                            <span class="font-semibold tracking-wide" x-text="item.label"></span>
                        </span>
                        
                        <div class="flex items-center space-x-2">
                            <span x-show="activeLink === item.id" 
                                  x-transition
                                  class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse shadow-lg shadow-cyan-400/50"></span>
                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transform translate-x-0 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </template>
                
                <!-- Mobile CTA Button -->
                <a href="#contact" 
                   class="group relative flex items-center justify-center space-x-2 mt-6 px-6 py-4 rounded-xl text-white font-bold text-center overflow-hidden shadow-2xl shadow-cyan-500/30 transform hover:scale-105 transition-all duration-300">
                    <!-- Animated Background -->
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-600"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <!-- Shine Effect -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </div>
                    
                    <span class="relative z-10 tracking-wide">Get Started Now</span>
                    <svg class="relative z-10 w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .animate-shimmer {
        animation: shimmer 3s infinite;
    }
    
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-20px) translateX(10px); }
    }
    
    .particle-1, .particle-2, .particle-3 {
        position: absolute;
        width: 4px;
        height: 4px;
        background: linear-gradient(45deg, #06b6d4, #3b82f6);
        border-radius: 50%;
        filter: blur(1px);
    }
    
    .particle-1 {
        top: 20%;
        left: 10%;
        animation: float 6s infinite;
    }
    
    .particle-2 {
        top: 60%;
        left: 80%;
        animation: float 8s infinite 1s;
    }
    
    .particle-3 {
        top: 40%;
        left: 50%;
        animation: float 7s infinite 2s;
    }
</style>