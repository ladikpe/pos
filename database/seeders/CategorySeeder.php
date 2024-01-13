<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class CategorySeeder extends Seeder
{

    //// Inventory or Product Category
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('categories')->truncate();

            Category::create([
                'name' => "LPG Gas Refill",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);


            Category::create([
                'name' => "LPG Accessories",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);


            Category::create([
                'name' => "LPG Cylinders",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

    }
}
