<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Marker;

class CreateMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->unsignedBigInteger('marker_id')->nullable();
            $table->foreign('marker_id')->references('id')->on('markers')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Жалобы',
                    'description' => 'Жалобы лиц, участвующих в деле и процессе',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Разногласия',
                    'description' => 'Разногласия с лицами, участвующими в деле)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Инвентаризация имущества',
                    'description' => 'Различные мероприятия по инвентаризации имущества должника',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Продажа имущества и имущественных прав',
                    'description' => 'Различные мероприятия по продаже имуществом и имущественными правами должника (оценка, продажа)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Работа с дебиторской задолженностью',
                    'description' => 'Различные мероприятия по принудительному взысканию дебиторской задолженности должника',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Работа со сделками должника',
                    'description' => 'Различные мероприятия в отношении сделок должника (расторжение действующих договоров, оспаривание сделок)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Работа с кредиторами должника',
                    'description' => 'Различные мероприятия в отношении кредиторов должника (включение требований в реестр требований кредиторов и текущий реестр, исключение из реестров)',
                ]
            )
        ;

        factory(Marker::class)
            ->create(
                [
                    'name' => 'Собрания кредиторов',
                    'description' => 'Мероприятия по организации собраний кредиторов)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Собрания дольщиков',
                    'description' => 'Мероприятия по организации собраний долщиков)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Заседания комитета кредиторов',
                    'description' => 'Мероприятия по организации комитета кредиторов)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Собрания работников должника',
                    'description' => 'Мероприятия по организации собраний работников должника)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Расчетные счета',
                    'description' => 'Мероприятия по расчетным счетам должника)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Субсидиарная ответственность и убытки с контролирующих должника лиц',
                    'description' => 'Мероприятия по организации привлечения контролирующих должника лиц к субсидиарной ответственности по непогашенным обязательствам должника, взыскания с них убытков)',
                ]
            )
        ;
        
        factory(Marker::class)
            ->create(
                [
                    'name' => 'Иные важные мероприятия',
                    'description' => 'Иные заслуживающие внимания мероприятия для отражения в отчете',
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
        Schema::dropIfExists('markers');
    }
}
