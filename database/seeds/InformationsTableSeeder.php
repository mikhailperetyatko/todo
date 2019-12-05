<?php

use Illuminate\Database\Seeder;
use App\Information;
use App\User;
use App\Tag;

class InformationsTableSeeder extends Seeder
{
    const TAGS_COUNT_MIN = 5;
    const TAGS_COUNT_MAX = 10;
    const INFORMATIONS_COUNT = 20;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        $tags = Tag::all();
        factory(Information::class, self::INFORMATIONS_COUNT)
            ->create(['owner_id' => User::first()])
            ->each(function($information) use ($tags) { 
                    $information->tags()->saveMany($tags->random(rand(1, self::TAGS_COUNT_MIN)));
                }
            )
        ;
    }
}