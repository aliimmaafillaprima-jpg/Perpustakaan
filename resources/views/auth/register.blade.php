@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 pt-16 md:p-10 md:pt-16 relative max-h-[90vh] overflow-y-auto">
    
    <a href="{{ route('landing') }}" class="absolute top-4 left-5 md:top-6 md:left-6 text-slate-400 hover:text-slate-800 text-sm font-medium flex items-center gap-1 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Kembali
    </a>

    <div class="text-center mb-6 mt-4">
        <h3 class="text-2xl font-bold text-slate-800">Daftar Akun Siswa</h3>
        <p class="text-slate-500 text-sm mt-1">Lengkapi data diri Anda untuk meminjam buku.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-rose-100 border-l-4 border-rose-500 text-rose-700 rounded text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap *</label>
            <input type="text" name="name" id="inputName" value="{{ old('name') }}" required
                oninput="capitalizeName(this)"
                class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition">
            <p class="text-[10px] text-slate-400 mt-1">Nama akan otomatis diawali huruf kapital.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">NIS *</label>
            <input type="text" name="nis" value="{{ old('nis') }}" required pattern="[0-9]{5,10}" title="NIS harus berupa angka 5-10 digit"
                class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition">
        </div>

        {{-- BAGIAN KELAS & JURUSAN (TETAP BERDAMPINGAN) --}}
        <div class="grid grid-cols-2 gap-3 w-full max-w-full">
            <div class="min-w-0"> {{-- min-w-0 wajib agar tidak meluber --}}
                <label class="block text-sm font-medium text-slate-700 mb-1">Kelas *</label>
                <select name="kelas" id="selectKelas" required onchange="updateJurusan()" class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition truncate">
                    <option value="">Pilih</option>
                    <option value="X" {{ old('kelas') == 'X' ? 'selected' : '' }}>X</option>
                    <option value="XI" {{ old('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                    <option value="XII" {{ old('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
                </select>
            </div>
            <div class="min-w-0"> {{-- min-w-0 wajib agar tidak meluber --}}
                <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan *</label>
                <select name="jurusan" id="selectJurusan" required class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition truncate">
                    <option value="">Pilih</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">No. Telepon *</label>
            <input type="tel" name="phone" id="inputPhone" value="{{ old('phone') }}" required 
                placeholder="08xxxxxxxxxx" oninput="validatePhone(this)"
                class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition">
            <p id="phoneError" class="text-[10px] text-rose-500 mt-1 hidden">Format tidak valid.</p>
            <p id="phoneSuccess" class="text-[10px] text-emerald-600 mt-1 hidden">Nomor telepon valid.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Password *</label>
            <input type="password" name="password" required minlength="6"
                class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password *</label>
            <input type="password" name="password_confirmation" required minlength="6"
                class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition">
        </div>

        <button type="submit"
            class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-3 rounded-lg transition shadow-lg mt-4 text-sm">
            Daftar
        </button>
    </form>

    <p class="mt-5 text-center text-sm text-slate-500">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-slate-800 font-bold hover:underline">Masuk</a>
    </p>
</div>

<script>
    const dataJurusan = {
        "X": ["DPIB A", "DPIB B", "TKP", "TK A", "TK B", "TE A", "TE B", "TE C", "TE D", "TE E", "TM A", "TM B", "TM C", "TM D", "TO A", "TO B", "TO C", "TO D", "TO E", "PPLG A", "PPLG B", "PPLG C"],
        "XI": ["BKP", "DPIB A", "DPIB B", "TITL A", "TITL B", "TEI A", "TEI B", "TEI C", "TOI A", "TOI B", "TP A", "TP B", "TP C", "TP D", "TKR A", "TKR B", "TKR C", "TSM A", "TSM B", "RPL A", "RPL B", "RPL C"],
        "XII": ["BKP", "DPIB A", "DPIB B", "TITL A", "TITL B", "TEI A", "TEI B", "TEI C", "TOI A", "TOI B", "TP A", "TP B", "TP C", "TP D", "TKR A", "TKR B", "TKR C", "TSM A", "TSM B", "RPL A", "RPL B", "RPL C"]
    };

    function updateJurusan() {
        const kelas = document.getElementById('selectKelas').value;
        const jurusanSelect = document.getElementById('selectJurusan');
        const oldJurusan = "{{ old('jurusan') }}";
        jurusanSelect.innerHTML = '<option value="">Pilih</option>';
        if (kelas && dataJurusan[kelas]) {
            dataJurusan[kelas].forEach(function(jurusan) {
                const option = document.createElement('option');
                option.value = jurusan;
                option.textContent = jurusan;
                if (jurusan === oldJurusan) option.selected = true;
                jurusanSelect.appendChild(option);
            });
        }
    }
    document.addEventListener('DOMContentLoaded', updateJurusan);

    function capitalizeName(input) {
        let words = input.value.split(" ");
        for (let i = 0; i < words.length; i++) {
            if (words[i].length > 0) {
                words[i] = words[i][0].toUpperCase() + words[i].substring(1).toLowerCase();
            }
        }
        input.value = words.join(" ");
    }

    function validatePhone(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
        const phoneError = document.getElementById('phoneError');
        const phoneSuccess = document.getElementById('phoneSuccess');
        const val = input.value;
        if (val.length === 0) {
            phoneError.classList.add('hidden');
            phoneSuccess.classList.add('hidden');
        } else if (val.startsWith('08') && val.length >= 10 && val.length <= 13) {
            phoneError.classList.add('hidden');
            phoneSuccess.classList.remove('hidden');
        } else {
            phoneError.classList.remove('hidden');
            phoneSuccess.classList.add('hidden');
        }
    }
</script>
@endsection