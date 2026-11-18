<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LENDORA — Perpustakaan Digital SMK Negeri 5 Surakarta</title>
    <link rel="icon" href="{{ asset('images/logo_smk.jpg') }}" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-library {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=2000&auto=format&fit=crop');
            background-size: cover; background-position: center;
            filter: brightness(0.65) blur(1px); 
            animation: panBackground 15s linear infinite alternate; 
        }
        @keyframes panBackground {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.15) translate(-3%, -3%); }
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-800 relative min-h-screen overflow-x-hidden">

<div class="bg-library"></div>

<nav class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-3 flex justify-between items-center gap-3">
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('images/logo_smk.jpg') }}" alt="Logo SMK" class="w-10 h-10 object-contain">
            <div>
                <h1 class="text-xl font-bold text-slate-800 leading-tight">LENDORA</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-semibold">SMK Negeri 5 Surakarta</p>
            </div>
        </a>
        <div class="flex shrink-0 items-center gap-2">
            <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Masuk</a>
            <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">Daftar</a>
        </div> 
    </div>
</nav>

<section class="py-20 md:py-32 relative z-10">
    <div class="max-w-7xl mx-auto px-4 md:px-6 text-center">
        <div class="inline-block px-4 py-1.5 rounded-full bg-slate-900/60 border border-slate-500/30 backdrop-blur-md text-slate-200 text-xs font-semibold mb-6">
            Sistem Informasi Perpustakaan Terpadu
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight drop-shadow-lg">
            Selamat Datang di <br><span class="text-orange-400">LENDORA</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-200 mb-10 max-w-2xl mx-auto drop-shadow">
            Sistem peminjaman buku modern untuk seluruh siswa-siswi SMK Negeri 5 Surakarta. Temukan, pinjam, dan baca buku favoritmu dengan mudah.
        </p>
        <div class="flex flex-col md:flex-row items-center justify-center gap-4">
            <a href="{{ route('public.catalog') }}" class="w-full md:w-auto bg-orange-500 hover:bg-orange-600 text-white font-bold px-8 py-4 rounded-xl shadow-lg transition transform hover:-translate-y-1 text-lg flex items-center justify-center gap-2">
                Jelajahi Katalog Buku
            </a>
            <a href="{{ route('register') }}" class="w-full md:w-auto bg-white/10 hover:bg-white/20 text-white font-bold px-8 py-4 rounded-xl border border-white/40 backdrop-blur transition text-lg flex items-center justify-center">
                Daftar sebagai Siswa
            </a>
        </div>
    </div>
</section>

<section class="py-16 bg-white/95 backdrop-blur-sm relative z-10">
    <div class="max-w-7xl mx-auto px-4 md:px-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="w-16 h-16 mx-auto bg-indigo-100 text-indigo-700 rounded-2xl flex items-center justify-center mb-5">
                <i data-lucide="book" class="w-8 h-8"></i>
            </div>
            <h3 class="font-bold text-xl mb-3 text-slate-800">Koleksi Lengkap</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Ribuan buku dari berbagai kategori siap dipinjam untuk menunjang kegiatan belajarmu.</p>
        </div>
        <div class="text-center p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="w-16 h-16 mx-auto bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-5">
                <i data-lucide="zap" class="w-8 h-8"></i>
            </div>
            <h3 class="font-bold text-xl mb-3 text-slate-800">Peminjaman Online</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Ajukan pinjaman dari mana saja tanpa perlu antre panjang di perpustakaan.</p>
        </div>
        <div class="text-center p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="w-16 h-16 mx-auto bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-5">
                <i data-lucide="check-circle" class="w-8 h-8"></i>
            </div>
            <h3 class="font-bold text-xl mb-3 text-slate-800">Akses Dimana Saja</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Bisa diakses lewat HP, tablet, maupun laptop dengan tampilan yang responsif.</p>
        </div>
    </div>
