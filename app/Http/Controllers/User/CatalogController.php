<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dasar
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());
            $query->where(function ($books) use ($search) {
                $books->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('publisher', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('available') && $request->available == '1') {
            $query->where('stock', '>', 0);
        }

        $books = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('user.catalog', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $hasActiveLoan = false;
        
        if (auth()->check()) {
            $hasActiveLoan = Loan::where('user_id', auth()->id())
                ->where('book_id', $book->id)
                ->whereIn('status', ['pending', 'approved'])
                ->exists();
        }

        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('user.book-detail', compact('book', 'hasActiveLoan', 'relatedBooks'));
    }
}