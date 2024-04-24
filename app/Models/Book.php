<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('title', 'LIKE', '%' . $search . '%');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withAvg([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating')->orderBy('reviews_avg_rating', 'desc');
    }

    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif ($to && !$from) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopeMinReviewsCount(Builder $query, int $count): Builder
    {
        return $query->withCount('reviews')
            ->where('reviews_count', '>=', $count);
    }

    public function scopeMinRating(Builder $query, float $rating): Builder
    {
        return $query->withAvg('reviews', 'rating')
            ->where('reviews_rating_avg', '>=', $rating);
    }
}
