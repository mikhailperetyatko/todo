<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\User;
use App\Tag;

class PostsTableSeeder extends Seeder
{
    const TAGS_COUNT_MIN = 5;
    const TAGS_COUNT_MAX = 10;
    const USERS_COUNT = 3;
    const POSTS_COUNT = 20;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        app()->singleton('tags', function() {
            return factory(Tag::class, rand(self::TAGS_COUNT_MIN, self::TAGS_COUNT_MAX))->make();
        });
        
        app()->singleton('users', function() {
            return factory(User::class, self::USERS_COUNT)->create();
        });
        
        factory(Post::class, self::POSTS_COUNT)
            ->create()
            ->each(function($post) { 
                    $post->tags()->saveMany(app('tags')->random(rand(1, self::TAGS_COUNT_MIN)));
                }
            )
        ;
    }
}
