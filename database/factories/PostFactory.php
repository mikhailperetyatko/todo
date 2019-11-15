<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'owner_id' => app('users')->random(),
        'slug' => $faker->unique()->words(1, true),
        'title' => $faker->words(5, true),
        'description' => $faker->words(15, true),
        'body' => $faker->words(30, true),
        'published' => (bool) rand(0, 1)
    ];
});
