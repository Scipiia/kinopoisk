<?php

namespace App\Services;

use App\Kernel\Auth\User;
use App\Kernel\Database\DatabaseInterface;
use App\Kernel\Upload\UploadedFileInterface;
use App\Models\Movie;
use App\Models\Review;

class MovieService
{
    public function __construct(
        private DatabaseInterface $database,
    )
    {
    }

    public function store(string $name, string $descriptions, UploadedFileInterface $image, int $category): false|int
    {
        $filePath = $image->move('movies');

         return $this->database->insert('movies', [
            'name'=>$name,
            'description'=>$descriptions,
            'preview'=>$filePath,
            'category_id'=>$category,
        ]);

    }

    public function all(): array
    {
        $movie = $this->database->get('movies');

        return array_map(function ($movie){
           return new Movie(
               $movie['id'],
               $movie['category_id'],
               $movie['name'],
               $movie['description'],
               $movie['preview'],
           );
        }, $movie);
    }

    public function destroy(int $id): void
    {
        $this->database->delete('movies',[
            'id'=>$id,
        ]);
    }

    public function find(int $id): ?Movie
    {
        $movie = $this->database->first('movies', [
            'id'=>$id,
        ]);

        if (!$movie) {
            return null;
        }

        $reviews = $this->getReviews($id);

        return new Movie(
            $movie['id'],
            $movie['category_id'],
            $movie['name'],
            $movie['description'],
            $movie['preview'],
            $reviews,
        );
    }

    public function update(int $id, int $category, string $name, string $description, ?UploadedFileInterface $image)
    {

        $data = [
            'name'=>$name,
            'category_id'=>$category,
            'description'=>$description,
        ];

        if ($image && ! $image->hasErrors())  {
            $filePath = $image->move("movies");
            $data['preview'] = $filePath;
        }

        $this->database->update('movies', $data, [
            'id' => $id,
        ]);
    }

    public function newMovies()
    {
        $movies = $this->database->get('movies', [], ['id'=>'DESC'],10);


       return array_map(function ($movie) {
           return new Movie(
               $movie['id'],
               $movie['category_id'],
               $movie['name'],
               $movie['description'],
               $movie['preview'],
               $this->getReviews($movie['id'])
           );
       }, $movies);
    }

    private function getReviews(int $id): array
    {
        $review = $this->database->get('reviews', [
            'movie_id' => $id,
        ]);

         return array_map(function ($review) {
             $user = $this->database->first('users', [
                'id'=>$review['user_id'],
             ]);

            return new Review(
                $review['id'],
                $review['rating'],
                $review['review'],
                $review['created_at'],
                new User(
                    $user['id'],
                    $user['name'],
                    $user['email'],
                    $user['password'],
                ),
            );
        }, $review);
    }
}