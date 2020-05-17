<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('value');
            $table->string('description');
            $table->timestamps();
        });
        
        factory(\App\ReferenceNotification::class)
            ->create(
                [
                    'name' => 'Электронная почта',
                    'value' => 'email',
                    'description' => 'Сообщение будет отправлено на электронную почту',
                ]
            )->save()
        ;
        
        factory(\App\ReferenceNotification::class)
            ->create(
                [
                    'name' => 'Pushall сервис',
                    'value' => 'pushall',
                    'description' => 'Сообщение будет отправлено через сервис Pushall. Для получения этого сообщения требуется установка приложения pushall на мобильный телефон',
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
        Schema::dropIfExists('reference_notifications');
    }
}
