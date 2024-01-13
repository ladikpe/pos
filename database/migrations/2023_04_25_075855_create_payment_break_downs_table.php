<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentBreakDownsTable extends Migration
{

    public function up()
    {
        Schema::create('payment_break_downs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_mode_id')->constrained();
            $table->foreignId('transaction_id')->constrained();
            $table->string('transaction_mode')->nullable();
            $table->decimal('amount',10,2 );
            $table->string('transaction_reference_number');
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('payment_break_downs');
    }
}
