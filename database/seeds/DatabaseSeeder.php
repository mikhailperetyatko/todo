<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)
            ->create([
                'name' => 'Михаил Перетятько',
                'email' => 'admin@mail.ru',
                'password' => \Hash::make('Password1!')
            ])
            ->roles()
            ->save(factory(\App\Role::class)->create([
                'name' => config('auth.admins.super.alias'),
                'posts_right' => 'm',
                'feedbacks_right' => 'm',
                'tags_right' => 'm',
                'users_right' => 'm',
            ]))
        ;

    }
}
