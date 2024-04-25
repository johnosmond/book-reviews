@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Review Book</h1>
        <form action="{{ route('books.reviews.store', $book) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="comment">Review</label>
                <textarea name="review" id="review" class="input form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <select name="rating" id="rating" class="input mb-4" required>
                    <option value="">Select a rating</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }} ">{{ $i }} {{ Str::plural('Star', $i) }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn">Add Review</button>
        </form>
    </div>
@endsection
