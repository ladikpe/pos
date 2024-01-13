<?php

namespace Database\Seeders;

use App\Models\TransactionMode;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransactionModeSeeder extends Seeder
{

    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('transaction_modes')->truncate();

        TransactionMode::create([
            'transaction_mode' =>  'POS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionMode::create([
                'transaction_mode' => 'Cash',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            TransactionMode::create([
                'transaction_mode' =>  'Transfer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            TransactionMode::create([
                'transaction_mode' => 'PayLater',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            TransactionMode::create([
                'transaction_mode' => 'Loyalty Discount',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

    }
}
