@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
        <div class="mb-5">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white"> Form Buku Baru</h3>
            <p class="text-sm text-slate-500">Isi data buku dengan lengkap.</p>
        </div>

        @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-rose-100 dark:bg-rose-900/30 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-4">
            @csrf

            {{-- COVER --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-white mb-2">Cover Buku</label>
                <div class="flex gap-4 items-start">
                    <div class="w-32 h-44 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-300 dark:border-slate-600">
                        <img id="coverPreview" src="" class="hidden w-full h-full object-cover">
                        <span id="coverPlaceholder" class="text-4xl">📕</span>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="cover" accept="image/*" id="coverInput" onchange="previewCover(event)"
                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer text-sm">
                        <p class="text-xs text-slate-400 mt-2">JPG, PNG, WEBP. Maks 2MB. Rasio 3:4 disarankan.</p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Judul Buku *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Penulis *</label>
                <input type="text" name="author" value="{{ old('author') }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Kategori *</label>
                <select name="category_id" required
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Penerbit</label>
                <input type="text" name="publisher" value="{{ old('publisher') }}"
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Tahun Terbit</label>
                <input type="number" name="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') }}"
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn') }}"
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Stok *</label>
                <input type="number" name="stock" value="{{ old('stock', 1) }}" min="0" required
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Deskripsi / Sinopsis</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description') }}</textarea>
            </div>

            <div class="md:col-span-2 flex gap-3 pt-2">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg transition shadow">
                    Simpan Buku
                </button>
                <a href="{{ route('admin.books.index') }}"
                   class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-white font-medium px-6 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewCover(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('coverPreview').src = ev.target.result;
        document.getElementById('coverPreview').classList.remove('hidden');
        document.getElementById('coverPlaceholder').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endpush