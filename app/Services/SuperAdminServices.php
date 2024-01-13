<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class SuperAdminServices
{
    public function __construct(protected User $user,
                                protected Order $order,
                                protected Transaction $transaction,
                                protected Inventory $inventory,
                                protected Customer $customer,
                                protected Branch $branch){
    }

        public function getAllUsers(array $data): array
        {
            $user = $this->user->where('branch_id', $data['branch_id'])
                               ->paginate(10);
            return [
                'message' => 'User Selected Based On Branch',
                'data' => $user,
                'status' => true,
                'statusCode' => 200
            ];
        }


        public function deleteUser()
        {

        }


        public function dailyReport(array $data): array
        {
            $transaction = $this->transaction->getBranch($data['branch_id'])
                                ->select('id', 'reference_number', 'staff_id', 'order_id', 'customer_id', 'transaction_mode_id', 'transaction_date', 'amount', 'user_id','branch_id', 'created_at')
                                ->with(['user:id,staff_id,first_name,last_name','transactionMode:id,transaction_mode','customer:id,name,email,phone_number', 'orders:id,order_number'])
                                ->orderByDesc('created_at')
                                ->orderByDesc('id');

            if(isset($data['start_date'], $data['end_date']))
            {
                $transaction = $transaction->whereBetween('created_at', [$data['start_date'], $data['end_date']]);
            }

            return [
                    'data' =>  $transaction->paginate(10),
                    'message' =>  'All Transaction Successfully Selected',
                    'statusCode' =>  200,
                    'status' => true
            ];

        }
//    public function cashierReport(array $data)
}
