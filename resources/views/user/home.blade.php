<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Trendy Salon - Home</title>
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
        <!-- Header -->
        <header class="sticky top-0 z-30 bg-pink-200/90 backdrop-blur">
            <div class="mx-auto max-w-screen-md px-4 py-3 flex items-center gap-3">
                <a href="{{ route('admin.login') }}" class="inline-flex items-center" title="Login Admin">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Trendy Salon" class="h-10 w-auto rounded-sm shadow hover:opacity-90" />
                </a>
                <div class="leading-tight">
                    <p class="text-xs tracking-widest text-stone-600">Trendy Salon</p>
                    <p class="text-sm font-medium">Layanan Terbaik Kami</p>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-screen-md px-4">
            <!-- Hero Slider -->
            <section class="mt-4">
                <div id="heroSlider" class="relative overflow-hidden rounded-xl shadow-lg">
                    <!-- Slides -->
                    <div class="slide">
                        <img src="{{ asset('img/home.jpg') }}" alt="Perawatan Rambut" class="w-full h-64 sm:h-80 object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <p class="text-2xl sm:text-3xl font-extrabold">Rasakan Perawatan Premium</p>
                            <p class="text-xl sm:text-2xl font-semibold mt-1">Dengan Sentuhan Profesional</p>
                            <p class="mt-2 text-xs sm:text-sm tracking-widest">DI TRENDY SALON</p>
                        </div>
                        <div class="absolute top-4 right-4">
                            <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-full bg-white/70 grid place-items-center text-[10px] sm:text-xs font-semibold text-stone-700">
                                STAY<br/>SAFE &<br/>HEALTHY
                            </div>
                        </div>
                    </div>
                    <!-- Single image, no controls -->
                </div>
            </section>

            <!-- Intro & CTA -->
            <section class="mt-6 rounded-xl bg-stone-100 px-4 py-6 shadow">
                <h2 class="text-center text-xl sm:text-2xl font-extrabold uppercase tracking-wide">TEMUKAN MODEL POTONGAN<br class="sm:hidden"/> RAMBUT TERBAIK ANDA!</h2>
                <div class="mt-2 flex items-center justify-center gap-4">
                    <span class="h-[2px] w-24 bg-amber-300/70"></span>
                    <span class="text-amber-400">🌀</span>
                    <span class="h-[2px] w-24 bg-amber-300/70"></span>
                </div>
                <p class="mt-4 text-center text-sm text-stone-700 leading-relaxed">Biarkan <span class="inline-block rounded-md bg-pink-300/60 px-2 py-0.5 font-semibold text-stone-900">TrendyLook</span> menganalisis<br/>Bentuk Wajahmu Dan Temukan Gaya<br/>Rambut Terbaik Untuk Tampil Lebih Percaya Diri!</p>
                <div class="mt-4 flex justify-center">
                    <a href="{{ route('scan') }}" class="inline-flex items-center gap-3 rounded-2xl bg-pink-300/80 px-6 py-3 text-sm font-semibold text-stone-900 shadow-md hover:bg-pink-400 active:translate-y-px active:shadow">
                        <!-- Icon kamera -->
                        <span class="grid h-8 w-8 place-items-center rounded-xl bg-pink-100/60 border border-stone-300 text-stone-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5">
                                <path d="M4 7h3l2-2h6l2 2h3a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z" stroke-width="1.5"/>
                                <circle cx="12" cy="13" r="3.5" stroke-width="1.5"/>
                            </svg>
                        </span>
                        Mulai Scan Sekarang
                        <!-- Chevron kanan -->
                        <span class="grid h-6 w-6 place-items-center rounded-full bg-pink-100/60 border border-stone-300 text-stone-700">›</span>
                    </a>
                </div>
            </section>

            <!-- Visit Section -->
            <section class="mt-8">
                <h3 class="text-center text-xl font-semibold">Kunjungi Trendy Salon<br class="sm:hidden"/> Terdekat Anda Sekarang</h3>
                <div class="mt-5 flex justify-center">
                    <div class="relative">
                        <div class="h-44 w-44 overflow-hidden rounded-full shadow-lg">
                            <img src="{{ asset('img/home2.jpg') }}" alt="Salon" class="h-full w-full object-cover"/>
                        </div>
                        <!-- Badge di luar lingkaran -->
                        <div class="absolute -right-6 top-6">
                            <div class="h-20 w-20 rounded-full bg-amber-200 shadow grid place-items-center text-center text-[10px] font-bold text-stone-700 tracking-wide">
                                BUKA<br/>SETIAP HARI<br/>
                                <span class="font-bold">08:00-20:00 WIB</span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mx-auto mt-5 max-w-sm rounded-xl bg-stone-100 px-4 py-3 text-center text-xs font-semibold text-stone-700 shadow tracking-wide">
                    *DISARANKAN <span class="font-bold">RESERVASI</span> TERLEBIH DAHULU
                </p>
            </section>

            <!-- Branches -->
            <section class="mt-6 space-y-4">
                <div class="flex items-start gap-3 rounded-xl bg-white p-4 shadow">
                    <div class="grid h-6 w-6 place-items-center rounded-full bg-amber-200">›</div>
                    <div>
                        <p class="font-semibold">Cabang Giwangan</p>
                        <p class="text-sm text-stone-600">Jl. Pramuka No39A, Prenggan, Kec. Kotagede, Kota Yogyakarta, DIY</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 rounded-xl bg-white p-4 shadow">
                    <div class="grid h-6 w-6 place-items-center rounded-full bg-amber-200">›</div>
                    <div>
                        <p class="font-semibold">Cabang Sapen</p>
                        <p class="text-sm text-stone-600">Jl. bimaskati No45 A, Demangan, Kec. Gondokusuman, Kota Yogyakarta, DIY</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 rounded-xl bg-white p-4 shadow">
                    <div class="grid h-6 w-6 place-items-center rounded-full bg-amber-200">›</div>
                    <div>
                        <p class="font-semibold">Cabang Kotabaru</p>
                        <p class="text-sm text-stone-600">Jl. Kiasakti Timur No14, Kotabaru, Kec. Danurejan, Kota Yogyakarta, DIY</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 rounded-xl bg-white p-4 shadow">
                    <div class="grid h-6 w-6 place-items-center rounded-full bg-amber-200">›</div>
                    <div>
                        <p class="font-semibold">Cabang Seturan</p>
                        <p class="text-sm text-stone-600">Jl. Sekolan Mataram No430, Pringwulung, Condongcatur, Kec. Depok, Kabupaten Sleman, DIY</p>
                    </div>
                </div>
            </section>

            <footer class="mt-10 pb-10 text-center text-xs text-stone-500">
                &copy; {{ date('Y') }} Trendy Salon
            </footer>
        </main>

        <!-- Small helper styles for slider -->
        <style>
            #heroSlider .slide { position: relative; }
        </style>
    </body>
</html>