<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->datetime('start_day');
            $table->datetime('end_day');
            $table->dateTime('cycle_date')->nullable();
            $table->double('price', 100);
            $table->double('deposit', 50);
            $table->string('note', 255);
            $table->foreignId('room_id')
                ->constrained()
                ->onUpdate('cascade');
                // ->onDelete('cascade');
            $table->foreignId('bed_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('deposit_state')->default(false);
            $table->string('project_service_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('contracts');
    }
}
