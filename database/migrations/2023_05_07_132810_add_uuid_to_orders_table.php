<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidToOrdersTable extends Migration
{

    public function up()
    {
        $uuid = Str::uuid();
        Schema::table('orders', function (Blueprint $table) use ($uuid){
            $table->string('synchronization_order_id')->default($uuid);
        });
    }


    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['synchronization_order_id']);
        });
    }
}
