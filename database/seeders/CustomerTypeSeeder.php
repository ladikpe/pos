<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerTypeSeeder extends Seeder
{
   public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('customer_types')->truncate();
                \App\Models\CustomerType::create([
                    'types' => 'retail',
                    'price_type' => 'retail',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                \App\Models\CustomerType::create([
                    'types' =>  'dealer',
                    'price_type' => 'dealer',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                \App\Models\CustomerType::create([
                    'types' => 'staff',
                    'price_type' => 'staff',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                \App\Models\CustomerType::create([
                    'types' => 'crs',
                    'price_type' => 'crs',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

    }
}
