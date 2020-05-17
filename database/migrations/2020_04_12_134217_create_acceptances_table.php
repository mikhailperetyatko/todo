<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acceptances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            
            $table->unsignedBigInteger('subtask_id');
            $table->foreign('subtask_id')->references('id')->on('subtasks')->onDelete('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('executor_id');
            $table->foreign('executor_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unsignedBigInteger('validator_id');
            $table->foreign('validator_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->text('executor_report')->nullable();
            $table->dateTime('report_date')->nullable();
            $table->text('validator_annotation')->nullable();
            $table->dateTime('annotation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acceptances');
    }
}
