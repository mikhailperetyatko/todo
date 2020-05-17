<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('description');
            $table->dateTime('execution_date');
            $table->dateTime('executed_date');
            
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('subtask_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->string('task_name');
            $table->date('task_execution_date');
            
            $table->unsignedBigInteger('executor_id')->nullable();
            $table->foreign('executor_id')->references('id')->on('users')->onDelete('set null');
            
            $table->unsignedBigInteger('validator_id')->nullable();
            $table->foreign('validator_id')->references('id')->on('users')->onDelete('set null');
            
            $table->unsignedBigInteger('reference_difficulty_id')->default(1)->nullable();
            $table->foreign('reference_difficulty_id')->references('id')->on('reference_difficulties')->onDelete('set null');
            
            $table->integer('score')->default(1);
            $table->string('location')->nullable();
            $table->json('acceptances')->nullable();
            $table->json('comments')->nullable();
            $table->json('files')->nullable();
            $table->json('markers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
