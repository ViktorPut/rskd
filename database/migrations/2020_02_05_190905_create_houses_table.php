<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            //Таблица объектов недвижимости
            $table->bigIncrements('id');
            $table->string('floors')->nullable()->comment('Количество этажей');
            $table->string('rooms')->nullable()->comment('Количество комнат');
            $table->decimal('space',2)->comment('Площадь');
            $table->decimal('cost',2)->comment('Стоимость');
            $table->longText('description')->nullable()->comment('Описание');
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('houses');
    }
}
