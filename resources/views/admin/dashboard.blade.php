@extends('admin.layout')

@section('content')
    <div class="space-y-6">
        <!-- Stats cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="relative rounded-2xl bg-white ring-1 ring-stone-200 p-5 shadow-sm hover:shadow-lg min-h-[120px] flex flex-col justify-between transition-all duration-300 group animate-fade-in-up hover:scale-105 hover:ring-pink-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-stone-700 tracking-wide">Vitamin Terlaris</p>
                        <h3 class="mt-2 text-xl font-extrabold bg-gradient-to-r from-pink-600 to-amber-600 bg-clip-text text-transparent">{{ $topVitamin ? $topVitamin->name : 'Tidak ada data' }}</h3>
                        <p class="mt-1 text-xs text-stone-600">{{ $topVitamin ? ('Tipe: ' . $topVitamin->hair_type) : 'Tambah data vitamin terlebih dahulu' }}</p>
                    </div>
                    <div class="h-8 w-8 rounded-full grid place-items-center bg-gradient-to-br from-pink-50 to-pink-100 ring-1 ring-pink-200 text-pink-600 transition-all duration-300 group-hover:scale-110 group-hover:ring-pink-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-4 w-4"><circle cx="12" cy="12" r="9" stroke-width="1.5"/><path d="M12 7v6l4 2" stroke-width="1.5"/></svg>
                    </div>
                </div>
            </div>
            <div class="relative rounded-2xl bg-white ring-1 ring-stone-200 p-5 shadow-sm hover:shadow-lg min-h-[120px] flex flex-col justify-between transition-all duration-300 group animate-fade-in-up hover:scale-105 hover:ring-pink-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-stone-700 tracking-wide">Total Model Rambut</p>
                        <h3 class="mt-2 text-xl font-extrabold bg-gradient-to-r from-pink-600 to-amber-600 bg-clip-text text-transparent">{{ $totalModels }}</h3>
                        <p class="mt-1 text-xs text-stone-600">Model Tersedia</p>
                    </div>
                    <div class="h-8 w-8 rounded-full grid place-items-center bg-gradient-to-br from-pink-50 to-pink-100 ring-1 ring-pink-200 text-pink-600 transition-all duration-300 group-hover:scale-110 group-hover:ring-pink-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-4 w-4"><path d="M8 11h8M8 15h8M7 7h10" stroke-width="1.5"/></svg>
                    </div>
                </div>
            </div>
            <div class="relative rounded-2xl bg-white ring-1 ring-stone-200 p-5 shadow-sm hover:shadow-lg min-h-[120px] flex flex-col justify-between transition-all duration-300 group animate-fade-in-up hover:scale-105 hover:ring-pink-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-stone-700 tracking-wide">Total Vitamin Rambut</p>
                        <h3 class="mt-2 text-xl font-extrabold bg-gradient-to-r from-pink-600 to-amber-600 bg-clip-text text-transparent">{{ $totalVitamins }}</h3>
                        <p class="mt-1 text-xs text-stone-600">Vitamin Tersedia</p>
                    </div>
                    <div class="h-8 w-8 rounded-full grid place-items-center bg-gradient-to-br from-pink-50 to-pink-100 ring-1 ring-pink-200 text-pink-600 transition-all duration-300 group-hover:scale-110 group-hover:ring-pink-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-4 w-4"><path d="M12 5v14M5 12h14" stroke-width="1.5"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Distribusi Jenis Rambut: Horizontal Bar -->
            <div class="lg:col-span-2 rounded-2xl bg-white ring-1 ring-stone-200 p-5 shadow-sm h-64 overflow-hidden">
                <p class="text-sm font-semibold text-stone-700 tracking-wide">Distribusi Jenis Rambut</p>
                <div class="mt-3 h-full">
                    <canvas id="hairTypeBar" class="w-full h-full"></canvas>
                </div>
            </div>
            <!-- Distribusi Tipe Rambut: Doughnut -->
            <div class="rounded-2xl bg-white ring-1 ring-stone-200 p-5 shadow-sm h-64 overflow-hidden">
                <p class="text-sm font-semibold text-stone-700 tracking-wide">Distribusi Tipe Rambut</p>
                <div class="mt-3 h-full">
                    <canvas id="hairTypePie" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Trend Mingguan Model Rambut: Bar -->
            <div class="rounded-2xl bg-white ring-1 ring-stone-200 p-5 shadow-sm h-64 overflow-hidden">
                <p class="text-sm font-semibold text-stone-700 tracking-wide">Trend Mingguan Model Rambut</p>
                <div class="mt-3 h-full">
                    <canvas id="weeklyModelBar" class="w-full h-full"></canvas>
                </div>
            </div>
            <!-- Trend Mingguan Vitamin Rambut: Bar -->
            <div class="rounded-2xl bg-white ring-1 ring-stone-200 p-5 shadow-sm h-64 overflow-hidden">
                <p class="text-sm font-semibold text-stone-700 tracking-wide">Trend Mingguan Vitamin Rambut</p>
                <div class="mt-3 h-full">
                    <canvas id="weeklyVitaminBar" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Success (Centered, auto-hide) -->
    @if(session('success'))
        <div id="center-notification" class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="relative z-10 w-full max-w-md rounded-2xl bg-white ring-1 ring-stone-200 shadow-xl px-6 py-5 text-center">
                    <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-pink-100 text-pink-700 ring-1 ring-pink-200">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5"><path d="M9 12l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke-width="1.5"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-pink-900">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Charts script (Chart.js CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // Colors that match the pink theme
        const colors = {
            pink: 'rgba(244, 114, 182, 0.8)', // pink-400
            pinkSoft: 'rgba(244, 114, 182, 0.25)',
            amber: 'rgba(251, 191, 36, 0.8)', // amber-400
            stone: 'rgba(168, 162, 158, 0.8)',
            green: 'rgba(52, 211, 153, 0.8)',
            blue: 'rgba(59, 130, 246, 0.8)'
        };

        // Distribusi Jenis Rambut - Horizontal Bar
        const hairTypeCtx = document.getElementById('hairTypeBar');
        if (hairTypeCtx) {
            new Chart(hairTypeCtx, {
                type: 'bar',
                data: {
                    labels: ['Lurus', 'Ikal', 'Bergelombang', 'Keriting'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [85, 60, 40, 25],
                        backgroundColor: colors.pink,
                        borderRadius: 8,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    transitions: { active: { animation: { duration: 0 } } },
                    layout: { padding: { left: 12, right: 12, top: 10, bottom: 10 } },
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { color: '#525252', padding: 8 } },
                        y: { grid: { display: false }, border: { display: false }, ticks: { color: '#525252', padding: 8 } }
                    }
                }
            });
        }

        // Distribusi Tipe Rambut - Doughnut
        const hairTypePieCtx = document.getElementById('hairTypePie');
        if (hairTypePieCtx) {
            new Chart(hairTypePieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Ratak', 'Tebal', 'Tipis', 'Kering'],
                    datasets: [{
                        data: [35, 25, 20, 20],
                        backgroundColor: [colors.pink, colors.amber, colors.blue, colors.green],
                        borderColor: '#fff',
                        borderWidth: 2,
                        hoverOffset: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    transitions: { active: { animation: { duration: 0 } } },
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#525252', padding: 12 } }
                    },
                    cutout: '60%'
                }
            });
        }

        // Trend Mingguan Model Rambut - Bar
        const weeklyModelCtx = document.getElementById('weeklyModelBar');
        if (weeklyModelCtx) {
            new Chart(weeklyModelCtx, {
                type: 'bar',
                data: {
                    labels: ['Oval Layer + Curtain Bangs', 'Butterfly haircut', 'Wolf cut'],
                    datasets: [{
                        label: 'Rekomendasi',
                        data: [50, 30, 20],
                        backgroundColor: colors.pink,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    transitions: { active: { animation: { duration: 0 } } },
                    layout: { padding: { left: 12, right: 12, top: 10, bottom: 16 } },
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { color: '#525252', padding: 8, maxRotation: 0, minRotation: 0 } },
                        y: { grid: { display: false }, border: { display: false }, ticks: { color: '#525252', padding: 8 } }
                    }
                }
            });
        }

        // Trend Mingguan Vitamin Rambut - Bar
        const weeklyVitaminCtx = document.getElementById('weeklyVitaminBar');
        if (weeklyVitaminCtx) {
            new Chart(weeklyVitaminCtx, {
                type: 'bar',
                data: {
                    labels: ['Vitamin A', 'Vitamin B', 'Vitamin C'],
                    datasets: [{
                        label: 'Pemakaian',
                        data: [50, 30, 20],
                        backgroundColor: colors.amber,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    transitions: { active: { animation: { duration: 0 } } },
                    layout: { padding: { left: 12, right: 12, top: 10, bottom: 16 } },
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { color: '#525252', padding: 8, maxRotation: 0, minRotation: 0 } },
                        y: { grid: { display: false }, border: { display: false }, ticks: { color: '#525252', padding: 8 } }
                    }
                }
            });
        }

        // Auto hide centered notification
        document.addEventListener('DOMContentLoaded', function () {
            const notif = document.getElementById('center-notification');
            if (notif) setTimeout(() => notif.classList.add('hidden'), 2500);
        });
    </script>
@endsection