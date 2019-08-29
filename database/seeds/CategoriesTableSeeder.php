<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createCategories();
    }

    public function createCategories()
    {
        Category::create([
            'name' => 'category 1',
            'description' => 'category description',
        ]);
    }
}
