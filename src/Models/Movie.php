<?php

namespace App\Models;

class Movie
{
    public function __construct(
        private int $id,
        private int $categoryId,
        private string $name,
        private string $description,
        private string $preview,
        private array $reviews = [],
    )
    {
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function categoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function preview(): string
    {
        return $this->preview;
    }

    /**
     * @return array<Review>
     */
    public function reviews(): array
    {
        return $this->reviews;
    }

    public function avgRating(): float
    {
        $ratings = array_map(function (Review $review) {
            return $review->rating();
        }, $this->reviews);

        if (count($ratings) == 0) {
            return  0;
        }

        return round(array_sum($ratings) / count($ratings), 1);
    }
}