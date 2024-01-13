<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSynchronizeUsersTable extends Migration
{

    public function up()
    {
        Schema::create('synchronize_users', function (Blueprint $table) {
            $table->uuid('synchronize_user_id');
            $table->string('full_name');
            $table->string('phone_number');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('staff_id');
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
        Schema::dropIfExists('synchronize_users');
    }
}
