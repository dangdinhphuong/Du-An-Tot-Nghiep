<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenaces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('type', 255);
            $table->string('remind', 255);
            $table->text('note');
            // thieus 2 khoá phụ tasks giống bên trên bảng tasks
            // 2 khóa phụ này đều là khóa phụ của bảng user nhé nhưng tạo 2 khóa để phân biệt ai là người giao ai là người nhận

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
        Schema::dropIfExists('maintenaces');
    }
}
