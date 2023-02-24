<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $books = [
            [
                'data' => [
                    'name' => 'The Housetrap',
                    'price' => 300,
                    'description' => Str::random(40),
                    'author_id' => $this->getRandomAuthor()->id,
                    'publisher' => Str::random(10),
                    'published_at' => now(),
                ],
                'categories' => $this->getRandomCategories()->pluck('id')->toArray(),
                'image' => new Image([
                    'url' => 'https://www.chickenhousebooks.com/wp-content/uploads/2023/02/The-Housetrap-Emma-Read-195x300.jpg'
                ]),
            ],
            [
                'data' => [
                    'name' => 'Dandy the Highway Lion',
                    'price' => 300,
                    'description' => Str::random(40),
                    'author_id' => $this->getRandomAuthor()->id,
                    'publisher' => Str::random(10),
                    'published_at' => now(),
                ],
                'categories' => $this->getRandomCategories()->pluck('id')->toArray(),
                'image' => new Image([
                    'url' => 'https://www.chickenhousebooks.com/wp-content/uploads/2022/12/Dandy-the-Highway-Lion-195x300.jpg'
                ]),
            ],
            [
                'data' => [
                    'name' => 'The Wall Between Us',
                    'price' => 300,
                    'description' => Str::random(40),
                    'author_id' => $this->getRandomAuthor()->id,
                    'publisher' => Str::random(10),
                    'published_at' => now(),
                ],
                'categories' => $this->getRandomCategories()->pluck('id')->toArray(),
                'image' => new Image([
                    'url' => 'https://www.chickenhousebooks.com/wp-content/uploads/2022/12/Wall-Between-Us-The-195x300.png'
                ]),
            ],
        ];

        foreach ($books as $book) {
            $newBook = Book::create($book['data']);
            $newBook->categories()->attach($book['categories']);
            $newBook->image()->save($book['image']);
        }
    }

    public function getRandomAuthor()
    {
        return Author::query()->inRandomOrder()->first();
    }

    public function getRandomCategories()
    {
        return Category::query()->inRandomOrder()->limit(3)->get();
    }
}
