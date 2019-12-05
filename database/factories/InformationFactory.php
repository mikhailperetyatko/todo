<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Information;
use Faker\Generator as Faker;

$factory->define(Information::class, function (Faker $faker) {
    return [
        'owner_id' => factory(\App\User::class),
        'slug' => $faker->unique()->words(1, true),
        'title' => 'news_' . $faker->words(5, true),
        'body' => $faker->sentence
    ];
});
