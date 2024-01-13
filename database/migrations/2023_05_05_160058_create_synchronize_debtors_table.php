<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizeDebtorsTable extends Migration
{

    public function up()
    {
        Schema::create('synchronize_debtors', function (Blueprint $table) {
            $table->uuid('synchronize_debtor_id');
            $table->string('debtor_number');
            $table->unsignedBigInteger('order_number');
            $table->decimal('amount', 10,2 );
            $table->string('staff_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->string('transaction_date');
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
        Schema::dropIfExists('synchronize_debtors');
    }
}
