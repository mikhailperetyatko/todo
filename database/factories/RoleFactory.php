<?php

use Faker\Generator as Faker;

$factory->define(App\Role::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'posts_right' => 'w',
        'feedbacks_right' => 'w',
        'tags_right' => 'w',
        'users_right' => '-',
    ];
});
