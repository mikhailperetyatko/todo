<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();
           
            $table->primary(['role_id', 'user_id']);
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
        
        factory(\App\User::class)
            ->create([
                'name' => 'Михаил Перетятько',
                'email' => 'admin@mail.ru',
                'password' => \Hash::make('Password1!')
            ])
            ->roles()
            ->save(factory(\App\Role::class)->create([
                'name' => config('auth.admin.alias'),
            ]))
        ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }
}
