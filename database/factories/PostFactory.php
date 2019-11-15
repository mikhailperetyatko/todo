<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'owner_id' => factory(\App\User::class)->create()->id,
        'slug' => $faker->unique()->words(1, true),
        'title' => $faker->words(5, true),
        'description' => $faker->sentence,
        'body' => $faker->sentence,
        'published' => (bool) rand(0, 1)
    ];
});
