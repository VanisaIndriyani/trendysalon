@extends('super.layout')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold">Manajemen Admin</h1>
            <p class="mt-1 text-sm text-stone-600">Buat akun admin baru dan reset password otomatis.</p>
        </div>
        <button id="btn-open-add" class="inline-flex items-center gap-2 rounded-xl bg-pink-500 text-white px-4 py-2 shadow hover:bg-pink-600 transition">
            <span class="text-lg">+</span>
            <span class="font-semibold">Tambah Admin</span>
        </button>
    </div>

    <!-- Search -->
    <div class="rounded-2xl ring-1 ring-stone-200 bg-white px-4 py-3 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5 text-stone-500"><circle cx="11" cy="11" r="7" stroke-width="1.5"/><path d="M20 20l-3-3" stroke-width="1.5"/></svg>
        <input id="admin-search" type="text" placeholder="Cari Admin..." class="w-full bg-transparent outline-none text-sm" autocomplete="off" />
    </div>

    <!-- (diubah) Notifikasi Error inline dihapus, diganti pop-up center di bawah -->

    <!-- Admin List -->
    <div class="rounded-2xl ring-1 ring-stone-200 bg-white overflow-hidden">
        <div class="grid grid-cols-[2fr_2fr_1fr] gap-2 px-6 py-3 text-sm font-semibold text-pink-800 bg-pink-50">
            <div>Nama</div>
            <div>Email</div>
            <div>Aksi</div>
        </div>
        <div class="divide-y">
            @forelse ($admins as $admin)
                <div class="grid grid-cols-[2fr_2fr_1fr] gap-2 px-6 py-3 text-sm items-center">
                    <div class="font-medium">{{ $admin->name }}</div>
                    <div class="text-stone-700">{{ $admin->email }}</div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('super.admins.reset', $admin) }}" class="inline">
                            @csrf
                            <button class="rounded-xl bg-stone-100 px-3 py-1.5 text-xs hover:bg-pink-50 ring-1 ring-stone-200">Reset Password</button>
                        </form>
                        <button type="button" class="rounded-xl bg-red-600 text-white px-3 py-1.5 text-xs hover:bg-red-700 btn-delete" data-email="{{ $admin->email }}" data-action="{{ route('super.admins.destroy', $admin) }}">Hapus</button>
                    </div>
                </div>
            @empty
                <div class="px-6 py-6 text-sm text-stone-600">Belum ada admin.</div>
            @endforelse
        </div>
    </div>

    <!-- Center Notification Modal (success) -->
    @if(session('success'))
        <div id="center-notif" class="fixed inset-0 z-50 grid place-items-center">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="relative z-10 bg-white rounded-2xl ring-1 ring-pink-200 p-6 shadow-xl flex items-center gap-3">
                <div class="h-8 w-8 rounded-full grid place-items-center bg-pink-50 ring-1 ring-pink-200 text-pink-600">✓</div>
                <div>
                    <p class="font-semibold text-stone-900">Berhasil</p>
                    @php
                        $__msg = session('success');
                        $__email = null; $__pwd = null;
                        if (preg_match("/Password baru untuk '([^']+)' adalah: (\S+)/", $__msg, $m)) {
                            $__email = $m[1];
                            $__pwd = $m[2];
                        }
                    @endphp
                    @if($__pwd)
                        <p class="text-sm text-stone-600">Password baru untuk '<span class="font-medium">{{ $__email }}</span>' adalah: <span class="text-pink-600 font-semibold">{{ $__pwd }}</span></p>
                    @else
                        <p class="text-sm text-stone-600">{{ $__msg }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Center Notification Modal (error) -->
    @if(session('error'))
        <div id="center-error" class="fixed inset-0 z-50 grid place-items-center">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="relative z-10 bg-white rounded-2xl ring-1 ring-red-200 p-6 shadow-xl flex items-center gap-3">
                <div class="h-8 w-8 rounded-full grid place-items-center bg-red-50 ring-1 ring-red-200 text-red-600">!</div>
                <div>
                    <p class="font-semibold text-stone-900">Gagal</p>
                    <p class="text-sm text-stone-600">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal: Tambah Admin -->
    <div id="modal-add" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-40">
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-lg max-h-[80vh] overflow-y-auto rounded-2xl bg-white ring-1 ring-stone-200 shadow-xl">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg">Tambah Admin</h3>
                </div>
                <form method="POST" action="{{ route('super.admins.store') }}" class="px-6 py-4 space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium">Nama</label>
                        <input name="name" type="text" class="mt-1 w-full rounded-xl ring-1 ring-stone-200 px-3 py-2" required />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input name="email" type="email" class="mt-1 w-full rounded-xl ring-1 ring-stone-200 px-3 py-2" required />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Password</label>
                        <input name="password" type="text" class="mt-1 w-full rounded-xl ring-1 ring-stone-200 px-3 py-2" placeholder="min 8 karakter" required />
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" class="px-4 py-2 rounded-xl ring-1 ring-stone-200 bg-stone-50 hover:bg-stone-100" data-close="#modal-add">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-pink-600 text-white hover:bg-pink-700">Buat Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Konfirmasi Hapus Admin -->
    <div id="modal-confirm" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-40">
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-sm max-h-[80vh] overflow-y-auto rounded-2xl bg-white ring-1 ring-red-200 shadow-xl">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full grid place-items-center bg-red-50 ring-1 ring-red-200 text-red-600">!</div>
                        <div>
                            <p class="text-sm text-stone-700">Yakin hapus admin <span id="del-email" class="font-medium text-red-600"></span>? Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-3">
                        <button type="button" class="px-4 py-2 rounded-xl ring-1 ring-stone-200 bg-stone-50 hover:bg-stone-100" data-close="#modal-confirm">Batal</button>
                        <form id="del-form" method="POST" action="">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal + Live search
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('admin-search');
            const list = document.querySelector('.divide-y');
            const rows = list ? Array.from(list.querySelectorAll('.grid')) : [];
            const apply = () => {
                const q = (input?.value || '').toLowerCase();
                rows.forEach(r => {
                    const text = r.textContent?.toLowerCase() || '';
                    const match = q === '' || text.includes(q);
                    r.style.display = match ? '' : 'none';
                });
            };
            input?.addEventListener('input', apply);

            const modalAdd = document.getElementById('modal-add');
            document.getElementById('btn-open-add')?.addEventListener('click', () => modalAdd.classList.remove('hidden'));
            document.querySelectorAll('[data-close]')?.forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = document.querySelector(btn.getAttribute('data-close'));
                    if (target) target.classList.add('hidden');
                });
            });

            // Delete confirm modal
            const modalConfirm = document.getElementById('modal-confirm');
            const delEmail = document.getElementById('del-email');
            const delForm = document.getElementById('del-form');
            document.querySelectorAll('.btn-delete')?.forEach(btn => {
                btn.addEventListener('click', () => {
                    delEmail.textContent = btn.getAttribute('data-email') || '';
                    const action = btn.getAttribute('data-action') || '';
                    if (delForm) delForm.setAttribute('action', action);
                    modalConfirm.classList.remove('hidden');
                });
            });

            const centerNotif = document.getElementById('center-notif');
            if (centerNotif) setTimeout(() => centerNotif.classList.add('hidden'), 5000);
            const centerError = document.getElementById('center-error');
            if (centerError) setTimeout(() => centerError.classList.add('hidden'), 5000);
        });
    </script>
</div>
@endsection