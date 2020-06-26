<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Debtor;

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
            $table->json('ownership')->nullable();
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
        
        factory(Debtor::class)
            ->create(
                [
                    'name' => 'Иванов Иван Иванович',
                    'residencePeriods' => [
                        [
                            "start" => "2020-01-02", 
                            "end" => "2020-01-20",
                        ], [
                            "start" => "2020-02-02", 
                            "end" => "",
                        ]
                    ],
                    'ownership' => [
                        [
                            "start" => "2020-01-09",
                            "end" => "",
                            "amount" => "1/3",
                        ]
                    ],
                    'birthday' => '2002-01-05',
                    'deathday' => '2020-03-01',
                    'debtor_type_id' => 1,
                    'debtor_account_id' => 1,
                ]
            )
        ;
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
