<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LENDORA') — Perpustakaan Digital</title>
    
    <link rel="icon" href="{{ asset('images/logo_smk.jpg') }}" type="image/jpeg">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .sidebar-item { transition: all 0.2s; }
        .sidebar-item:hover { background: rgba(255,255,255,0.1); }
        .sidebar-item.active { background: rgba(255,255,255,0.15); border-left: 4px solid #F97316; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(-10%); }
            50% { transform: translateY(0); }
        }
        .animate-bounce-slow { animation: bounce-slow 2s infinite; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors overflow-x-hidden">

{{-- SPLASH SCREEN --}}
<div id="splash-screen" class="fixed inset-0 z-[9999] bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex flex-col items-center justify-center transition-opacity duration-700 ease-in-out">
    <div class="bg-white p-4 rounded-full mb-4 animate-bounce-slow shadow-2xl">
        <img src="{{ asset('images/logo_smk.jpg') }}" alt="Logo SMK" class="w-20 h-20 object-contain">
    </div>
    <h1 class="text-4xl font-extrabold text-white tracking-widest mb-2 drop-shadow-lg">LENDORA</h1>
    <p class="text-indigo-300 font-semibold mb-10 text-sm tracking-widest uppercase">Perpustakaan SMK Negeri 5 Surakarta</p>
    <p class="absolute bottom-10 text-slate-500 text-xs font-medium tracking-wide">
        © 2026 LENDORA — Perpustakaan Digital
    </p>
</div>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

<div class="flex min-h-screen max-w-full">
    {{-- SIDEBAR --}}
    @auth
    <aside id="sidebar" class="w-64 bg-indigo-700 dark:bg-slate-800 text-white flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 -translate-x-full md:translate-x-0">
        <div class="p-5 border-b border-indigo-600 dark:border-slate-700">
            <a href="/" class="flex items-center gap-3">
                <div class="bg-white p-1 rounded-lg shrink-0">
                    <img src="{{ asset('images/logo_smk.jpg') }}" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-xl font-bold leading-tight">LENDORA</h1>
                    <p class="text-[10px] text-indigo-200 uppercase tracking-widest font-semibold">SMK N 5 Surakarta</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 py-4 overflow-y-auto">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="{{ route('admin.books.index') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                    <i data-lucide="book" class="w-5 h-5"></i> Kelola Buku
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i data-lucide="tags" class="w-5 h-5"></i> Kategori
                </a>
                <a href="{{ route('admin.students.index') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <i data-lucide="users" class="w-5 h-5"></i> Data Siswa
                </a>
                <a href="{{ route('admin.loans.index') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('admin.loans.index') ? 'active' : '' }}">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i> Peminjaman
                </a>
                <a href="{{ route('admin.loans.history') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('admin.loans.history') ? 'active' : '' }}">
                    <i data-lucide="printer" class="w-5 h-5"></i> Cetak Laporan
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i data-lucide="home" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="{{ route('public.catalog') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('public.catalog*') ? 'active' : '' }}">
                    <i data-lucide="library" class="w-5 h-5"></i> Katalog Buku
                </a>
                <a href="{{ route('user.loans.index') }}" class="sidebar-item flex items-center gap-3 px-5 py-3 {{ request()->routeIs('user.loans.*') ? 'active' : '' }}">
                    <i data-lucide="bookmark" class="w-5 h-5"></i> Peminjaman Saya
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-indigo-600 dark:border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 bg-orange-500 hover:bg-orange-600 rounded-lg font-medium transition text-white">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                </button>
            </form>
        </div>
    </aside>
    @endauth

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col w-full max-w-full transition-all duration-300 {{ auth()->check() ? 'md:ml-64' : '' }}">
        
        {{-- NAVBAR --}}
        <header class="bg-white dark:bg-slate-800 shadow-sm sticky top-0 z-30 border-b border-slate-200 dark:border-slate-700 w-full">
            <div class="flex justify-between items-center px-2 md:px-6 py-3 w-full">
                
                {{-- BAGIAN KIRI --}}
                <div class="flex items-center gap-1 md:gap-3 flex-1 min-w-0 pr-1">
                    @auth
                        <button onclick="toggleSidebar()" class="md:hidden shrink-0 w-8 h-8 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center justify-center">
                            <i data-lucide="menu" class="w-5 h-5 text-slate-800 dark:text-white"></i>
                        </button>
                    @endauth

                    @if(!request()->is('/'))
                        <button onclick="history.back()" aria-label="Kembali" class="shrink-0 mr-1 w-9 h-9 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        </button>
                    @endif

                    @guest
                        <a href="{{ route('landing') }}" class="flex items-center gap-1 md:gap-2 hover:opacity-80 transition min-w-0">
                            <img src="{{ asset('images/logo_smk.jpg') }}" alt="Logo SMK" class="w-7 h-7 md:w-8 md:h-8 object-contain shrink-0">
                            <span class="text-base md:text-xl font-bold text-indigo-600 dark:text-indigo-400 truncate">LENDORA</span>
                        </a>
                    @else
                        <h2 class="text-sm md:text-xl font-semibold text-slate-800 dark:text-white truncate">@yield('page-title', 'Dashboard')</h2>
                    @endguest
                </div>

                {{-- BAGIAN KANAN --}}
                <div class="flex items-center gap-1 md:gap-2 shrink-0">
                    {{-- TOMBOL DARK MODE --}}
                    <button onclick="toggleDarkMode()" class="shrink-0 w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 flex items-center justify-center transition">
                        <i data-lucide="sun" id="iconLight" class="w-4 h-4 md:w-5 md:h-5 text-slate-600 hidden"></i>
                        <i data-lucide="moon" id="iconDark" class="w-4 h-4 md:w-5 md:h-5 text-slate-600"></i>
                    </button>

                    @auth
                        {{-- NOTIFIKASI --}}
                        <div class="relative shrink-0">
                            <button onclick="toggleDropdown('notifDropdown')" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 flex items-center justify-center relative transition">
                                <i data-lucide="bell" class="w-4 h-4 md:w-5 md:h-5 text-indigo-600 dark:text-indigo-400"></i>
                                <span id="notifBadge" class="hidden absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white dark:border-slate-800">0</span>
                            </button>
                            
                            <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-64 md:w-80 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 z-50 overflow-hidden">
                                <div class="p-3 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                                    <h4 class="font-bold text-sm dark:text-white">Notifikasi Terbaru</h4>
                                </div>
                                <div id="notifList" class="max-h-80 overflow-y-auto">
                                    <div class="p-4 text-center text-slate-400 text-sm">Memuat...</div>
                                </div>
                            </div>
                        </div>

                        {{-- PROFIL --}}
                        <div class="relative shrink-0">
                            <button onclick="toggleDropdown('profileDropdown')" class="flex items-center gap-1 md:gap-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full p-0.5 md:p-1 md:pr-3 transition">
                                <img src="{{ auth()->user()->avatar_url }}" class="w-7 h-7 md:w-9 md:h-9 rounded-full object-cover shrink-0" alt="Avatar">
                                <div class="text-left hidden lg:block truncate max-w-[100px]">
                                    <p class="font-semibold text-sm text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 capitalize truncate">
                                        @if(auth()->user()->role === 'siswa')
                                            {{ auth()->user()->kelas }} {{ auth()->user()->jurusan ? '- ' . auth()->user()->jurusan : '' }}
                                        @else
                                            {{ auth()->user()->role }}
                                        @endif
                                    </p>
                                </div>
                            </button>
                            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 z-50 overflow-hidden">
                                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                                    <p class="font-semibold text-sm dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm dark:text-white border-b border-slate-100 dark:border-slate-700">
                                    Profil Saya
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-rose-50 dark:hover:bg-rose-900/30 text-sm text-rose-600 transition">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- TOMBOL UNTUK TAMU --}}
                        <div class="flex gap-1 md:gap-2">
                            <a href="{{ route('login') }}" class="px-2 py-1.5 md:px-4 md:py-2 text-[11px] md:text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-2 py-1.5 md:px-4 md:py-2 text-[11px] md:text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        {{-- MAIN AREA (flex-1 agar footer otomatis di bawah) --}}
        <main class="p-3 md:p-6 w-full max-w-full overflow-x-hidden flex-1">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-100 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 rounded flex items-center gap-2 text-sm">
                    <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-rose-100 dark:bg-rose-900/30 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 rounded flex items-center gap-2 text-sm">
                    <i data-lucide="x-circle" class="w-5 h-5"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        {{-- FOOTER HALAMAN DALAM (Jam Operasional & Email) --}}
        <footer class="mt-auto py-5 px-6 border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-500 dark:text-slate-400 flex flex-col md:flex-row justify-between items-center gap-3">
            <div class="text-center md:text-left">
                &copy; {{ date('Y') }} <strong>LENDORA</strong> - SMK Negeri 5 Surakarta.
            </div>
            <div class="flex flex-col md:flex-row items-center gap-2 md:gap-5 text-center">
                <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5 text-indigo-500"></i> Senin-Kamis: 07.00-15.30 | Jumat: 07.00-14.00</span>
                <span class="flex items-center gap-1.5"><i data-lucide="mail" class="w-3.5 h-3.5 text-indigo-500"></i> perpustakaansmklima@gmail.com</span>
            </div>
        </footer>
    </div>
</div>

{{-- MODAL HAPUS GLOBAL --}}
<div id="deleteConfirmModal" class="hidden fixed inset-0 bg-black/60 z-[9999] flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center transform scale-100 transition-transform">
        <div class="w-20 h-20 bg-rose-100 dark:bg-rose-900/30 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="trash-2" class="w-10 h-10"></i>
        </div>
        <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Konfirmasi Hapus</h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm mb-8 leading-relaxed">
            Apakah Anda yakin ingin menghapus data ini? <br>
            <span class="text-indigo-600 dark:text-indigo-400 font-medium">Anda masih bisa menambahkannya kembali nanti.</span>
        </p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 px-5 py-3 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-white font-semibold rounded-xl transition">Batal</button>
            <button type="button" onclick="submitDelete()" class="flex-1 px-5 py-3 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 transition transform hover:-translate-y-0.5">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const splash = document.getElementById('splash-screen');
    if (splash) {
        if (sessionStorage.getItem('lendora_splash_shown')) {
            splash.remove();
        } else {
            setTimeout(() => {
                splash.classList.add('opacity-0');
                setTimeout(() => {
                    splash.remove();
                    sessionStorage.setItem('lendora_splash_shown', 'true');
                }, 700);
            }, 3500);
        }
    }
});

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function initDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.documentElement.classList.add('dark');
        document.getElementById('iconLight').classList.remove('hidden');
        document.getElementById('iconDark').classList.add('hidden');
    }
}

