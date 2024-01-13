<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizeTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('synchronize_transactions', function (Blueprint $table) {
            $table->uuid('synchronize_transaction_id');
            $table->string('reference_number');
            $table->unsignedBigInteger('order_number');
            $table->decimal('amount', 10,2 );
            $table->string('staff_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->string('transaction_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('synchronize_transactions');
    }
}
