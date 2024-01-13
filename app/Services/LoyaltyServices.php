<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Loyalty;
use Illuminate\Support\Facades\DB;

class LoyaltyServices
{
    protected $loyalty;
    protected $category;
    public function __construct(Loyalty $loyalty, Category $category)
    {
        $this->loyalty = $loyalty;
        $this->category = $category;
    }
    public function addSettings($data): array
    {
        $category = $this->category
            ->where('id', $data['category_id'])
            ->first();

        $loyaltySetting = $this->loyalty::create([
            'loyalty_name' => $category['name'],
            'category_id' => $data['category_id'],
            'points' => $data['point_per_kg'],
            'amount' => $data['amount'],
        ]);

        return [
            'data' => $loyaltySetting,
            'message' => 'loyalty created successfully',
            'statusCode' => 201,
            'status' => true
        ];
    }
    public function index(): array
    {
        return [
              'data' => $this->loyalty->first(),
              'message' => 'Loyalty list',
              'statusCode' => 200,
              'status' => true
        ];
    }

    public function addLoyaltyAmount($data): void
    {
        $loyalty = Loyalty::where('category_id', $data['category_id'])->first();

        if($loyalty) {
            $total = (int)$loyalty->points * (int)$loyalty->amount;
            $calculateAmountGained = (int)$total * (int)$data['quantity'];

            DB::table('loyalty_customers')->insert([
                'customer_id' => $data['customer_id'],
                'loyalty_id' => $loyalty['id'],
                'order_id' => $data['order_id'],
                'category_id' => $loyalty['category_id'],
                'amount_gain_by_points' => $calculateAmountGained,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if(($data['loyalty_discount']))
        {
            DB::table('loyalty_deductions')->insert([
                'customer_id' => $data['customer_id'],
                'loyalty_id' => $loyalty['id'],
                'order_id' => $data['order_id'],
                'category_id' => $loyalty['category_id'],
                'amount_deducted' => $data['loyalty_discount'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

}
