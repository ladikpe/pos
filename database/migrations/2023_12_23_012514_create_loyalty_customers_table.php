<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_customers', function (Blueprint $table) {
            Schema::dropIfExists('loyalty_users');
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('loyalty_id')->constrained('loyalties');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('category_id');
            $table->integer('amount_gain_by_points');
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
        Schema::dropIfExists('loyalty_customers');
    }
}
