<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_infos', function (Blueprint $table) {
            $table->id();
            $table->string('student_code', 255)->unique();
            $table->string('department', 255);
            $table->integer('student_year')->length(2);
            $table->string('nation', 255)->nullable();
            $table->string('religion', 255)->nullable();
            $table->string('CCCD')->unique();
            $table->datetime('date_range'); // Ngày cấp căn cước công dân
            $table->string('issued_by', 255); // nơi cấp
            $table->integer('student_object')->nullable(11);// đối tượng
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('school', 255);
            // Thiếu mã trường học cho thêm sau nhé
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
        Schema::dropIfExists('student_infos');
    }
}
