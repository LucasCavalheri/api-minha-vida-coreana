<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\Category::all();
        $posts = \App\Models\Post::all();

        foreach ($posts as $post) {
            $randomCategories = $categories->random(rand(1, 3));
            $categoryIds = $randomCategories->pluck('id')->toArray();
            $post->categories()->attach($categoryIds);
        }
    }
}
