<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BankSeeder extends Seeder
{
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('banks')->truncate();
        DB::table('banks')->insert([
            [  'name' =>  'United Bank For Africa Plc',
                'acn_name' => 'UBA Marina',
                'acn_no' => '101-4274-050',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA Obalende',
                'acn_no' => '101-9906-800',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA Warehouse',
                'acn_no' => '1016143967',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA Transport Operations',
                'acn_no' => '1023647447',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA Transport Expenses',
                'acn_no' => '1021920717',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'SunTrust Bank Nigeria Limited',
                'acn_name' => 'SunTrust (Naira)',
                'acn_no' => '000-109-1190',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'SunTrust Bank Nigeria Limited',
                'acn_name' => 'SunTrust (USD)',
                'acn_no' => '000-125-5439',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'Zenith Bank Plc',
                'acn_name' => 'Zenith Bank (USD)',
                'acn_no' => '507-178-2305',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'Guaranty Trust Bank Plc',
                'acn_name' => 'GTB Katsina Expenses',
                'acn_no' => '043-2598-318',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA Tokarawa Operations',
                'acn_no' => '100-0796-278',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'Zenith Bank Plc - EURO',
                'acn_name' => 'Zenith Bank (EURO)',
                'acn_no' => '508-044-7530',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA (USD)',
                'acn_no' => '3001117351',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'Zenith Bank Plc',
                'acn_name' => 'Zenith Bank (GBP)',
                'acn_no' => '506-045-2710',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],



            [  'name' =>  'Access Bank Plc',
                'acn_name' => 'Access Bank Sharada Operations',
                'acn_no' => '0055997171',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA Tokarawa Expenses',
                'acn_no' => '101-9973-781',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'Guaranty Trust Bank Plc',
                'acn_name' => 'GTB Sharada Operations',
                'acn_no' => '014-6344-236',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'Guaranty Trust Bank Plc',
                'acn_name' => 'GTB Katsina Operations',
                'acn_no' => '043-2598-239',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],



            [  'name' =>  'Jaiz Bank Plc',
                'acn_name' => 'Jaiz Bank Sharada Operations',
                'acn_no' => '0005088995',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [  'name' =>  'Zenith Bank Plc',
                'acn_name' => 'Zenith Bank Sharada Operations',
                'acn_no' => '1012657657',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'First City Monument Bank Plc',
                'acn_name' => 'FCMB Tokarawa Operations',
                'acn_no' => '6421980010',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


            [  'name' =>  'Guaranty Trust Bank Plc',
                'acn_name' => 'GTB Tokarawa Operations',
                'acn_no' => '0528-850-445',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [  'name' =>  'Guaranty Trust Bank Plc',
                'acn_name' => 'GTB Tokarawa Expenses',
                'acn_no' => '002-0283-235',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA Sharada Operations',
                'acn_no' => '101-7082-230',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [  'name' =>  'United Bank for Africa Plc',
                'acn_name' => 'UBA Sharada (Expenses)',
                'acn_no' => '101-9971-684',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }

}
