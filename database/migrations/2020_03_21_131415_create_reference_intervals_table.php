<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceIntervalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_intervals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('value');
            $table->string('description');
            $table->timestamps();
        });
        
        factory(\App\ReferenceInterval::class)
            ->create(
                [
                    'name' => 'Минуты',
                    'value' => 'minute',
                    'description' => 'Единица измерения интервала - минута',
                ]
            )->save()
        ;
        
        factory(\App\ReferenceInterval::class)
            ->create(
                [
                    'name' => 'Часы',
                    'value' => 'hour',
                    'description' => 'Единица измерения интервала - час',
                ]
            )->save()
        ;
        
        factory(\App\ReferenceInterval::class)
            ->create(
                [
                    'name' => 'Сутки',
                    'value' => 'day',
                    'description' => 'Единица измерения интервала - сутки',
                ]
            )->save()
        ;
        
        factory(\App\ReferenceInterval::class)
            ->create(
                [
                    'name' => 'Неделя',
                    'value' => 'week',
                    'description' => 'Единица измерения интервала - неделя',
                ]
            )->save()
        ;
        
        factory(\App\ReferenceInterval::class)
            ->create(
                [
                    'name' => 'Месяц',
                    'value' => 'month',
                    'description' => 'Единица измерения интервала - месяц',
                ]
            )->save()
        ;
        
        factory(\App\ReferenceInterval::class)
            ->create(
                [
                    'name' => 'Квартал',
                    'value' => 'quarter',
                    'description' => 'Единица измерения интервала - квартал',
                ]
            )->save()
        ;
        
        factory(\App\ReferenceInterval::class)
            ->create(
                [
                    'name' => 'Год',
                    'value' => 'year',
                    'description' => 'Единица измерения интервала - год',
                ]
            )->save()
        ;
        
        //ReferenceInterval
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reference_intervals');
    }
}
