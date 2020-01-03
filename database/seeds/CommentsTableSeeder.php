<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\Information;
use App\Comment;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CommentsTableSeeder extends Seeder
{
    const COMMENTS_COUNT_MIN = 2;
    const COMMENTS_COUNT_MAX = 10;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    private function getComments()
    {
        return factory(Comment::class, rand(self::COMMENTS_COUNT_MIN, self::COMMENTS_COUNT_MAX))->create(['owner_id' => User::all()->random()->id]);
    }
    
    private function saveThroughModel(Model $model)
    {
        $model->comments()->saveMany($this->getComments());
    }
    
    public function run()
    {
        $posts = Post::all()->each(function ($post) {
            $this->saveThroughModel($post);
        });
        
        $informations = Information::all()->each(function ($information) {
            $this->saveThroughModel($information);
        });
    }
}
