@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 md:p-6">
        <div class="mb-5 flex items-center gap-3">
            <img src="{{ $student->avatar_url }}" class="w-14 h-14 rounded-full object-cover shadow-sm" alt="">
            <div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Edit: {{ $student->name }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Perbarui data siswa.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-rose-100 dark:bg-rose-900/30 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.students.update', $student) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf @method('PUT')
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">NIS *</label>
                <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" required pattern="[0-9]{5,10}" title="NIS harus berupa angka 5-10 digit" class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            
            {{-- KELAS & JURUSAN (TETAP SEJAJAR) --}}
            <div class="md:col-span-2 grid grid-cols-2 gap-3 w-full max-w-full">
                <div class="min-w-0 w-full">
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Kelas *</label>
                    <select name="kelas" id="selectKelas" required onchange="updateJurusan()" class="w-full max-w-full px-3 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none truncate">
                        <option value="">Pilih</option>
                        <option value="X" {{ old('kelas', $student->kelas) == 'X' ? 'selected' : '' }}>X (Sepuluh)</option>
                        <option value="XI" {{ old('kelas', $student->kelas) == 'XI' ? 'selected' : '' }}>XI (Sebelas)</option>
                        <option value="XII" {{ old('kelas', $student->kelas) == 'XII' ? 'selected' : '' }}>XII (Dua Belas)</option>
                    </select>
                </div>
                <div class="min-w-0 w-full">
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Jurusan *</label>
                    <select name="jurusan" id="selectJurusan" required class="w-full max-w-full px-3 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none truncate">
                        <option value="">Pilih Kelas Dulu</option>
                    </select>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Email *</label>
                <input type="email" name="email" value="{{ old('email', $student->email) }}" required class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">No. Telepon *</label>
                <input type="tel" name="phone" value="{{ old('phone', $student->phone) }}" required pattern="[0-9]{10,15}" class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="md:col-span-2">
                <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg p-3">
                    <p class="text-xs text-amber-700 dark:text-amber-400 mb-2">Kosongkan password jika tidak ingin mengganti.</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Password Baru</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="md:col-span-2 flex flex-col md:flex-row gap-3 pt-2">
                <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg transition shadow text-sm">Update</button>
                <a href="{{ route('admin.students.index') }}" class="w-full md:w-auto text-center bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 text-slate-700 dark:text-white font-medium px-6 py-2.5 rounded-lg transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const dataJurusan = {
        "X": ["DPIB A", "DPIB B", "TKP", "TK A", "TK B", "TE A", "TE B", "TE C", "TE D", "TE E", "TM A", "TM B", "TM C", "TM D", "TO A", "TO B", "TO C", "TO D", "TO E", "PPLG A", "PPLG B", "PPLG C"],
        "XI": ["BKP", "DPIB A", "DPIB B", "TITL A", "TITL B", "TEI A", "TEI B", "TEI C", "TOI A", "TOI B", "TP A", "TP B", "TP C", "TP D", "TKR A", "TKR B", "TKR C", "TSM A", "TSM B", "RPL A", "RPL B", "RPL C"],
        "XII": ["BKP", "DPIB A", "DPIB B", "TITL A", "TITL B", "TEI A", "TEI B", "TEI C", "TOI A", "TOI B", "TP A", "TP B", "TP C", "TP D", "TKR A", "TKR B", "TKR C", "TSM A", "TSM B", "RPL A", "RPL B", "RPL C"]
    };

    function updateJurusan() {
        const kelas = document.getElementById('selectKelas').value;
        const jurusanSelect = document.getElementById('selectJurusan');
        const currentJurusan = "{{ old('jurusan', $student->jurusan) }}";

        jurusanSelect.innerHTML = '<option value="">Pilih Jurusan</option>';

        if (kelas && dataJurusan[kelas]) {
            dataJurusan[kelas].forEach(jurusan => {
                const option = document.createElement('option');
                option.value = jurusan;
                option.textContent = jurusan;
                if (jurusan === currentJurusan) option.selected = true;
                jurusanSelect.appendChild(option);
            });
        } else {
            jurusanSelect.innerHTML = '<option value="">Pilih Kelas Dulu</option>';
        }
    }
    document.addEventListener('DOMContentLoaded', updateJurusan);
</script>
@endpush