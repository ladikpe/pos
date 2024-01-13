<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToBranchTable extends Migration
{

    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->softDeletes();
        });
    }


    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
