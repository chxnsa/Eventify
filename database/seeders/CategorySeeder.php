<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Concerts & Music',
                'slug' => 'concerts-music',
                'icon' => 'music',
            ],
            [
                'name' => 'Workshops',
                'slug' => 'workshops',
                'icon' => 'tools',
            ],
            [
                'name' => 'Seminars',
                'slug' => 'seminars',
                'icon' => 'presentation',
            ],
            [
                'name' => 'Family & Kids',
                'slug' => 'family-kids',
                'icon' => 'users',
            ],
            [
                'name' => 'Festival',
                'slug' => 'festival',
                'icon' => 'party',
            ],
            [
                'name' => 'Sports & Fitness',
                'slug' => 'sports-fitness',
                'icon' => 'trophy',
            ],
            [
                'name' => 'Art & Exhibition',
                'slug' => 'art-exhibition',
                'icon' => 'palette',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
