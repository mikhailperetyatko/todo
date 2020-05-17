<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\PreinstallerTask;

class CreatePreinstallerTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preinstaller_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->string('name');
            $table->boolean('repeatability')->default(false);
            $table->unsignedBigInteger('reference_interval_id')->default(2);
            $table->foreign('reference_interval_id')->references('id')->on('reference_intervals')->onDelete('cascade');
            $table->integer('interval_value')->default(0);
            $table->json('subtasks');
        });
        
        factory(PreinstallerTask::class)
            ->create(
                [
                    'name' => 'Собрание кредиторов',
                    'repeatability' => true,
                    'reference_interval_id' => 6,
                    'interval_value' => 1,
                    'subtasks' => [
                        [
                            'description' => 'Подготовить уведомление о собрании кредиторов',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => -15,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 2,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Опубликовать сообщение о собрании кредиторов должника в ЕФРСБ',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => -15,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Отправить уведомление о собрании кредиторов',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => -15,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Составление отчетов конкурсного управляющего и иных материалов по повестке',
                            'showable_by' => 3,
                            'reference_interval_id' => 3,
                            'delay' => -10,
                            'reference_difficulty_id' => 4,
                            'reference_priority_id' => 3,
                            'score' => 5,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Подготовка бюллетеней и журнала',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => -3,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 1,
                            'score' => 1,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Отправить отчеты и иные необходимые материалы по собранию кредиторов в адрес СРО',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => -5,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Участие в собрании кредиторов',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 0,
                            'reference_difficulty_id' => 2,
                            'reference_priority_id' => 3,
                            'score' => 3,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Подготовка протокола собрания кредиторов',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 1,
                            'reference_difficulty_id' => 2,
                            'reference_priority_id' => 2,
                            'score' => 3,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Отправка протокола собрания кредиторов в СРО',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 1,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Отправка протокола собрания кредиторов с материалами в Арбитражный суд',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => 2,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 3,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Опубликование результатов собрания кредиторов в ЕФРСБ',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => 2,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 2,
                            'not_delayable' => true,
                        ],
                    ],
                ]
            )
        ;
        
        factory(PreinstallerTask::class)
            ->create(
                [
                    'name' => 'Заседание комитета кредиторов',
                    'repeatability' => true,
                    'reference_interval_id' => 6,
                    'interval_value' => 1,
                    'subtasks' => [
                        [
                            'description' => 'Подготовить уведомление о заседании комитета кредиторов',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => -15,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 2,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Опубликовать сообщение о заседании комитета кредиторов в ЕФРСБ',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => -15,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Отправить уведомление о заседании комитета кредиторов',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => -15,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Составление отчетов конкурсного управляющего и иных материалов по повестке',
                            'showable_by' => 3,
                            'reference_interval_id' => 3,
                            'delay' => -10,
                            'reference_difficulty_id' => 4,
                            'reference_priority_id' => 3,
                            'score' => 5,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Подготовка бюллетеней и журнала',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => -3,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 1,
                            'score' => 1,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Отправить отчеты и иные необходимые материалы по заседанию комитета кредиторов в адрес СРО',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => -5,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Участие в заседании комитета кредиторов',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 0,
                            'reference_difficulty_id' => 2,
                            'reference_priority_id' => 3,
                            'score' => 3,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Подготовка протокола заседания комитета кредиторов',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 1,
                            'reference_difficulty_id' => 2,
                            'reference_priority_id' => 2,
                            'score' => 3,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Отправка протокола заседания комитета кредиторов в СРО',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 1,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Отправка протокола заседания комитета кредиторов с материалами в Арбитражный суд',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => 2,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 3,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Опубликование результатов заседания комитета кредиторов в ЕФРСБ',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => 2,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 2,
                            'not_delayable' => true,
                        ],
                    ],
                ]
            )
        ;
        
        factory(PreinstallerTask::class)
            ->create(
                [
                    'name' => 'Собрание работников должника',
                    'repeatability' => true,
                    'reference_interval_id' => 6,
                    'interval_value' => 1,
                    'subtasks' => [
                        [
                            'description' => 'Подготовить уведомление о собрании работников должника',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => -31,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 2,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Подготовка бюллетеней',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => -30,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 1,
                            'score' => 1,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Опубликовать сообщение о собрании работников должника в ЕФРСБ',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => -30,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Отправить уведомление о собрании работников должника',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => -30,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Подготовка протокола собрания работников должника',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 1,
                            'reference_difficulty_id' => 2,
                            'reference_priority_id' => 2,
                            'score' => 3,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Отправка протокола собрания работников должника с материалами в Арбитражный суд',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => 1,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 3,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Опубликование результатов собрания работников должника в ЕФРСБ',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => 1,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 2,
                            'not_delayable' => true,
                        ],
                    ],
                ]
            )
        ;
        
        factory(PreinstallerTask::class)
            ->create(
                [
                    'name' => 'Ежеквартальная отчетность финансового управляющего',
                    'repeatability' => true,
                    'reference_interval_id' => 6,
                    'interval_value' => 1,
                    'subtasks' => [
                        [
                            'description' => 'Получить выписки банка и иные документы, необходимые для подготовки отчета финансового управляющего',
                            'showable_by' => 3,
                            'reference_interval_id' => 3,
                            'delay' => -20,
                            'reference_difficulty_id' => 2,
                            'reference_priority_id' => 3,
                            'score' => 3,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Составление отчета финансового управляющего',
                            'showable_by' => 3,
                            'reference_interval_id' => 3,
                            'delay' => -7,
                            'reference_difficulty_id' => 4,
                            'reference_priority_id' => 3,
                            'score' => 10,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Отправить отчет в адрес кредиторов',
                            'showable_by' => 3,
                            'reference_interval_id' => 3,
                            'delay' => 0,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 3,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Отправка отчета в СРО',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => 0,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 4,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                        [
                            'description' => 'Отправка отчета в Арбитражный суд',
                            'showable_by' => 2,
                            'reference_interval_id' => 3,
                            'delay' => 0,
                            'reference_difficulty_id' => 1,
                            'reference_priority_id' => 4,
                            'score' => 1,
                            'not_delayable' => true,
                        ],
                    ],
                ]
            )
        ;
        
        factory(PreinstallerTask::class)
            ->create(
                [
                    'name' => 'Судебный спор (в качестве истца)',
                    'repeatability' => false,
                    'subtasks' => [
                        [
                            'description' => 'Подготовить возражение на отзыв (иной документ) ответчика',
                            'showable_by' => 5,
                            'reference_interval_id' => 3,
                            'delay' => -20,
                            'reference_difficulty_id' => 3,
                            'reference_priority_id' => 2,
                            'score' => 10,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Участие в судебном заседании',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 0,
                            'reference_difficulty_id' => 4,
                            'reference_priority_id' => 3,
                            'score' => 10,
                            'not_delayable' => false,
                        ],
                    ],
                ]
            )
        ;
        
        factory(PreinstallerTask::class)
            ->create(
                [
                    'name' => 'Судебный спор (в качестве ответчика)',
                    'repeatability' => false,
                    'subtasks' => [
                        [
                            'description' => 'Подготовить отзыв на исковое заявление (иной документ) истца',
                            'showable_by' => 5,
                            'reference_interval_id' => 3,
                            'delay' => -30,
                            'reference_difficulty_id' => 3,
                            'reference_priority_id' => 2,
                            'score' => 10,
                            'not_delayable' => false,
                        ],
                        [
                            'description' => 'Участие в судебном заседании',
                            'showable_by' => 1,
                            'reference_interval_id' => 3,
                            'delay' => 0,
                            'reference_difficulty_id' => 4,
                            'reference_priority_id' => 3,
                            'score' => 10,
                            'not_delayable' => false,
                        ],
                    ],
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
        Schema::dropIfExists('preinstaller_tasks');
    }
}
