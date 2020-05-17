<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->primary(['role_id','permission_id']);
        });
                
        $director = factory(\App\Role::class)
            ->create(
                [
                    'name' => 'Руководитель',
                    'slug' => 'director',
                    'description' => 'Полные права',
                ]
            )
        ;
        
        $mainExecutor = factory(\App\Role::class)
            ->create(
                [
                    'name' => 'Главный исполнитель',
                    'slug' => 'mainExecutor',
                    'description' => 'Права на чтение, изменение, исполнение, завершение, поручение',
                ]
            )
        ;
        
        $executor = factory(\App\Role::class)
            ->create(
                [
                    'name' => 'Исполнитель',
                    'slug' => 'executor',
                    'description' => 'Права на чтение, исполнение',
                ]
            )
        ;
        
        $observer = factory(\App\Role::class)
            ->create(
                [
                    'name' => 'Наблюдатель',
                    'slug' => 'observer',
                    'description' => 'Права на чтение',
                ]
            )
        ;
        //read - 1, write - 2, complete - 3, finish - 4, delete - 5, delay - 6, delegation - 7
        
        $director->permissions()->toggle([1, 2, 3, 4, 5, 6, 7]);
        $mainExecutor->permissions()->toggle([1, 2, 3, 4, 7]);
        $executor->permissions()->toggle([1, 3]);
        $observer->permissions()->toggle([1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
