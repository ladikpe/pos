<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToPaymentBreakDownsTable extends Migration
{

    public function up()
    {
        Schema::table('payment_break_downs', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('payment_break_downs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
