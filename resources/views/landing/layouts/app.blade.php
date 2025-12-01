<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Puri Digital Output - Digital Technology Solutions, IT Infrastructure, Security & Manage Services Provider">
    <meta name="keywords" content="IT Infrastructure, Security, Manage Services, SaaS, DaaS, Data Center, IT Consultant">
    <meta name="author" content="Puri Digital Output">
    
    <title>{{ $title ?? 'Puri Digital Output - Digital Technology Solutions' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        },
                        dark: {
                            DEFAULT: '#0f172a',
                            100: '#1e293b',
                            200: '#334155',
                            300: '#475569',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated Background Gradient */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(20, 184, 166, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 20%, rgba(99, 102, 241, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 12px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1e293b;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #14b8a6 0%, #06b6d4 100%);
            border-radius: 10px;
            border: 2px solid #1e293b;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #0d9488 0%, #0891b2 100%);
        }
        
        /* Gradient Text with Animation */
        .gradient-text {
            background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 50%, #8b5cf6 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientFlow 3s ease infinite;
        }
        
        @keyframes gradientFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            position: relative;
        }
        
        .gradient-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 10% 20%, rgba(20, 184, 166, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(6, 182, 212, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        /* Card Hover Effect */
        .card-hover {
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(20, 184, 166, 0.1);
        }
        
        .card-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
            border-radius: inherit;
        }
        
        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(20, 184, 166, 0.25),
                        0 0 0 1px rgba(20, 184, 166, 0.3);
            border-color: rgba(20, 184, 166, 0.3);
        }
        
        .card-hover:hover::before {
            opacity: 1;
        }
        
        /* Glow Effect */
        .glow {
            box-shadow: 0 0 30px rgba(20, 184, 166, 0.6),
                        0 0 60px rgba(20, 184, 166, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 30px rgba(20, 184, 166, 0.6),
                            0 0 60px rgba(20, 184, 166, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(20, 184, 166, 0.8),
                            0 0 80px rgba(20, 184, 166, 0.4);
            }
        }
        
        /* Button Primary */
        .btn-primary {
            background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-primary:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 15px 40px rgba(20, 184, 166, 0.5),
                        0 5px 15px rgba(6, 182, 212, 0.3);
        }
        
        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-primary:active {
            transform: scale(0.98);
        }
        
        /* Overlay Dark */
        .overlay-dark {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.97) 0%, rgba(30, 41, 59, 0.95) 100%);
            backdrop-filter: blur(20px);
        }
        
        /* Section Divider */
        .section-divider {
            background: linear-gradient(90deg, transparent, #14b8a6, #06b6d4, transparent);
            height: 2px;
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Particle Effect */
        .particle {
            position: fixed;
            background: rgba(20, 184, 166, 0.3);
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat 15s linear infinite;
        }
        
        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) translateX(0) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px) scale(1);
                opacity: 0;
            }
        }
        
        /* Content Container */
        .content-container {
            position: relative;
            z-index: 1;
        }
        
        /* Glass Effect */
        .glass {
            background: rgba(30, 41, 59, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(20, 184, 166, 0.2);
        }
        
        /* Shine Effect */
        .shine {
            position: relative;
            overflow: hidden;
        }
        
        .shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 70%
            );
            transform: rotate(45deg);
            animation: shine 3s ease-in-out infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased">
    
    <!-- Particle Background -->
    <div id="particles"></div>
    
    <!-- Main Content -->
    <div class="content-container">
        @yield('content')
    </div>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- AOS Init -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic',
        });
    </script>
    
    <!-- Custom Scripts -->
    <script>
        // Create Floating Particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 20;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.width = Math.random() * 5 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        
        createParticles();
        
        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (navbar) {
                if (window.scrollY > 50) {
                    navbar.classList.add('bg-dark/95', 'backdrop-blur-lg', 'shadow-lg');
                    navbar.style.borderBottom = '1px solid rgba(20, 184, 166, 0.2)';
                } else {
                    navbar.classList.remove('bg-dark/95', 'backdrop-blur-lg', 'shadow-lg');
                    navbar.style.borderBottom = 'none';
                }
            }
        });
        
        // Active Menu on Scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });
        
        // Smooth Scroll for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Parallax Effect
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.parallax');
            
            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
        
        // Cursor Glow Effect (Optional)
        document.addEventListener('mousemove', function(e) {
            const glow = document.createElement('div');
            glow.style.position = 'fixed';
            glow.style.width = '200px';
            glow.style.height = '200px';
            glow.style.borderRadius = '50%';
            glow.style.background = 'radial-gradient(circle, rgba(20, 184, 166, 0.1) 0%, transparent 70%)';
            glow.style.pointerEvents = 'none';
            glow.style.left = (e.clientX - 100) + 'px';
            glow.style.top = (e.clientY - 100) + 'px';
            glow.style.zIndex = '0';
            glow.style.transition = 'opacity 0.3s';
            
            document.body.appendChild(glow);
            
            setTimeout(() => {
                glow.style.opacity = '0';
                setTimeout(() => glow.remove(), 300);
            }, 100);
        });
    </script>
    
    @stack('scripts')
</body>
</html>