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
        $role = factory(\App\Role::class)->create();
            
        $users = factory(User::class, self::USERS_COUNT)
            ->create()
            ->each(function ($user) use ($role) {
                $user->roles()->save($role);
            })
        ;
        $tags = factory(Tag::class, rand(self::TAGS_COUNT_MIN, self::TAGS_COUNT_MAX))->create();
        
        factory(Post::class, self::POSTS_COUNT)
            ->create(['owner_id' => $users->first()])
            ->each(function($post) use ($users, $tags) { 
                    $post->update(['owner_id' => $users->random()->id]);
                    $post->tags()->saveMany($tags->random(rand(1, self::TAGS_COUNT_MIN)));
                }
            )
        ;
    }
}
