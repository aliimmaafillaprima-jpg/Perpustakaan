@extends('layouts.app')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
<div class="grid md:grid-cols-3 gap-5 min-w-0">
    {{-- FORM TAMBAH --}}
    <div class="md:col-span-1 min-w-0">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 md:sticky md:top-24">
            <h3 class="font-bold mb-4 text-slate-800 dark:text-white">Tambah Kategori</h3>

            @if($errors->any())
                <div class="mb-3 px-3 py-2 bg-rose-100 dark:bg-rose-900/30 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 rounded text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Fiksi"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Opsional"
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description') }}</textarea>
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition shadow">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    {{-- DAFTAR --}}
    <div class="md:col-span-2 min-w-0">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">Daftar Kategori</h3>
                    <p class="text-xs text-slate-500">Total {{ $categories->total() }} kategori.</p>
                </div>
            </div>
            <div class="w-full min-w-0 overflow-x-auto overscroll-x-contain touch-pan-x">
                <table class="w-full text-sm min-w-[700px]">
                    <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3 text-left">No</th>
                            <th class="px-5 py-3 text-left">Nama</th>
                            <th class="px-5 py-3 text-left">Deskripsi</th>
                            <th class="px-5 py-3 text-left">Buku</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $i => $cat)
                        <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <td class="px-5 py-3 text-slate-500">{{ $categories->firstItem() + $i }}</td>
                            <td class="px-5 py-3 font-semibold text-slate-800 dark:text-white">{{ $cat->name }}</td>
                            <td class="px-5 py-3 text-slate-500 text-xs">{{ Str::limit($cat->description, 50) ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 rounded text-xs">
                                    {{ $cat->books_count }} buku
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-center gap-2">
                                    <button onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}')"
                                        class="px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded text-xs transition">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="confirmDelete(event, this)">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded text-xs transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                                <p>Belum ada kategori.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-700">{{ $categories->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="editModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white">Edit Kategori</h3>
        <form id="editForm" method="POST" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Nama Kategori *</label>
                <input type="text" name="name" id="editName" required
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Deskripsi</label>
                <textarea name="description" id="editDescription" rows="3"
                    class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition">
                    Update
                </button>
                <button type="button" onclick="closeEditModal()"
                    class="flex-1 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 text-slate-700 dark:text-white font-medium py-2.5 rounded-lg transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editCategory(id, name, description) {
    document.getElementById('editForm').action = '/admin/categories/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endpush