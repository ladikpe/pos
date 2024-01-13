<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashDepositsTable extends Migration
{

    public function up()
    {
        Schema::create('cash_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('bank_of_deposit');
            $table->decimal('amount');
            $table->string('date_of_deposit');
            $table->foreignId('user_id')->nullable()
                                                ->constrained();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('cash_deposits');
    }
}
