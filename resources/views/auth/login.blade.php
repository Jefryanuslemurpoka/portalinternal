<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Internal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-teal-50 to-cyan-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Card Login -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-teal-100">
            
            <!-- Header -->
            <div class="bg-gradient-to-br from-teal-500 to-cyan-600 p-10 text-center">
                <div class="w-16 h-16 bg-white/95 rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-lg">
                    <i class="fas fa-building text-3xl text-teal-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1 tracking-tight">Portal Internal</h1>
                <p class="text-teal-50 text-sm font-light">Sistem Manajemen Karyawan</p>
            </div>

            <!-- Form Login -->
            <div class="p-8">
                
                <!-- Alert Success -->
                @if(session('success'))
                <div class="mb-6 p-4 bg-teal-50 border border-teal-200 text-teal-700 rounded-xl flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Alert Error -->
                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    @foreach($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="email">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-teal-500">
                                <i class="fas fa-envelope text-sm"></i>
                            </span>
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                value="{{ old('email') }}"
                                class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition @error('email') border-red-300 @enderror"
                                placeholder="nama@email.com"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-teal-500">
                                <i class="fas fa-lock text-sm"></i>
                            </span>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="w-full pl-11 pr-12 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition @error('password') border-red-300 @enderror"
                                placeholder="••••••••"
                                required
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute right-4 top-3.5 text-gray-400 hover:text-teal-600 transition"
                            >
                                <i class="fas fa-eye text-sm" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-6 flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-400"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-600">
                            Ingat Saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-medium py-3.5 rounded-xl hover:from-teal-600 hover:to-cyan-700 transition duration-200 shadow-md hover:shadow-lg"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>

                </form>

                <!-- Info Akun Demo -->
                <div class="mt-6 p-4 bg-teal-50/50 rounded-xl border border-teal-100">
                    <p class="text-xs text-gray-500 font-semibold mb-2.5">Akun Demo:</p>
                    <div class="text-xs text-gray-600 space-y-1.5">
                        <p><span class="font-medium text-teal-700">Admin:</span> admin@portal.com / admin123</p>
                        <p><span class="font-medium text-teal-700">Karyawan:</span> budi@portal.com / karyawan123</p>
                    </div>
                </div>

            </div>

        </div>

        <!-- Footer -->
        <p class="text-center text-teal-700 text-sm mt-6 font-light">
            &copy; 2025 Portal Internal. All rights reserved.
        </p>

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
    </script>

</body>
</html>