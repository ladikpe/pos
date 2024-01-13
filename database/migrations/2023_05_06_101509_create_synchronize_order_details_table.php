<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizeOrderDetailsTable extends Migration
{

    public function up()
    {
        Schema::create('synchronize_order_details', function (Blueprint $table) {
            $table->uuid('synchronize_inventory_id');
            $table->string('inventory_name');
            $table->unsignedBigInteger('quantity');
            $table->decimal('price');
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('synchronize_order_details');
    }
}
