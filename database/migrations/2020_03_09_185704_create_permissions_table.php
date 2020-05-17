<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value');
            $table->string('description');
            $table->timestamps();
        });
        
        factory(\App\Permission::class)
            ->create(
                [
                    'value' => 'read',
                    'description' => 'Право чтения',
                ]
            )->save()
        ;
        
        factory(\App\Permission::class)
            ->create(
                [
                    'value' => 'write',
                    'description' => 'Право изменения',
                ]
            )->save()
        ;
        
        factory(\App\Permission::class)
            ->create(
                [
                    'value' => 'complete',
                    'description' => 'Право исполнение',
                ]
            )->save()
        ;
        
        factory(\App\Permission::class)
            ->create(
                [
                    'value' => 'finish',
                    'description' => 'Право завершение',
                ]
            )->save()
        ;
        
        factory(\App\Permission::class)
            ->create(
                [
                    'value' => 'delete',
                    'description' => 'Право удаления',
                ]
            )->save()
        ;
        
        factory(\App\Permission::class)
            ->create(
                [
                    'value' => 'delay',
                    'description' => 'Право отсрочки',
                ]
            )->save()
        ;
        
        factory(\App\Permission::class)
            ->create(
                [
                    'value' => 'delegation',
                    'description' => 'Право поручения',
                ]
            )->save()
        ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
