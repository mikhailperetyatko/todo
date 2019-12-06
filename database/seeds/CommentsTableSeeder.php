<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\Information;
use App\Comment;
use App\User;

class CommentsTableSeeder extends Seeder
{
    const COMMENTS_COUNT_MIN = 5;
    const COMMENTS_COUNT_MAX = 10;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $posts = Post::all();
        $informations = Information::all();
        factory(Comment::class, rand(self::COMMENTS_COUNT_MIN, self::COMMENTS_COUNT_MAX))
            ->create(['owner_id' => User::first()])
            ->each(function($comment) use ($posts, $informations) { 
                    $comment->owner_id = User::orderBy(DB::raw('RAND()'))->take(1)->first()->id;
                    if (rand(0, 1)) {
                        $comment->posts()->saveMany($posts->random(1));
                    } else {
                        $comment->informations()->saveMany($informations->random(1));
                    }
                    $comment->save();
                }
            )
        ;
    }
}
