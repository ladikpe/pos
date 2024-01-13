<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Inventory;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{

    protected  Const CustomerName = 'Walk-In-Customer';
    protected  Const CustomerEmail = 'retailCustomer@uglPos.com';
    protected  Const CustomerPhoneNumber = '+234 080 1234 5678';
    protected  Const CustomerType = 1;

    public function run()
    {
        $faker = Faker::create();
        $counter = 0;
        while ($counter <= 20) {

            Customer::create([
                'name' => self::CustomerName,
                'email' => self::CustomerEmail,
                'phone_number' => self::CustomerPhoneNumber,
                'customer_type_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $counter++;
        }
    }
}
