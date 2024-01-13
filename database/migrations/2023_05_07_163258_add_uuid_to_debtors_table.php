<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidToDebtorsTable extends Migration
{
    public function up()
    {
        $uuid = Str::uuid();
        Schema::table('debtors', static function (Blueprint $table) use ($uuid) {
            $table->string('synchronization_debtor_id')->default($uuid);
        });
    }

    public function down()
    {
        Schema::table('debtors', static function (Blueprint $table) {
            $table->dropColumn(['synchronization_debtor_id']);
        });
    }
}
