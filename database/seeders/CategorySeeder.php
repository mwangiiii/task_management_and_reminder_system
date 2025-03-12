<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category_type=[
            ['type'=> 'home'],
            ['type' => 'school'],
            ['type' => 'work'],
            ['type'=> 'friends'],
            ['type' => 'leisure'],
        ];

        foreach($category_type as $category){
            Category::create($category);
        }
    }
}
