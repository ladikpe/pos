<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentTypeNameToPaymentBreakDownTable extends Migration
{

    public function up()
    {
        Schema::table('payment_break_downs', function (Blueprint $table) {
            $table->string('payment_type_name')->nullable();
            $table->string('description')->nullable();
        });
    }


    public function down()
    {
        Schema::table('payment_break_downs', function (Blueprint $table) {
            $table->dropColumn(['payment_break_downs', 'description']);
        });
    }
}
