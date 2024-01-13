<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizeOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('synchronize_orders', function (Blueprint $table) {
            $table->uuid('synchronize_order_id');
            $table->string('order_number');
            $table->decimal('amount', 10,2 );
            $table->decimal('discount', 10,2);
            $table->string('staff_name');
            $table->string('customer_name');
            $table->unsignedBigInteger('branch_id');
            $table->string('order_date');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('synchronize_orders');
    }
}
