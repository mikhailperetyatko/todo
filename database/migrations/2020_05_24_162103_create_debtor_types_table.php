<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebtorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debtor_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->string('value');
        });
        
        factory(\App\DebtorType::class)
            ->create(
                [
                    'name' => 'Собственник',
                    'value' => 'owner',
                ]
            )->save()
        ;
        
        factory(\App\DebtorType::class)
            ->create(
                [
                    'name' => 'Наниматель',
                    'value' => 'employer',
                ]
            )->save()
        ;
        
        factory(\App\DebtorType::class)
            ->create(
                [
                    'name' => 'Сожитель',
                    'value' => 'cohabitant',
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
        Schema::dropIfExists('debtor_types');
    }
}
