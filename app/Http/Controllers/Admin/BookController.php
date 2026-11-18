<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Logika pencarian teks sudah dihapus
        // Hanya menyisakan filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'publisher'   => 'nullable|string|max:255',
            'year'        => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn'        => 'nullable|string|max:20',
            'stock'       => 'required|integer|min:0|max:9999',
            'description' => 'nullable|string|max:2000',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'title.required'       => 'Judul buku wajib diisi.',
            'author.required'      => 'Penulis wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'stock.required'       => 'Stok wajib diisi.',
            'cover.image'          => 'File harus berupa gambar.',
            'cover.max'            => 'Ukuran cover maksimal 2MB.',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $this->uploadCover($request->file('cover'));
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'publisher'   => 'nullable|string|max:255',
            'year'        => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn'        => 'nullable|string|max:20',
            'stock'       => 'required|integer|min:0|max:9999',
            'description' => 'nullable|string|max:2000',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            // Hapus cover lama
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $this->uploadCover($request->file('cover'));
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui! ✅');
    }

    public function destroy(Book $book)
    {
        // Cek apakah buku sedang dipinjam
        $activeLoans = $book->loans()
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($activeLoans > 0) {
            return back()->with('error', "Buku tidak bisa dihapus! Masih ada {$activeLoans} peminjaman aktif.");
        }

        // Hapus cover
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus! 🗑️');
    }

    /**
     * Upload cover buku ke storage/app/public/covers
     */
    private function uploadCover($file): string
    {
        $filename = 'covers/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('covers', basename($filename), 'public');
        return $filename;
    }
}