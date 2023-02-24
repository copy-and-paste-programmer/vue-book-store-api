<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $names=[
            'Adventure stories',
            'Classics',
            'Crime',
            'Fairy tales',
            'Fantasy',
            'Historical fiction',
            'Horror',
            'Humour and satire'
        ];
        foreach($names as $name){
            Category::create([
                'name' => $name,
                'created_at' => now()
            ]);
        }

    }
}
