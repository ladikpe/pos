<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidToTransactionTable extends Migration
{

    public function up()
    {
        $uuid = Str::uuid();
        Schema::table('transactions', static function (Blueprint $table) use ($uuid) {
            $table->string('synchronization_transaction_id')->default($uuid);
        });
    }

    public function down()
    {
        Schema::table('transactions', static function (Blueprint $table) {
            $table->dropColumn(['synchronization_transaction_id']);
        });
    }
}
