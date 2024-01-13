<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BranchSeeder extends Seeder
{

    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('branches')->truncate();

            Branch::create([
                'name' => 'UGL-LAGOS',
                'phone_no' => '08012341234',
                'email' => 'admin-lagos@uglgas.com',
                'address' => 'LAGOS Nigeria`',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);


            Branch::create([
                'name' => 'UGL-TOKARAWA',
                'phone_no' => '08012341234',
                'email' => 'admin_tokorawa@uglgas.com',
                'address' => 'Tokorawa Nigeria`',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);


            Branch::create([
                'name' => 'UGL-SHARADA',
                'phone_no' => '08012341234',
                'email' => 'admin_sharada@uglgas.com',
                'address' => 'Sharada Nigeria`',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Branch::create([
                'name' => 'UGL-KATSINA',
                'phone_no' => '08012341234',
                'email' => 'admin_katsina@ugl-gas.com',
                'address' => 'Katsina Nigeria`',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

    }
}
