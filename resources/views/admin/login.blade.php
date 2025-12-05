<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Login - Trendy Salon</title>
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
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="bg-stone-200 font-sans text-stone-800">
        <main class="min-h-screen grid place-items-center px-4 py-8">
            <section class="mx-auto w-full max-w-5xl bg-white rounded-2xl shadow-xl ring-1 ring-stone-200 overflow-hidden grid grid-cols-1 lg:grid-cols-2 lg:divide-x lg:divide-stone-200 animate-fade-in-up">
                <!-- Ilustrasi kiri -->
                <div class="relative hidden lg:block bg-gradient-to-br from-pink-50 via-pink-100/50 to-amber-50">
                    <div class="p-8 flex flex-col items-center animate-fade-in">
                        <div class="relative group">
                            <img src="{{ asset('img/login.png') }}" alt="Ilustrasi Login" class="max-w-md w-full h-auto transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-pink-200/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                        <p class="mt-4 text-center font-semibold text-stone-700 bg-gradient-to-r from-pink-600 to-amber-600 bg-clip-text text-transparent animate-gradient">Trendy Salon Management</p>
                    </div>
                </div>

                <!-- Form kanan -->
                <div class="p-6 sm:p-10 animate-fade-in-up">
                    <div class="text-center">
                        <div class="relative inline-block group">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Trendy Salon" class="mx-auto h-20 sm:h-24 w-auto rounded-sm shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl" />
                            <div class="absolute inset-0 rounded-sm bg-pink-200/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <h1 class="mt-3 text-lg font-semibold bg-gradient-to-r from-pink-600 to-amber-600 bg-clip-text text-transparent animate-gradient">Admin & Super Admin Login</h1>
                        <p class="text-xs text-stone-600 mt-1">Gunakan akun Admin atau Super Admin untuk masuk</p>

                    @if (session('error'))
                        <div class="mt-4 rounded-xl bg-red-50 text-red-700 ring-1 ring-red-200 px-4 py-3 text-sm animate-shake border-l-4 border-red-500">{{ session('error') }}</div>
                    @endif
                    </div>

                    <form class="mt-8 space-y-5" method="post" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="group animate-fade-in-up">
                            <label for="email" class="text-xs font-semibold text-stone-700 flex items-center gap-1">
                                <span>Email</span>
                                <span class="text-pink-600">*</span>
                            </label>
                            <input id="email" type="email" name="email" placeholder="admin@contoh.com" class="mt-1.5 w-full rounded-lg border border-stone-300 bg-stone-50 px-3 py-2.5 text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-pink-400 focus:bg-white group-hover:border-pink-300" />
                        </div>
                        <div class="group animate-fade-in-up">
                            <label for="password" class="text-xs font-semibold text-stone-700 flex items-center gap-1">
                                <span>Password</span>
                                <span class="text-pink-600">*</span>
                            </label>
                            <input id="password" type="password" name="password" placeholder="••••••••" class="mt-1.5 w-full rounded-lg border border-stone-300 bg-stone-50 px-3 py-2.5 text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-pink-400 focus:bg-white group-hover:border-pink-300" />
                        </div>

                        <div class="flex items-center justify-between">
                           
                       
                        </div>

                        <button type="submit" class="group relative mt-6 w-full rounded-lg bg-gradient-to-r from-pink-600 to-pink-700 px-4 py-2.5 text-sm font-semibold text-white shadow-lg hover:shadow-xl hover:from-pink-700 hover:to-pink-800 active:translate-y-px transition-all duration-300 overflow-hidden">
                            <!-- Shimmer effect -->
                            <span class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></span>
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-4 w-4">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke-width="1.5"/>
                                    <path d="M10 17l5-5-5-5" stroke-width="1.5"/>
                                    <path d="M15 12H3" stroke-width="1.5"/>
                                </svg>
                                Login
                            </span>
                        </button>
                    </form>

                  
            </section>
        </main>
        
        <!-- Enhanced styles with animations -->
        <style>
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
            
            @keyframes gradient {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            
            .animate-fade-in {
                animation: fadeIn 0.6s ease-out;
            }
            
            .animate-fade-in-up {
                animation: fadeInUp 0.8s ease-out;
            }
            
            .animate-gradient {
                background-size: 200% 200%;
                animation: gradient 3s ease infinite;
            }
            
            .animate-shake {
                animation: shake 0.5s ease-in-out;
            }
            
            /* Smooth transitions */
            * {
                transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>
    </body>
 </html>