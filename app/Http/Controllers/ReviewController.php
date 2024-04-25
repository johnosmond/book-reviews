<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Book $book)
    {
        return view('books.reviews.create', compact('book'));
    }

    public function store(Request $request, Book $book)
    {
        $data = $request->validate([
            'review' => 'required|string|min:10|max:500',
            'rating' => 'required|integer|between:1,5',
        ]);

        $book->reviews()->create($data);

        return redirect()->route('books.show', $book);
    }
}
