<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()
                ->constrained('contracts');
            $table->foreignId('invoice_id')->nullable()
                ->constrained('invoices');
            $table->datetime('collection_date');
            $table->foreignId('user_id')->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->double('amount_of_money', 50);
            $table->string('payment_type', 255);
            $table->text('note')->nullable();
            $table->foreignId('receipt_reason_id')
                ->constrained('receipt_reasons')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // $table->foreignId('project_id')
            //     ->constrained('projects')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');
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
        Schema::dropIfExists('receipts');
    }
}
