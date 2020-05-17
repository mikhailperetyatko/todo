<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceDifficultiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_difficulties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('value');
            $table->string('description');
            $table->integer('score');
            $table->timestamps();
        });
        
        factory(\App\ReferenceDifficulty::class)
            ->create(
                [
                    'name' => 'Низкая',
                    'value' => 'low',
                    'description' => 'Для задач, на выполнение которых не требуется много времени',
                    'score' => 1,
                ]
            )->save()
        ;
        
        factory(\App\ReferenceDifficulty::class)
            ->create(
                [
                    'name' => 'Средняя',
                    'value' => 'middle',
                    'description' => 'Для задач, на выполнение которых потребуется не менее 2 часов',
                    'score' => 3,
                ]
            )->save()
        ;
        
        factory(\App\ReferenceDifficulty::class)
            ->create(
                [
                    'name' => 'Сложная',
                    'value' => 'high',
                    'description' => 'Для задач, на выполнение которых потребуется не менее 4 часов',
                    'score' => 5,
                ]
            )->save()
        ;
        
        factory(\App\ReferenceDifficulty::class)
            ->create(
                [
                    'name' => 'Особо сложная',
                    'value' => 'supreme',
                    'description' => 'Для задач, на выполнение которых потребуется не менее 8 часов',
                    'score' => 10,
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
        Schema::dropIfExists('reference_difficulties');
    }
}
