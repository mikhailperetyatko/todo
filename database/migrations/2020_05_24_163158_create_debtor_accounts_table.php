<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\DebtorAccount;
use Illuminate\Support\Str;

class CreateDebtorAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('debtor_accounts');
        Schema::create('debtor_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('slug');
            $table->text('address');
            $table->float('incoming_saldo');
            $table->json('balance');
            $table->json('payments_relation')->nullable();
            $table->json('claim_calculation')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
        
        factory(DebtorAccount::class)
            ->create(
                [
                    'slug' => '0000001',
                    'address' => 'Вологда',
                    'incoming_saldo' => -100,
                    'balance' => [
                        [
                            "paid" => [
                                [
                                    "date" => null,
                                    "amount" => 100,
                                    'uuid' => (string) Str::uuid(),
                                ]
                            ],
                            "month" => "2020-01-01", 
                            "services" => [
                                [
                                    "name" => "Отопление", 
                                    "amount" => 20, 
                                    "tariff" => 150, 
                                    "onlyOwner" => false, 
                                    "amount_title" => "ГКал",
                                    'uuid' => (string) Str::uuid(),
                                ], [
                                    "name" => "Содержание", 
                                    "amount" => 20, 
                                    "tariff" => 200, 
                                    "onlyOwner" => true, 
                                    "amount_title" => "кв.м.",
                                    'uuid' => (string) Str::uuid(),
                                ],
                            ],
                            "recosting" => [
                                [
                                    "date" => null,
                                    "amount" => -50,
                                    'uuid' => (string) Str::uuid(),
                                ],
                            ],
                        ], [
                            "paid" => [
                                [
                                    "date" => null,
                                    "amount" => 50,
                                    'uuid' => (string) Str::uuid(),
                                ]
                            ], 
                            "month" => "2020-02-01", 
                            "services" => [
                                [
                                    "name" => "Отопление", 
                                    "amount" => 20, 
                                    "tariff" => 150, 
                                    "onlyOwner" => false, 
                                    "amount_title" => "ГКал",
                                    'uuid' => (string) Str::uuid(),
                                ], [
                                    "name" => "Содержание", 
                                    "amount" => 20, 
                                    "tariff" => 200, 
                                    "onlyOwner" => true, 
                                    "amount_title" => "кв.м.",
                                    'uuid' => (string) Str::uuid(),
                                ], 
                            ],
                            "recosting" => [
                                [
                                    "date" => null,
                                    "amount" => 50,
                                    'uuid' => (string) Str::uuid(),
                                ],
                            ], 
                        ],
                    ],
                    'project_id' => 5,
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
        Schema::dropIfExists('debtor_accounts');
    }
}
