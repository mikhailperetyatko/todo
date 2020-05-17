<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferencePrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_priorities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('value');
            $table->string('description');
            $table->integer('score');
            $table->timestamps();
        });
        
        factory(\App\ReferencePriority::class)
            ->create(
                [
                    'name' => 'Низкий',
                    'value' => 'low',
                    'description' => 'Для задач с низким приоритетом',
                    'score' => 1,
                ]
            )->save()
        ;
        
        factory(\App\ReferencePriority::class)
            ->create(
                [
                    'name' => 'Средний',
                    'value' => 'middle',
                    'description' => 'Для важных задач',
                    'score' => 2,
                ]
            )->save()
        ;
        
        factory(\App\ReferencePriority::class)
            ->create(
                [
                    'name' => 'Высокий',
                    'value' => 'high',
                    'description' => 'Для особо важных задач, за пропуск которых возможна ответственность',
                    'score' => 3,
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
        Schema::dropIfExists('reference_priorities');
    }
}
