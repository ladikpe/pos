<?php

namespace Database\Seeders;

use App\Models\BusinessSegment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class BusinessSegmentSeeder extends Seeder
{

    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('business_segments')->truncate();
           DB::table('business_segments')->insert([
                  [  'name' =>  'Restaurant',
                    'description' => 'Restaurants',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                 ],
                  [  'name' =>  'Hotels',
                   'description' => 'Hotels',
                   'created_at' => Carbon::now(),
                   'updated_at' => Carbon::now(),
                  ],
                   [  'name' =>  'Individual',
                        'description' => 'Individual',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ],
                   [  'name' =>  'Religious Houses',
                       'description' => 'Religious Houses',
                       'created_at' => Carbon::now(),
                       'updated_at' => Carbon::now(),
                   ],
               [  'name' =>  'Bakery',
                    'description' => 'Bakery',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
               [  'name' =>  'Staff',
                   'description' => 'Staff',
                   'created_at' => Carbon::now(),
                   'updated_at' => Carbon::now(),
               ],


               [  'name' =>  'Dealers',
                    'description' => 'Dealers',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]



           ]);



    }

}
