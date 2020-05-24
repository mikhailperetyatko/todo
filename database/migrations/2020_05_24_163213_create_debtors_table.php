<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debtors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            
            $table->text('name');
            $table->json('residencePeriods')->nullable();
            $table->json('property')->nullable();
            $table->date('birthday')->nullable();
            $table->date('deathday')->nullable();
            $table->text('address')->nullable();
            
            $table->unsignedBigInteger('father_id')->nullable();
            $table->foreign('father_id')->references('id')->on('debtors')->onDelete('set null');
            
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->foreign('mother_id')->references('id')->on('debtors')->onDelete('set null');
            
            $table->unsignedBigInteger('debtor_type_id');
            $table->foreign('debtor_type_id')->references('id')->on('debtor_types')->onDelete('cascade');
            
            $table->unsignedBigInteger('debtor_account_id');
            $table->foreign('debtor_account_id')->references('id')->on('debtor_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debtors');
    }
}
