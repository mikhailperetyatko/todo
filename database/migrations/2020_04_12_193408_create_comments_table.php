<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('body');
            $table->unsignedBigInteger('subtask_id');
            $table->foreign('subtask_id')->references('id')->on('subtasks')->onDelete('cascade');
            $table->unsignedBigInteger('refer_comment_id')->nullable();
            $table->foreign('refer_comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->boolean('important')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
