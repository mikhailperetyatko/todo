<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubtasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('description');
            $table->dateTime('execution_date');
            $table->integer('showable_by')->default(0);
            $table->date('showable_date');
            
            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade')->onUpdate('cascade');
            
            $table->unsignedBigInteger('executor_id');
            $table->foreign('executor_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unsignedBigInteger('validator_id');
            $table->foreign('validator_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unsignedBigInteger('reference_interval_id')->default(2);
            $table->foreign('reference_interval_id')->references('id')->on('reference_intervals')->onDelete('cascade');
            $table->integer('delay')->nullable();
            
            $table->unsignedBigInteger('reference_difficulty_id')->default(1);
            $table->foreign('reference_difficulty_id')->references('id')->on('reference_difficulties')->onDelete('cascade');
            
            $table->unsignedBigInteger('reference_priority_id')->default(1);
            $table->foreign('reference_priority_id')->references('id')->on('reference_priorities')->onDelete('cascade');
            
            $table->integer('score')->default(1);
            $table->boolean('not_delayable')->default(false);
            $table->string('location')->nullable();
            $table->boolean('completed')->default(false);
            $table->boolean('finished')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subtasks');
    }
}
