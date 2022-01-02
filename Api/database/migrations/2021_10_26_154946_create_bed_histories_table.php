<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBedHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bed_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')
            ->constrained('rooms')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('bed_id')
            ->constrained('beds')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('contract_id')
            ->constrained('contracts')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->dateTime('day_transfer');
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
        Schema::dropIfExists('bed_histories');
    }
}
