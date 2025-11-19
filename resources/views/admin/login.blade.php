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
        <main class="min-h-screen grid place-items-center px-4">
            <section class="mx-auto w-full max-w-5xl bg-white rounded-2xl shadow-lg ring-1 ring-stone-200 overflow-hidden grid grid-cols-1 lg:grid-cols-2 lg:divide-x lg:divide-stone-200">
                <!-- Ilustrasi kiri -->
                <div class="relative hidden lg:block bg-pink-50">
                    <div class="p-8 flex flex-col items-center">
                        <img src="{{ asset('img/login.png') }}" alt="Ilustrasi Login" class="max-w-md w-full h-auto" />
                        <p class="mt-4 text-center font-semibold text-stone-700">Trendy Salon Management</p>
                    </div>
                </div>

                <!-- Form kanan -->
                <div class="p-6 sm:p-10">
                    <div class="text-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo Trendy Salon" class="mx-auto h-20 sm:h-24 w-auto rounded-sm shadow" />
                        <h1 class="mt-3 text-lg font-semibold">Admin & Super Admin Login</h1>
                        <p class="text-xs text-stone-600">Gunakan akun Admin atau Super Admin untuk masuk</p>

                    @if (session('error'))
                        <div class="mt-4 rounded-xl bg-red-50 text-red-700 ring-1 ring-red-200 px-4 py-3 text-sm">{{ session('error') }}</div>
                    @endif
                    </div>

                    <form class="mt-8 space-y-4" method="post" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div>
                            <label for="email" class="text-xs font-semibold text-stone-700">Email</label>
                            <input id="email" type="email" name="email" placeholder="admin@contoh.com" class="mt-1 w-full rounded-md border border-stone-300 bg-stone-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300" />
                        </div>
                        <div>
                            <label for="password" class="text-xs font-semibold text-stone-700">Password</label>
                            <input id="password" type="password" name="password" placeholder="••••••••" class="mt-1 w-full rounded-md border border-stone-300 bg-stone-50 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300" />
                        </div>

                        <div class="flex items-center justify-between">
                           
                       
                        </div>

                        <button type="submit" class="mt-4 w-full rounded-md bg-pink-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-pink-700 active:translate-y-px">Login</button>
                    </form>

                  
            </section>
        </main>
    </body>
 </html>