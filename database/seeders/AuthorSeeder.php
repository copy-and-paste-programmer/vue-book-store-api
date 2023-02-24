<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authors')->insert([
            'name' => 'William Shakespeare',
            'email' => Str::random(10).'@gmail.com',
            'description' => 'Lorem Ipsum typesetting industry. Lorem Ipsum seeder example .',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('authors')->insert([
            'name' => 'William Faulkner',
            'email' => Str::random(10).'@gmail.com',
            'description' => 'Lorem Ipsum typesetting industry. Lorem Ipsum seeder example .',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('authors')->insert([
            'name' => 'Henry James',
            'email' => Str::random(10).'@gmail.com',
            'description' => 'Lorem Ipsum typesetting industry. Lorem Ipsum seeder example .',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('authors')->insert([
            'name' => 'Jane Austen',
            'email' => Str::random(10).'@gmail.com',
            'description' => 'Lorem Ipsum typesetting industry. Lorem Ipsum seeder example .',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('authors')->insert([
            'name' => 'Charles Dickens',
            'email' => Str::random(10).'@gmail.com',
            'description' => 'Lorem Ipsum typesetting industry. Lorem Ipsum seeder example .',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
