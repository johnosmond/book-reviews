<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'latest');

        $books = Book::when(
            $search,
            fn ($query, $search) => $query->search($search)
        );

        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAvgRating()->withReviewsCount(),
        };

        $books = $books->paginate(10);

        $page = request()->get('page', 1);
        $cacheKey = "books.page.{$page}" . $filter . '.' . $search;

        // $books = cache()->remember($cacheKey, 3600, fn () => $books->paginate(10));

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(int $book_id)
    // {
    //     // we have to use $book_id instead route model binding
    //     // because we want to cache the query

    //     // test the chache performance
    //     $begin = hrtime(true);

    //     $cacheKey = 'book:' . $book_id;

    //     $book = cache()->remember(
    //         $cacheKey,
    //         3600,
    //         fn () => Book::with([
    //             'reviews' => fn ($query) => $query->latest()
    //         ])->withAvgRating()->withReviewsCount()->findOrFail($book_id)
    //     );

    //     // test the chache performance
    //     $end = hrtime(true);

    //     // $book->load(['reviews' => fn ($query) => $query->latest()]);
    //     return view('books.show', ['book' => $book, 'begin' => $begin, 'end' => $end]);
    // }
    public function show(Book $book)
    {
        $book = Book::where('id', $book->id)
            ->with(['reviews' => fn ($query) => $query->latest()])
            ->withAvgRating()->withReviewsCount()->firstOrFail();
        return view('books.show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
