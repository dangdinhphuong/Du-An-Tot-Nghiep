<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceIndexsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_indexes', function (Blueprint $table) {
            $table->id();
            $table->integer('index_water')->length(50);
            $table->integer('index_electric')->length(50);
            $table->foreignId('room_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->text('note');
            $table->string('img', 255);
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('service_indexes');
    }
}
