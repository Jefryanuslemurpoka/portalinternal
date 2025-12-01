<section id="contact" class="py-20 lg:py-32 bg-dark-100 relative overflow-hidden">
    
    <!-- Background Decoration -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary-500/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <div class="inline-flex items-center px-4 py-2 bg-primary-500/10 border border-primary-500/30 rounded-full mb-6">
                <span class="text-primary-400 text-sm font-medium">Get In Touch</span>
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                <span class="text-white">Mari Berdiskusi Tentang</span>
                <span class="gradient-text block mt-2">Kebutuhan IT Anda</span>
            </h2>
            <p class="text-lg text-gray-400 leading-relaxed">
                Tim kami siap membantu Anda menemukan solusi terbaik untuk transformasi digital bisnis Anda
            </p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
            
            <!-- Left Side - Contact Form -->
            <div data-aos="fade-right">
                
                <!-- Success/Error Message -->
                @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 mb-6 flex items-start space-x-3">
                    <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="text-green-400 font-semibold mb-1">Berhasil!</h4>
                        <p class="text-sm text-gray-300">{{ session('success') }}</p>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 flex items-start space-x-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="text-red-400 font-semibold mb-1">Error!</h4>
                        <p class="text-sm text-gray-300">{{ session('error') }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Contact Form -->
                <form action="{{ route('landing.contact') }}" method="POST" class="bg-dark rounded-2xl p-8 border border-gray-800">
                    @csrf
                    
                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-white mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 bg-dark-100 border border-gray-800 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-primary-500 transition-colors duration-300"
                               placeholder="Masukkan nama lengkap Anda"
                               required>
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-white mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 bg-dark-100 border border-gray-800 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-primary-500 transition-colors duration-300"
                               placeholder="nama@perusahaan.com"
                               required>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Phone -->
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-semibold text-white mb-2">
                            No. Telepon
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 bg-dark-100 border border-gray-800 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-primary-500 transition-colors duration-300"
                               placeholder="08xxxxxxxxxx">
                        @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Subject -->
                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-semibold text-white mb-2">
                            Subjek
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               class="w-full px-4 py-3 bg-dark-100 border border-gray-800 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-primary-500 transition-colors duration-300"
                               placeholder="Konsultasi IT Infrastructure">
                        @error('subject')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Message -->
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-semibold text-white mb-2">
                            Pesan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="5"
                                  class="w-full px-4 py-3 bg-dark-100 border border-gray-800 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-primary-500 transition-colors duration-300 resize-none"
                                  placeholder="Ceritakan kebutuhan IT Anda..."
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full btn-primary px-8 py-4 rounded-xl text-white font-semibold flex items-center justify-center space-x-2 group">
                        <span>Kirim Pesan</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                    
                </form>
                
            </div>
            
            <!-- Right Side - Contact Info & Map -->
            <div data-aos="fade-left">
                
                <!-- Contact Cards -->
                <div class="space-y-6 mb-8">
                    
                    <!-- Office Address -->
                    <div class="bg-dark rounded-2xl p-6 border border-gray-800 hover:border-primary-500/50 transition-all duration-300 group">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-14 h-14 bg-primary-500/10 rounded-xl flex items-center justify-center group-hover:bg-primary-500/20 transition-colors duration-300">
                                <svg class="w-7 h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-white mb-2 group-hover:text-primary-400 transition-colors duration-300">Office Address</h4>
                                <p class="text-gray-400 text-sm leading-relaxed">
                                    Ruko Florite Blok FR-46, Gading Serpong, Pakulonan Barat, Kelapa Dua, Kab. Tangerang, Banten, 15810
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="bg-dark rounded-2xl p-6 border border-gray-800 hover:border-cyan-500/50 transition-all duration-300 group">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-14 h-14 bg-cyan-500/10 rounded-xl flex items-center justify-center group-hover:bg-cyan-500/20 transition-colors duration-300">
                                <svg class="w-7 h-7 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-white mb-2 group-hover:text-cyan-400 transition-colors duration-300">Email Address</h4>
                                <p class="text-gray-400 text-sm mb-1">
                                    <strong class="text-white">Support:</strong> <a href="mailto:support@purido.co.id" class="hover:text-cyan-400 transition-colors duration-300">support@purido.co.id</a>
                                </p>
                                <p class="text-gray-400 text-sm">
                                    <strong class="text-white">General:</strong> <a href="mailto:contact@purido.co.id" class="hover:text-cyan-400 transition-colors duration-300">contact@purido.co.id</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="bg-dark rounded-2xl p-6 border border-gray-800 hover:border-green-500/50 transition-all duration-300 group">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-14 h-14 bg-green-500/10 rounded-xl flex items-center justify-center group-hover:bg-green-500/20 transition-colors duration-300">
                                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-white mb-2 group-hover:text-green-400 transition-colors duration-300">Phone Number</h4>
                                <p class="text-gray-400 text-sm">
                                    <a href="tel:628131010672" class="hover:text-green-400 transition-colors duration-300 font-semibold text-lg text-white">
                                        +62-813-1010-672
                                    </a>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Available: Monday - Friday, 09:00 - 17:00 WIB</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Business Hours -->
                    <div class="bg-gradient-to-br from-primary-500/10 to-cyan-500/10 rounded-2xl p-6 border border-primary-500/30">
                        <h4 class="text-lg font-bold text-white mb-4 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Business Hours</span>
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-gray-400">
                                <span>Monday - Friday</span>
                                <span class="text-white font-semibold">08:00 - 16:00 WIB</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Saturday</span>
                                <span class="text-white font-semibold">08:00 - 12:00 WIB</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Sunday</span>
                                <span class="text-red-400 font-semibold">Closed</span>
                            </div>
                            <div class="pt-3 mt-3 border-t border-gray-800">
                                <p class="text-primary-400 font-semibold">24/7 Technical Support Available</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Google Maps -->
                <div class="bg-dark rounded-2xl p-4 border border-gray-800 overflow-hidden">
                    <div class="aspect-video rounded-xl overflow-hidden">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2234567890123!2d106.62345!3d-6.2345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTQnMDQuMiJTIDEwNsKwMzcnMjQuNCJF!5e0!3m2!1sen!2sid!4v1234567890" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="grayscale hover:grayscale-0 transition-all duration-300">
                        </iframe>
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </div>
</section>