</section>

{{-- TOP BUKU FAVORIT (DENGAN EFEK HOVER ZOOM) --}}
@if($topBooks->count() > 0)
<section class="py-16 bg-slate-100/90 backdrop-blur-sm relative z-10">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold mb-2 text-slate-800">Buku Terpopuler</h2>
            <p class="text-slate-500 text-sm">Buku yang paling sering dipinjam oleh siswa SMK N 5 Surakarta.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($topBooks as $book)
            <a href="{{ route('public.catalog.show', $book) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                
                {{-- GAMBAR DENGAN EFEK ZOOM --}}
                <div class="aspect-[3/4] overflow-hidden bg-slate-100">
                    <img src="{{ $book->cover_url }}" 
                         class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-110" 
                         alt="">
                </div>
                
                <div class="p-4 text-center">
                    <h4 class="font-bold text-sm text-slate-800 truncate group-hover:text-indigo-600 transition-colors">{{ $book->title }}</h4>
                    <p class="text-xs text-slate-500 truncate">{{ $book->author }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<footer class="bg-slate-900/95 backdrop-blur-md text-slate-400 py-12 relative z-10 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 md:px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ asset('images/logo_smk.jpg') }}" alt="Logo SMK" class="w-10 h-10 object-contain grayscale opacity-80">
                <div>
                    <h3 class="text-xl font-bold text-white">LENDORA</h3>
                    <p class="text-xs text-slate-500 uppercase tracking-widest">Perpustakaan Digital</p>
                </div>
            </div>
            <p class="text-sm leading-relaxed mb-4">Sistem Informasi Peminjaman Buku Perpustakaan SMK Negeri 5 Surakarta. Membaca hari ini, memimpin hari esok.</p>
            <div class="flex items-center gap-2 text-sm">
                <i data-lucide="instagram" class="w-4 h-4 text-pink-500"></i>
                <span>Collaboration w/ <span class="text-slate-200 font-semibold">@book_kamaska</span></span>
            </div>
        </div>
        <div>
            <h4 class="text-white font-bold mb-4 flex items-center gap-2"><i data-lucide="clock" class="w-5 h-5 text-indigo-400"></i> Jam Operasional</h4>
            <ul class="space-y-2 text-sm">
                <li class="flex justify-between border-b border-slate-800 pb-2"><span>Senin - Kamis</span><span class="text-slate-200">07.00 - 15.30 WIB</span></li>
                <li class="flex justify-between border-b border-slate-800 pb-2"><span>Jumat</span><span class="text-slate-200">07.00 - 14.00 WIB</span></li>
                <li class="flex justify-between text-rose-400 pt-1"><span>Sabtu - Minggu</span><span>Tutup</span></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-4 flex items-center gap-2"><i data-lucide="info" class="w-5 h-5 text-orange-400"></i> Kontak & Informasi</h4>
            <ul class="space-y-3 text-sm">
                <li class="flex items-start gap-3"><i data-lucide="mail" class="w-4 h-4 mt-0.5 text-slate-500 shrink-0"></i><span>perpustakaansmklima@gmail.com</span></li>
                <li class="flex items-start gap-3"><i data-lucide="building-2" class="w-4 h-4 mt-0.5 text-slate-500 shrink-0"></i><span>NPP: <span class="text-slate-200">3372011H1000001</span></span></li>
                <li class="flex items-start gap-3"><i data-lucide="map-pin" class="w-4 h-4 mt-0.5 text-slate-500 shrink-0"></i><span>SMK Negeri 5 Surakarta</span></li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 md:px-6 mt-10 pt-6 border-t border-slate-800 text-center text-xs text-slate-600">
        &copy; {{ date('Y') }} LENDORA — Perpustakaan Digital SMK Negeri 5 Surakarta. All Rights Reserved.
    </div>
</footer>

<script>
    lucide.createIcons();
</script>
</body>
</html>