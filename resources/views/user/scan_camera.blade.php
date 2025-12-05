<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        <title>Scan Wajah & Rambut</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji']
                    }
                }
            };
        </script>
        <script>
            // Expose absolute URLs for scan routes so JS can navigate correctly under subdirectories
            window.__SCAN_ROUTES__ = {
                camera: "{{ url('/scan/camera') }}",
                results: "{{ url('/scan/results') }}",
            };
        </script>
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="bg-stone-200 font-sans text-stone-800" id="scanCameraPage">
        <!-- Header -->
        <header class="sticky top-0 z-30 bg-pink-200/90 backdrop-blur border-b border-pink-300/40 transition-all duration-300 shadow-sm">
            <div class="mx-auto max-w-screen-md px-3 sm:px-4 py-2.5 sm:py-3 flex items-center gap-2 sm:gap-3">
                <a href="{{ route('scan') }}" class="grid h-8 w-8 sm:h-9 sm:w-9 place-items-center rounded-lg border border-stone-300 bg-pink-100/60 text-stone-700 touch-manipulation transition-all duration-300 hover:bg-pink-200 hover:scale-110 hover:shadow-md group" aria-label="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-4 w-4 sm:h-5 sm:w-5 transition-transform duration-300 group-hover:-translate-x-1"><path d="M15 6l-6 6 6 6" stroke-width="1.5"/></svg>
                </a>
                <h1 class="text-base sm:text-lg font-semibold bg-gradient-to-r from-pink-600 to-amber-600 bg-clip-text text-transparent animate-gradient">Scan Wajah & Rambut</h1>
            </div>
        </header>

        <main class="mx-auto max-w-screen-md px-3 sm:px-4">
            <section class="mt-3 sm:mt-4 animate-fade-in-up">
                <div class="rounded-xl bg-gradient-to-r from-pink-100 via-pink-50 to-amber-100 px-3 sm:px-4 py-3 sm:py-4 text-center shadow-md animate-pulse-slow">
                    <p class="text-[10px] sm:text-xs text-stone-700 leading-relaxed font-medium">Posisikan wajah dan rambut Anda di tengah frame<br/>untuk hasil terbaik</p>
                </div>

                <div class="mt-3 sm:mt-4 rounded-xl bg-stone-300 p-2 sm:p-4 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="rounded-xl border-2 border-sky-500 bg-stone-200 grid place-items-center h-[280px] sm:h-[360px] md:h-[420px] relative overflow-hidden animate-border-pulse">
                        <!-- Animated border effect -->
                        <div class="absolute inset-0 rounded-xl border-2 border-pink-400 opacity-0 animate-border-glow"></div>
                        
                        <video id="cameraVideo" autoplay playsinline class="h-full w-full object-cover rounded-lg hidden transition-opacity duration-500"></video>
                        <div id="cameraPlaceholder" class="grid place-items-center animate-fade-in">
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-10 w-10 sm:h-14 sm:w-14 text-stone-700 animate-pulse-slow"><path d="M4 7h3l2-2h6l2 2h3a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z" stroke-width="1.5"/><circle cx="12" cy="13" r="3.5" stroke-width="1.5"/></svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="h-16 w-16 sm:h-20 sm:w-20 border-2 border-pink-400 rounded-full animate-spin-slow"></div>
                                </div>
                            </div>
                            <p class="mt-4 text-xs sm:text-sm text-stone-600 animate-fade-in">Memuat kamera...</p>
                        </div>
                        <canvas id="cameraCanvas" class="hidden"></canvas>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 flex justify-center">
                    <button id="captureBtn" type="button" aria-label="Ambil foto" class="group relative grid h-20 w-20 sm:h-24 sm:w-24 place-items-center rounded-full bg-gradient-to-br from-pink-400 to-pink-500 ring-4 ring-pink-200 shadow-xl transition-all duration-300 active:scale-95 touch-manipulation hover:scale-110 hover:shadow-2xl hover:ring-pink-300 animate-pulse-slow">
                        <!-- Outer ring animation -->
                        <div class="absolute inset-0 rounded-full border-4 border-pink-300 opacity-0 group-hover:opacity-100 group-hover:scale-125 transition-all duration-500"></div>
                        
                        <!-- Inner glow -->
                        <div class="absolute inset-2 rounded-full bg-white/30 blur-sm group-hover:bg-white/50 transition-all duration-300"></div>
                        
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-10 w-10 sm:h-12 sm:w-12 text-white relative z-10 transition-transform duration-300 group-hover:scale-110">
                            <path d="M4 7h3l2-2h6l2 2h3a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z" stroke-width="1.5"/>
                            <circle cx="12" cy="13" r="3.5" stroke-width="1.5"/>
                        </svg>
                        
                        <!-- Ripple effect on click -->
                        <div class="absolute inset-0 rounded-full bg-white/50 scale-0 group-active:scale-150 group-active:opacity-0 transition-all duration-500"></div>
                    </button>
                </div>
                
                <!-- Helper text -->
                <p class="mt-4 text-center text-[10px] sm:text-xs text-stone-600 animate-fade-in">
                    Tekan tombol untuk mengambil foto
                </p>
            </section>
        </main>

        <!-- Enhanced styles with animations -->
        <style>
            /* Fade in animations */
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes pulseSlow {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.9; transform: scale(1.02); }
            }
            
            @keyframes borderPulse {
                0%, 100% { border-color: rgb(14 165 233); }
                50% { border-color: rgb(236 72 153); }
            }
            
            @keyframes borderGlow {
                0%, 100% { opacity: 0; }
                50% { opacity: 1; }
            }
            
            @keyframes spinSlow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            @keyframes gradient {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }
            
            .animate-fade-in {
                animation: fadeIn 0.6s ease-out;
            }
            
            .animate-fade-in-up {
                animation: fadeInUp 0.8s ease-out;
            }
            
            .animate-pulse-slow {
                animation: pulseSlow 2s ease-in-out infinite;
            }
            
            .animate-border-pulse {
                animation: borderPulse 3s ease-in-out infinite;
            }
            
            .animate-border-glow {
                animation: borderGlow 2s ease-in-out infinite;
            }
            
            .animate-spin-slow {
                animation: spinSlow 3s linear infinite;
            }
            
            .animate-gradient {
                background-size: 200% 200%;
                animation: gradient 3s ease infinite;
            }
            
            /* Camera video fade in */
            #cameraVideo:not(.hidden) {
                animation: fadeIn 0.5s ease-out;
            }
            
            /* Hide placeholder when video is active */
            #cameraVideo:not(.hidden) ~ #cameraPlaceholder {
                display: none;
            }
            
            /* Smooth transitions */
            * {
                transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>
        
        <script>
            // Enhanced camera loading animation
            document.addEventListener('DOMContentLoaded', () => {
                const video = document.getElementById('cameraVideo');
                const placeholder = document.getElementById('cameraPlaceholder');
                
                if (video) {
                    video.addEventListener('loadedmetadata', () => {
                        video.classList.remove('hidden');
                        if (placeholder) {
                            placeholder.style.opacity = '0';
                            setTimeout(() => {
                                placeholder.style.display = 'none';
                            }, 500);
                        }
                    });
                    
                    video.addEventListener('play', () => {
                        video.style.opacity = '1';
                    });
                }
            });
        </script>
    </body>
</html>