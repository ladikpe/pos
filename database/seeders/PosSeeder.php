<?php

namespace Database\Seeders;

use App\Models\Pos;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PosSeeder extends Seeder
{


    public function run()
    {
        Pos::create([
            'name' =>  'POS ZENITH',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Pos::create([
            'name' => 'POS UBA',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Pos::create([
            'name' =>  'POS FIDELITY',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
