<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPosts();
    }

    public function createPosts()
    {
        Post::create([
            'title' => 'title post 1',
            'content' => 'post content',
            'user_id' => User::first()->id,
            'category_id' => Category::first()->id,
        ]);
    }
}
