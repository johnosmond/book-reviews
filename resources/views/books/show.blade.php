@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="sticky top-0 mb-2 text-2xl">{{ $book->title }}</h1>

        <div class="book-info">
            <div class="book-author mb-4 text-lg font-semibold">by {{ $book->author }}</div>
            <div class="book-rating flex items-center">
                <div class="mr-2 text-sm font-medium text-slate-700">
                    <x-star-rating :rating="$book->reviews_avg_rating" />
                </div>
                <span class="book-review-count text-sm text-gray-500">
                    {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                </span>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('books.reviews.create', $book) }}" class="reset-link">Review Book</a>
    </div>

    <div>
        <h2 class="mb-4 text-xl font-semibold">Reviews</h2>
        @forelse ($book->reviews as $review)
            <ul>
                <li class="book-item mb-4">
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <div class="font-semibold">
                                <x-star-rating :rating="$review->rating" />
                            </div>
                            <div class="book-review-count">
                                {{ $review->created_at->format('M j, Y') }}</div>
                        </div>
                        <p class="text-gray-700">{{ $review->review }}</p>
                    </div>
                </li>
            </ul>
        @empty
            <div class="empty-book-item">
                <p class="empty-text text-lg font-semibold">No reviews yet</p>
            </div>
        @endforelse
    </div>
    @isset($begin, $end)
        {{ $begin }}<br>
        {{ $end }}<br>
        {{ $end - $begin }}
    @endisset
@endsection