function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    const isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('darkMode', isDark);
    
    if (isDark) {
        document.getElementById('iconLight').classList.remove('hidden');
        document.getElementById('iconDark').classList.add('hidden');
    } else {
        document.getElementById('iconLight').classList.add('hidden');
        document.getElementById('iconDark').classList.remove('hidden');
    }
}

function toggleDropdown(id) {
    const dd = document.getElementById(id);
    const isHidden = dd.classList.contains('hidden');
    document.querySelectorAll('[id$="Dropdown"]').forEach(el => el.classList.add('hidden'));
    if (isHidden) dd.classList.remove('hidden');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('button[onclick^="toggleDropdown"]') && !e.target.closest('[id$="Dropdown"]')) {
        document.querySelectorAll('[id$="Dropdown"]').forEach(el => el.classList.add('hidden'));
    }
});

let formToSubmit = null;
function confirmDelete(event, form) {
    event.preventDefault(); 
    formToSubmit = form;    
    document.getElementById('deleteConfirmModal').classList.remove('hidden'); 
}
function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').classList.add('hidden'); 
    formToSubmit = null;
}
function submitDelete() {
    if (formToSubmit) formToSubmit.submit(); 
}

function loadNotifications() {
    fetch('{{ route("notifications.fetch") }}')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notifBadge');
            const list = document.getElementById('notifList');

            if (data.count > 0) {
                badge.textContent = data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }

            if (data.items.length === 0) {
                list.innerHTML = '<div class="p-6 text-center text-slate-400 text-sm">Belum ada notifikasi baru.</div>';
                return;
            }

            let html = '';
            data.items.forEach(item => {
                let colorClass = 'bg-blue-100 text-blue-600';
                let iconName = 'info';
                
                if (item.type === 'success') { colorClass = 'bg-emerald-100 text-emerald-600'; iconName = 'check-circle'; }
                if (item.type === 'warning') { colorClass = 'bg-amber-100 text-amber-600'; iconName = 'alert-triangle'; }
                if (item.type === 'error') { colorClass = 'bg-rose-100 text-rose-600'; iconName = 'x-circle'; }

                let timeAgo = new Date(item.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                html += `
                    <a href="{{ url('/notifications/read') }}/${item.id}" class="block p-3 border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 ${colorClass}">
                                <i data-lucide="${iconName}" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-sm dark:text-white leading-tight truncate">${item.title}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">${item.message}</p>
                                <p class="text-[10px] text-slate-400 mt-1">${timeAgo}</p>
                            </div>
                        </div>
                    </a>
                `;
            });
            list.innerHTML = html;
            lucide.createIcons(); 
        })
        .catch(error => console.error('Error fetching notifications:', error));
}

initDarkMode();
lucide.createIcons();

@auth
    loadNotifications();
    setInterval(loadNotifications, 30000); 
@endauth
</script>
@stack('scripts')
</body>
</html>