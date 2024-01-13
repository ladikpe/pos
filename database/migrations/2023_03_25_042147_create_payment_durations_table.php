<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDurationsTable extends Migration
{

    public function up()
    {
        Schema::create('payment_durations', function (Blueprint $table) {
            $table->id();
            $table->string('duration');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_durations');
    }
}
