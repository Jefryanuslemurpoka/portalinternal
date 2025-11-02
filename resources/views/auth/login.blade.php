<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Internal - PT Puri Digital Output</title>
    
    <!-- ✅ GUNAKAN VITE ASSET (BUKAN CDN) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .gradient-premium {
            background: linear-gradient(135deg, #0f766e 0%, #0891b2 50%, #0e7490 100%);
            position: relative;
            overflow: hidden;
        }
        
        .gradient-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(255,255,255,0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(6,182,212,0.15) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .pattern-dots {
            background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        
        .floating-slow {
            animation: floating 8s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(3deg); }
        }
        
        .input-premium {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .input-premium:focus {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -10px rgba(13, 148, 136, 0.25);
        }

        .btn-premium {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-premium:hover::before {
            left: 100%;
        }

        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 25px 50px -12px rgba(13, 148, 136, 0.5);
        }

        .logo-glow {
            box-shadow: 0 20px 60px -10px rgba(255, 255, 255, 0.3);
        }

        .illustration-container {
            perspective: 1000px;
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-white min-h-screen overflow-hidden">
    
    <div class="flex min-h-screen">
        
        <!-- Left Side - Premium Branding -->
        <div class="hidden lg:flex lg:w-1/2 gradient-premium relative">
            
            <!-- Decorative Background Elements -->
            <div class="absolute inset-0 pattern-dots opacity-30"></div>
            <div class="absolute top-20 right-20 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 bg-cyan-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full bg-gradient-to-br from-transparent via-white/5 to-transparent"></div>
            
            <!-- Content Container -->
            <div class="relative z-10 w-full h-screen p-12 flex flex-col">
                
                <!-- Top Section - Logo & Company -->
                <div class="pt-4">
                    <!-- Logo Bulat -->
                    <div class="w-28 h-28 bg-white rounded-full flex items-center justify-center shadow-2xl mb-10 logo-glow p-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo PT Puri Digital Output" class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-6xl font-black text-white mb-5 leading-tight tracking-tight">
                        PT Puri Digital Output
                    </h1>
                    <div class="h-1.5 w-32 bg-gradient-to-r from-white via-cyan-200 to-transparent rounded-full mb-8"></div>
                    <p class="text-2xl text-white/95 font-medium mb-3">Portal Internal Management</p>
                </div>

                <!-- Center Section - Illustration -->
                <div class="flex-1 flex items-center justify-center illustration-container px-8 py-8">
                    <div class="relative floating-slow w-full max-w-sm">
                        <!-- Main Icon Circle -->
                        <div class="w-full aspect-square max-w-xs mx-auto glass-morphism rounded-full flex items-center justify-center relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-full"></div>
                            <i class="fas fa-users text-8xl text-white relative z-10"></i>
                            
                            <!-- Orbiting Elements -->
                            <div class="absolute top-8 -right-6 w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-chart-line text-2xl text-white"></i>
                            </div>
                            <div class="absolute -bottom-3 left-8 w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-shield-alt text-2xl text-white"></i>
                            </div>
                            <div class="absolute top-1/2 -left-8 transform -translate-y-1/2 w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-xl">
                                <i class="fas fa-cog text-2xl text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section - Copyright (Fixed at bottom) -->
                <div class="pb-6">
                    <div class="border-t border-white/20 pt-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/90 text-sm font-medium mb-1">&copy; 2025 PT Puri Digital Output</p>
                                <p class="text-white/60 text-xs">All rights reserved • Version 1.1.0</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-white/70 text-xs font-medium">System Online</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Right Side - Premium Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16 bg-white">
            
            <div class="w-full max-w-lg">

                <!-- Mobile Header -->
                <div class="lg:hidden text-center mb-12">
                    <!-- Logo Bulat Mobile -->
                    <div class="w-24 h-24 bg-white rounded-full mx-auto mb-6 flex items-center justify-center shadow-2xl p-4 border-4 border-teal-500">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo PT Puri Digital Output" class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-3xl font-black text-gray-900 mb-2">PT Puri Digital Output</h1>
                    <p class="text-teal-600 font-semibold">Portal Internal Management</p>
                </div>

                <!-- Welcome Section -->
                <div class="mb-14">
                    <h2 class="text-5xl font-black text-gray-900 mb-5 tracking-tight leading-tight">LOGIN</h2>
                    <p class="text-gray-600 text-lg leading-relaxed font-normal">Masukkan kredensial Anda untuk mengakses sistem manajemen internal</p>
                </div>

                <!-- Alert Success -->
                @if(session('success'))
                <div class="mb-8 p-5 bg-gradient-to-r from-teal-50 to-cyan-50 border-l-4 border-teal-500 rounded-2xl flex items-center shadow-sm">
                    <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Alert Error -->
                @if($errors->any())
                <div class="mb-8 p-5 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-2xl shadow-sm">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            @foreach($errors->all() as $error)
                                <p class="text-sm font-semibold text-gray-800">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label class="block text-gray-900 text-sm font-bold mb-4 tracking-wide uppercase" for="email">
                            Email Address
                        </label>
                        <div class="relative group">
                            <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-teal-500 transition-colors group-focus-within:text-teal-600">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                autocomplete="email"
                                value="{{ old('email') }}"
                                class="input-premium w-full pl-14 pr-6 py-5 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-0 focus:border-teal-500 bg-gray-50 text-gray-900 font-semibold placeholder-gray-400 text-base @error('email') border-red-400 @enderror"
                                placeholder="yourname@company.com"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label class="block text-gray-900 text-sm font-bold mb-4 tracking-wide uppercase" for="password">
                            Password
                        </label>
                        <div class="relative group">
                            <div class="absolute left-5 top-1/2 transform -translate-y-1/2 text-teal-500 transition-colors group-focus-within:text-teal-600">
                                <i class="fas fa-lock text-lg"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                autocomplete="current-password"
                                class="input-premium w-full pl-14 pr-16 py-5 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-0 focus:border-teal-500 bg-gray-50 text-gray-900 font-semibold placeholder-gray-400 text-base @error('password') border-red-400 @enderror"
                                placeholder="Enter your password"
                                required
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-teal-600 transition-all duration-200 p-2"
                            >
                                <i class="fas fa-eye text-lg" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center pt-2">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            class="w-5 h-5 text-teal-600 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 cursor-pointer transition"
                        >
                        <label for="remember" class="ml-3 text-sm text-gray-700 font-semibold cursor-pointer select-none">
                            Keep me signed in
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="btn-premium w-full bg-gradient-to-r from-teal-600 via-teal-700 to-cyan-700 text-white font-bold py-6 rounded-2xl shadow-xl flex items-center justify-center space-x-3 text-base tracking-wide mt-10"
                    >
                        <span>SIGN IN</span>
                        <i class="fas fa-arrow-right text-lg"></i>
                    </button>

                </form>

                <!-- Mobile Copyright -->
                <div class="lg:hidden text-center mt-12 pt-8 border-t border-gray-200">
                    <p class="text-gray-500 text-xs font-medium">&copy; 2025 PT Puri Digital Output. All rights reserved.</p>
                </div>

            </div>

        </div>

    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Enhanced form validation feedback
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.classList.add('border-red-400');
            });
            
            input.addEventListener('input', function() {
                this.classList.remove('border-red-400');
            });
        });

        // Prevent form resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</body>
</html>