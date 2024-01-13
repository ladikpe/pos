<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnums;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Helpers\GenerateRandomNumber;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{

    protected const UserGenderMale = 'male';
    protected const UserGenderFemale = 'female';

    public function run()
    {
         $this->lagosBranchUsers();
    }

    public function LagosBranchUsers(Faker $faker)
    {
        $lagosBranch = Branch::getBranchId(1);
        DB::table('users')->insert([
                [
                'staff_id' => (new \App\Helpers\GenerateRandomNumber)->uniqueRandomNumber('UGL-STAFF-', 10),
                'phone_no' => $faker->e164PhoneNumber,
                'gender' => self::UserGenderMale,
                'branch_id' => $lagosBranch,
                'first_name' => 'UGL-LAGOS',
                'last_name' => 'SUPER-ADMIN',
                'email' => 'akanmayowa@yahoo.com',
                'password' => bcrypt('123456'),
                'user_role' => UserRoleEnums::SuperAdmin,
                'created_at' => Carbon::now(),
                'updated_at'=> Carbon::now(),
            ],
            [
                'staff_id' => (new \App\Helpers\GenerateRandomNumber)->uniqueRandomNumber('UGL-STAFF-', 10),
                'phone_no' => $faker->e164PhoneNumber,
                'gender' => self::UserGenderMale,
                'branch_id' => $lagosBranch,
                'first_name' => 'UGL-LAGOS',
                'last_name' => 'ADMIN',
                'email' => 'akanmayowa@yahoo.com',
                'password' => bcrypt('123456'),
                'user_role' => UserRoleEnums::Cashier,
                'created_at' => Carbon::now(),
                'updated_at'=> Carbon::now(),
            ],
            [
                'staff_id' => (new \App\Helpers\GenerateRandomNumber)->uniqueRandomNumber('UGL-STAFF-', 10),
                'phone_no' => $faker->e164PhoneNumber,
                'gender' => self::UserGenderMale,
                'branch_id' => $lagosBranch,
                'first_name' => 'UGL-LAGOS',
                'last_name' => 'CASHIER',
                'email' => 'akanmayowa@yahoo.com',
                'password' => bcrypt('123456'),
                'user_role' => UserRoleEnums::Cashier,
                'created_at' => Carbon::now(),
                'updated_at'=> Carbon::now(),
            ]

      ]);
    }
}
