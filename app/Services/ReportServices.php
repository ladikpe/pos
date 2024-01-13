<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Models\Debtor;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentBreakDown;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ResponseTraits;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportServices
{
    use ResponseTraits;
    protected Transaction $transaction;
    protected User $user;
    protected Debtor $debtor;
    protected Order $order;
    protected PaymentBreakdown $paymentBreakdown;
    protected OrderDetail $orderDetail;

     public function __construct(Transaction  $transaction, User $user, Debtor $debtor, Order $order, PaymentBreakdown $paymentBreakdown, OrderDetail $orderDetail)
    {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->debtor = $debtor;
        $this->order = $order;
        $this->paymentBreakdown = $paymentBreakdown;
        $this->orderDetail = $orderDetail;
    }

      public function dailyReport(array $data): array
     {
         $start_date = $data['start_date'] ?? null;
         $end_date = $data['end_date'] ?? null;
         $customer = $data['customer_id'] ?? null;
         $user = $data['user_id'] ?? null;
         $perPage = $data['limit'] ?? 10;
         if($data['get_all_trashed_transactions'] ?? null)
         {
                     $transaction = $this->transaction::onlyTrashed()
                                     ->where('transactions.branch_id', $data['branch_id'])
                                     ->leftJoin('payment_break_downs', 'transactions.id', '=', 'payment_break_downs.transaction_id')
                                     ->select('transactions.id', 'transactions.reference_number', 'transactions.amount', 'transactions.order_id',
                                                       'transactions.customer_id', 'transactions.transaction_date', 'transactions.user_id', 'transactions.branch_id')
                                     ->selectRaw("SUM(case when payment_break_downs.transaction_mode_id = 1 then payment_break_downs.amount else 0 end) as pos,
                                                                               SUM(case when payment_break_downs.transaction_mode_id = 2 then payment_break_downs.amount else 0 end) as cash,
                                                                               SUM(case when payment_break_downs.transaction_mode_id = 3 then payment_break_downs.amount else 0 end) as transfer,
                                                                               SUM(case when payment_break_downs.transaction_mode_id = 4 then payment_break_downs.amount else 0 end) as pay_later")
                                     ->with(['user:id,staff_id,first_name,last_name', 'customer:id,name,email,phone_number',
                                         'ordersTrashed:id,order_number',
                                         'ordersTrashed.orderDetailTrash:id,order_id,inventory_id,quantity,price,amount',
                                         'ordersTrashed.orderDetailTrash.inventory:id,name'
                                     ])
                                     ->orderByDesc('transactions.created_at')
                                     ->when($start_date && $end_date, static function ($query) use ($start_date, $end_date) {
                                         $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$start_date, $end_date]);
                                     })
                                     ->when($start_date && $end_date && $customer, static function ($query) use ($start_date, $end_date, $customer) {
                                         $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$start_date, $end_date])
                                             ->where('transactions.customer_id', $customer);
                                     })
                                     ->when($start_date && $end_date && $user, static function ($query) use ($start_date, $end_date, $user) {
                                         $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$start_date, $end_date])
                                             ->where('transactions.user_id', $user);
                                     })
                                     ->when($user, static function ($query) use ($user) {
                                         $query->where('transactions.user_id', $user);
                                     })
                                     ->when($customer, static function ($query) use ($customer) {
                                         $query->where('transactions.customer_id', $customer);
                                     })
                                     ->groupBy(DB::raw('transactions.id'));
         }else {
             $transaction = $this->transaction->where('transactions.branch_id', $data['branch_id'])
                 ->leftJoin('payment_break_downs', 'transactions.id', '=', 'payment_break_downs.transaction_id')
                 ->select('transactions.id', 'transactions.reference_number', 'transactions.amount', 'transactions.order_id', 'transactions.customer_id', 'transactions.transaction_date', 'transactions.user_id', 'transactions.branch_id')
                 ->selectRaw("SUM(case when payment_break_downs.transaction_mode_id = 1 then payment_break_downs.amount else 0 end) as pos,
                                                           SUM(case when payment_break_downs.transaction_mode_id = 2 then payment_break_downs.amount else 0 end) as cash,
                                                           SUM(case when payment_break_downs.transaction_mode_id = 3 then payment_break_downs.amount else 0 end) as transfer,
                                                           SUM(case when payment_break_downs.transaction_mode_id = 4 then payment_break_downs.amount else 0 end) as pay_later")
                 ->with(['user:id,staff_id,first_name,last_name', 'customer:id,name,email,phone_number', 'orders:id,order_number', 'orders.orderDetail:id,order_id,inventory_id,quantity,price,amount', 'orders.orderDetail.inventory:id,name'])
                 ->orderByDesc('transactions.created_at')
                 ->when($start_date && $end_date, static function ($query) use ($start_date, $end_date) {
                     $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$start_date, $end_date]);
                 })
                 ->when($start_date && $end_date && $customer, static function ($query) use ($start_date, $end_date, $customer) {
                     $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$start_date, $end_date])
                         ->where('transactions.customer_id', $customer);
                 })
                 ->when($start_date && $end_date && $user, static function ($query) use ($start_date, $end_date, $user) {
                     $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$start_date, $end_date])
                         ->where('transactions.user_id', $user);
                 })
                 ->when($user, static function ($query) use ($user) {
                     $query->where('transactions.user_id', $user);
                 })
                 ->when($customer, static function ($query) use ($customer) {
                     $query->where('transactions.customer_id', $customer);
                 })
                 ->groupBy(DB::raw('transactions.id'));

         }
        if(isset($data['export_to_excel']))
        {
            return [
                'data' => $transaction->get()->toArray(),
                'header' => ["ID", "ORDER NUMBER", "CUSTOMER", "AMOUNT", "CASH", "POS", "TRANSFER", "CREDIT SALES", "DATE/TIME", "CASHIER"]
            ];
        }

         return [
             'data' => $transaction->paginate($perPage),
             'message' => 'All Transaction Successfully Selected',
             'status' =>   true,
             'statusCode' => 200
         ];
    }

      public function transactionReport(array $data): array
     {
         $perPage = $data['limit'] ?? 10;
         $start_date = $data['start_date'] ?? null;
         $end_date = $data['end_date'] ?? null;
         $transaction = $this->paymentBreakdown
                                    ->selectRaw("created_at as TRANSACTION_DATE, SUM(amount) as TOTAL_AMOUNT")
                                    ->selectRaw("SUM(case when transaction_mode_id = 1 then amount else 0 end) as POS")
                                    ->selectRaw("SUM(case when transaction_mode_id = 2 then amount else 0 end) as CASH")
                                    ->selectRaw("SUM(case when transaction_mode_id = 3 then amount else 0 end) as BANK_TRANSFER")
                                    ->selectRaw("SUM(case when transaction_mode_id = 4 then amount else 0 end) as PAY_LATER")
//                                    ->selectRaw("SUM(case when transaction_mode_id > 4 then amount else 0 end) as OTHERS")
                                 ->when($start_date && $end_date, static function($query) use ($start_date, $end_date) {
                                     $query->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                                 })
                                 ->orderByDesc('created_at')
                                 ->groupBy(DB::raw('Date(created_at)'));


         if(isset($data['export_to_excel']))
         {
             return [
                 'data' => $transaction->get()->toArray(),
                 'header' => [ "Transaction DATE" ,"TOTAL AMOUNT", "POS", "CASH", "TRANSFER", "CREDIT SALES" ]
             ];
         }



        return [
                'data' => $transaction->paginate($perPage),
                'message' => 'Fetch All User Transactions',
                'status' => true,
                'statusCode' => 200,
        ];

    }

      public function cashierReport(array $data): array
       {
            $perPage = $data['limit'] ?? 10;
            $start_date = $data['start_date']?? null;
            $end_date = $data['end_date'] ?? null;
            $user = $data['user_id'] ?? null;
            $transaction = $this->transaction
                                             ->where('transactions.branch_id', $data['branch_id'])
                                             ->leftJoin('payment_break_downs', 'transactions.id', '=', 'payment_break_downs.transaction_id')
                                              ->select( 'transactions.staff_id', 'transactions.transaction_date', 'transactions.user_id', 'transactions.id', 'transactions.amount')
                                              ->selectRaw("SUM(case when payment_break_downs.transaction_mode_id = 1 then payment_break_downs.amount else 0 end) as pos,
                                                           SUM(case when payment_break_downs.transaction_mode_id = 2 then payment_break_downs.amount else 0 end) as cash,
                                                           SUM(case when payment_break_downs.transaction_mode_id = 3 then payment_break_downs.amount else 0 end) as transfer,
                                                           SUM(case when payment_break_downs.transaction_mode_id = 4 then payment_break_downs.amount else 0 end) as pay_later,
                                                           SUM(case when payment_break_downs.transaction_mode_id > 0 then payment_break_downs.amount else 0 end) as total_amount")
                                                ->with(['user:id,staff_id,first_name,last_name'])
                                                ->orderByDesc('transactions.transaction_date')
                                                ->groupBy('transactions.user_id')
                                                ->groupBy(DB::raw("DATE_FORMAT(transactions.transaction_date, '%Y-%m-%d')"))
                                               ->when($start_date && $end_date && $user, static function ($transaction) use ($start_date, $end_date, $user) {
                                                       $transaction->whereBetween(DB::raw('DATE(transaction_date)'), [$start_date, $end_date])
                                                                   ->where('user_id', $user);
                                               })
                                                ->when($start_date && $end_date, static function ($transaction) use ($start_date, $end_date) {
                                                    $transaction->whereBetween(DB::raw('DATE(transaction_date)'), [$start_date, $end_date]);
                                                    })
                                                ->when($user, static function ($transaction) use ($user) {
                                                    $transaction->where('user_id', $user);
                                                });
           if(isset($data['export_to_excel']))
           {
               return [
                   'data' => $transaction->get(),
                   'header' => [ "STAFF ID" ,"CASHIER NAME", "TOTAL AMOUNT", "CASH", "POS", "TRANSFER", "CREDIT SALES", "DATE" ]
               ];
           }
            return [
                    'data' => $transaction->paginate($perPage),
                    'message' => 'Cashier Sales Record Retrieved Successfully',
                    'statusCode' => 200,
                    'status' => true
            ];
        }

      public function debtorReport(array $data): array
      {
           $perPage = $data['limit'] ?? 10;
           $customer_debtor_record = $this->debtor
                                           ->where('branch_id', $data['branch_id'])
                                            ->select('id', 'debtor_number', 'order_number', 'total_amount', 'customer_id', 'discount', 'payment_duration' , 'branch_id' ,'user_id', 'created_at')
                                            ->with(['employee:id,staff_id,first_name,last_name'])
                                            ->with(['customer:id,name,email,phone_number,gender'])
                                            ->orderByDesc('id');

            if(isset($data['start_date'], $data['end_date'], $data['customer_id']))
            {
                $customer_debtor_record = $customer_debtor_record->where('customer_id', $data['customer_id'])
                                                                    ->whereBetween(DB::raw('DATE(created_at)'), [$data['start_date'], $data['end_date']]);

            }

            elseif (isset($data['customer_id']))
            {
                $customer_debtor_record = $customer_debtor_record->where('customer_id', $data['customer_id']);
            }

            elseif(isset($data['start_date'], $data['end_date']))
            {
                $customer_debtor_record = $customer_debtor_record->whereBetween(DB::raw('DATE(created_at)'), [$data['start_date'], $data['end_date']]);
            }

          if(isset($data['export_to_excel']))
          {
              return [
                  'data' => $customer_debtor_record->get(),
                  'header' => [ "ORDER NUMBER" ,"DEBTOR NUMBER", "TOTAL AMOUNT", "DATE", "CASHIER", "CUSTOMER" ]
              ];
          }

            return [
                'message' => 'Debtor Record Successfully Retrieved',
                'data' => $customer_debtor_record->paginate($perPage),
                'status' => true,
                'statusCode' => 200,
              ];
    }

      public function fetchAllUser(array $data): array
        {
            $data['limit'] = $data['limit'] ?? 0;
            $user = CheckingIdHelpers::checkAuthUserBranch($this->user);
            $user = $user->select('id', 'first_name', 'last_name', 'branch_id');
            if(isset($data['search'])){
                $search = $data['search'];
              $user = $user->where(function($query) use ($search){
                              $query->OrWhere('id',  'like', '%'.$search.'%');
                              $query->OrWhere('first_name',  'like', '%'.$search.'%');
                              $query->OrWhere('last_name',  'like', '%'.$search.'%');
                              $query->orWhere(DB::raw("CONCAT(`first_name`,' ',`last_name`)"), 'like', '%' . $search . '%');
                          });
            }
             $user = $user->paginate($data['limit']);

            return [
                'status' => true,
                'statusCode' => 200,
                'message' => 'Fetch All User',
                'data' => $user,
            ];
        }

      public function cashierDailyReport(array $data): array
        {
            $perPage = $data['limit'] ?? 10;
            $cashier_daily_report = $this->transaction->where('branch_id', $data['branch_id'])
                                                    ->select('id', 'reference_number', 'transaction_date', 'user_id','amount','order_id', 'branch_id' )
                                                    ->with(['user:id,staff_id,first_name,last_name'])
                                                    ->with('orders:id,order_number,loyalty_discount')
                                                    ->orderByDesc('transaction_date');

            if(isset($data['start_date'], $data['end_date'], $data['user_id']))
            {
                $cashier_daily_report = $cashier_daily_report->whereBetween(DB::raw('DATE(transaction_date)'), [$data['start_date'], $data['end_date']])
                                                            ->where('user_id', $data['user_id']);
            }

            else if(isset($data['start_date'], $data['end_date']))
            {
                $cashier_daily_report = $cashier_daily_report->whereBetween(DB::raw('DATE(transaction_date)'), [$data['start_date'], $data['end_date']]);
            }

            else if(isset($data['user_id']))
            {
                $cashier_daily_report = $cashier_daily_report->where('user_id', $data['user_id']);
            }

            if(isset($data['export_to_excel']))
            {
                return [
                    'data' => $cashier_daily_report->get(),
                    'header' => [ "Order Number","Reference Number","Total Amount","Date","Cashier" ]
                ];
            }

            return [
                'status' => true,
                'message' => 'Cashier Daily Report Successfully Retrieved',
                'statusCode' => 200,
                'data' => $cashier_daily_report->paginate($perPage)
            ];

        }

      public function orderDetailReport(array $data):array
        {
            $perPage = $data['limit'] ?? 10;
            $customer = $data['customer_id'] ?? null;
            $start_date = $data['start_date'] ?? null;
            $end_date = $data['end_date'] ?? null;

            $orderDetailsReport = CheckingIdHelpers::checkAuthUserBranch($this->order)
                                                ->select('id', 'order_number', 'customer_id', 'total' , 'order_date')
                                                ->with('customer:id,name')
                                                ->with(['orderDetail:order_id,inventory_id,quantity,price,amount,branch_id', 'orderDetail.inventory:id,name'])
                                                ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                                                    $query->whereBetween(DB::raw('DATE(order_date)'), [$start_date, $end_date]);
                                                })

                                                ->when($start_date && $end_date && $customer, function ($query) use ($start_date, $end_date, $customer) {
                                                    $query->whereBetween(DB::raw('DATE(order_date)'), [$start_date, $end_date])
                                                            ->where('customer_id', $customer);
                                                })
                                                ->when($customer, function ($query) use ($data) {
                                                       $query->where('customer_id', $data['customer_id']);
                                                })
                                                ->paginate($perPage);
            return [
                'status' => true,
                'message' => 'Cashier Daily Report Successfully Retrieved',
                'statusCode' => 200,
                'data' => $orderDetailsReport
            ];

        }

        public function accessoriesDailyReport($data)
        {
             $start_date = $data['start_date'] ?? null;
             $end_date = $data['end_date'] ?? null;
             $customer = $data['customer_id'] ?? null;
             $user = $data['user_id'] ?? null;
             $perPage = $data['limit'] ?? 10;

             $accessoriesDailyReport = $this->orderDetail->where('branch_id', $data['branch_id'])
                             ->select('id','order_id','quantity','price','inventory_id','amount','created_at')
                            ->with('inventory:id,category_id,name', 'inventory.categories:id,name')
                            ->with(['order:id,order_number,user_id,customer_id',
                                'order.user:id,staff_id,first_name,last_name'])
                             ->whereHas('inventory', function ($q) {
                                         $q->where('category_id', '>=', 2);
                              })
                             ->orderByDesc('created_at');

            if(isset($data['start_date'], $data['end_date'], $data['user_id']))
            {
                $accessoriesDailyReport = $accessoriesDailyReport->whereBetween(DB::raw('DATE(created_at)'), [$data['start_date'], $data['end_date']])->where('user_id', $data['user_id']);
            }

            else if(isset($data['start_date'], $data['end_date']))
            {
                $accessoriesDailyReport = $accessoriesDailyReport->whereBetween(DB::raw('DATE(created_at)'), [$data['start_date'], $data['end_date']]);
            }

            else if(isset($data['user_id']))
            {
                $accessoriesDailyReport = $accessoriesDailyReport
                             ->whereHas('order.user', function ($q) use ($user){
                                         $q->where('user_id', $user);
                              });
            }

            else if(isset($data['customer_id']))
            {
                $accessoriesDailyReport = $accessoriesDailyReport
                             ->whereHas('order.customer', function ($q) use ($user){
                                         $q->where('customer_id', $user);
                              });
            }


            if(isset($data['export_to_excel']))
            {
                return [
                    'data' => $accessoriesDailyReport->get(),
                    'header' => ["Order number", "INVENTORY TYPE", "INVENTORY NAME", "Cashier", "Quantity", "Price", "Amount",  "Date"]
                ];
            }

            return [
                'status' => true,
                'message' => 'Accessories Daily Report Successfully Retrieved',
                'statusCode' => 200,
                'data' => $accessoriesDailyReport->paginate($perPage)
            ];


        }
}
