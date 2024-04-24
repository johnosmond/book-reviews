<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    // eloquent relationship method
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    // scope methods

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('title', 'LIKE', '%' . $search . '%');
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ]);
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {
        // using the scope method above
        return $query->withReviewsCount($from, $to)->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder
    {
        // using the scope method above
        return $query->withAvgRating($from, $to)->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviewsCount(Builder $query, int $count): Builder
    {
        // aggregate function on scope method above
        return $query->where('reviews_count', '>=', $count);
    }

    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        // filter by date range on the reviews
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif ($to && !$from) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    // public function scopeMinRating(Builder $query, float $rating): Builder
    // {
    //     return $query->withAvg('reviews', 'rating')
    //         ->where('reviews_rating_avg', '>=', $rating);
    // }

    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviewsCount(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviewsCount(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviewsCount(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviewsCount(5);
    }
}
