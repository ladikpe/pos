<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Models\BusinessSegment;
use App\Models\InventoryReStockHistory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\TransactionMode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportBetaServices
{

    public function __construct(protected Transaction $transaction,
                                protected TransactionMode $transactionMode,
                                protected BusinessSegment $businessSegment,
                                protected Order $order,
                                protected OrderDetail $orderDetail,
                                protected InventoryReStockHistory $inventoryReStockHistory){

    }

    public function relationshipFilter($filterValue, $item): \Closure
    {
        return  static function ($query) use ($filterValue, $item){
            $query->where($filterValue, $item);
        };
    }
    public function salesTypeReport(array $data): array
    {
        $perPage = $data['limit'] ?? 10;
        $start_date = $data['start_date'] ?? null;
        $end_date = $data['end_date'] ?? null;
        $sales_type = $data['transaction_mode_id'] ?? null;

        $transaction =  $this->transaction
            ->where('branch_id', $data['branch_id'])
            ->select('id', 'reference_number', 'order_id', 'customer_id', 'transaction_mode_id', 'transaction_date', 'amount', 'user_id','branch_id')
            ->with(['user:id,staff_id,first_name,last_name'])
            ->with('orders:id,order_number,loyalty_discount')
            ->with('customer:id,name')
            ->with('paymentBreakDown')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->when($sales_type, function ($query) use ($sales_type) {
                $query->where('transaction_mode_id', $sales_type);
            })
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('transaction_date', [$start_date, $end_date]);
            })
            ->when($start_date && $end_date && $sales_type, function ($query) use ($sales_type, $start_date, $end_date) {
                $query->where('transaction_mode_id',  $sales_type)
                    ->whereBetween('transaction_date', [$start_date, $end_date]);
            });

        if(isset($data['export_to_excel']))
        {
            return [
                'data' => $transaction->get(),
                'header' => [ "Payment Mode","Total Amount","Date","Order Number"]
            ];
        }

        return [
            'data' => $transaction->paginate($perPage),
            'message' => 'All Transaction Successfully Selected',
            'status' => true,
            'statusCode' => 200,
        ];
    }
    public function businessSegmentReport($data = []): array
    {
        $perPage = $data['limit'] ?? 10;
        $start_date = $data['start_date'] ?? null;
        $end_date = $data['end_date'] ?? null;
        $business_segment = $data['business_segment_id'] ?? null;
        $filter =  $this->relationshipFilter('business_segment_id',  $business_segment);

        $transaction = $this->transaction
            ->where('branch_id', $data['branch_id'])
            ->select('id', 'reference_number', 'order_id', 'customer_id', 'transaction_mode_id', 'transaction_date', 'amount', 'user_id','branch_id')
            ->with(['user:id,staff_id,first_name,last_name'])
            ->with('orders:id,order_number,loyalty_discount')
            ->with(['customer:id,name,business_segment_id', 'customer.businessSegment:id,name'])
            ->with('transactionMode:id,transaction_mode')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->when($start_date && $end_date && $business_segment, function ($query) use ($start_date, $end_date, $filter) {
                $query->whereHas( 'customer', $filter)
                    ->whereBetween('transaction_date', [$start_date, $end_date]);
            })
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('transaction_date', [$start_date, $end_date]);
            })
            ->when($business_segment, function ($query) use ($filter) {
                $query->whereHas( 'customer', $filter);
            });

        if(isset($data['export_to_excel']))
        {
            return [
                'data' => $transaction->get(),
                'header' => [ "BUSINESS SEGMENT", "TOTAL AMOUNT", "DATE", "ORDER NUMBER"]
            ];
        }

        return [
            'data' => $transaction->paginate($perPage),
            'message' => 'All Transaction And Business Segment Record Successfully Selected',
            'status' => true,
            'statusCode' => 200,
        ];
    }
    public function customerTypeReport(array $data) :array
    {
        $perPage = $data['limit'] ?? 10;
        $month = $data['month'] ?? null;
        $year = $data['year'] ?? null;
        $customer_type = $data['customer_type_id'] ?? null;
        $customer = $data['customer_id'] ?? null;
        $filter = $this->relationshipFilter('customer_type_id', $customer_type);
        $filter_customer = $this->relationshipFilter('id', $customer);
        $transaction = $this->order
                                    ->select('id', 'branch_id', 'customer_id', 'order_number', 'total', 'order_date', 'loyalty_discount')
                                    ->where('branch_id', $data['branch_id'])
                                    ->withSum('orderDetail', 'amount')
                                    ->withSum('orderDetail', 'quantity')
                                    ->with([
                                        'customer:id,name,phone_number,customer_type_id',
                                        'customer.customerType:id,types'
                                    ])
                                    ->when($customer,  function ($item) use ($customer) {
                                        $item->where('customer_id', $customer);
                                    })
                                    ->orderByDesc('created_at')
                                    ->groupBy('customer_id')

                                    ->when($month && $year && $customer_type, function ($query) use ($month, $filter, $year) {
                                        $query->whereHas( 'customer', $filter)
                                            ->whereMonth('created_at', $month)
                                            ->whereYear('created_at', $year);
                                    })
                                    ->when($month &&  $year && $customer_type && $customer, function ($query) use ($month, $filter, $filter_customer, $year) {
                                        $query->whereHas( 'customer', $filter)
                                            ->whereHas( 'customer', $filter)
                                            ->whereHas( 'customer', $filter_customer)
                                            ->whereMonth('created_at', $month)
                                           ->whereYear('created_at', $year);
                                    })
                                    ->when($month && $year && $customer, function ($query) use ($month, $filter_customer, $year) {
                                        $query->whereHas( 'customer', $filter_customer)
                                            ->whereMonth('created_at', $month)
                                            ->whereYear('created_at', $year);
                                    })
                                    ->when($month && $year , function ($query) use ($month, $year) {
                                        $query->whereMonth('created_at', [$month])
                                            ->whereYear('created_at', $year);
                                    })
                                    ->when($customer_type, function ($query) use ($filter) {
                                        $query->whereHas( 'customer', $filter);
                                    })
                                    ->when($customer, function ($query) use ($filter_customer) {
                                        $query->whereHas( 'customer', $filter_customer);
                                    })->paginate($perPage)
                                      ->through(function ($item)  {
                                          $average = $item['order_detail_sum_quantity'] != 0 ? ($item['order_detail_sum_amount'] / $item['order_detail_sum_quantity']) : 0;
                                          return [
                                              'customer_data' => $item,
                                              'average' =>  $average,
                                              'transaction_month' => Carbon::parse($item['order_date'])->format('Y-M')
                                          ];
                                      });

        if(isset($data['export_to_excel']))
        {
            return [
                'data' => $transaction,
                'header' => ["CUSTOMER TYPE", "CUSTOMER NAME",  "QUANTITY", "TOTAL AMOUNT", "AVERAGE", "DATE"]
            ];
        }

        return [
            'data' => $transaction,
            'message' => 'All Transaction And Business Segment Record Successfully Selected',
            'status' => true,
            'statusCode' => 200,
        ];
    }
    public function InventoryOrProductTypeReport(array $data) : array
    {
        $perPage = $data['limit'] ?? 10;
        $start_date = $data['start_date'] ?? null;
        $end_date = $data['end_date'] ?? null;
        $category = $data['category_id'] ?? null;
        $filter =  $this->relationshipFilter('category_id',  $category);

        $inventoryReport = $this->orderDetail
            ->select('order_id','inventory_id','quantity','price','created_at')
            ->with(['inventory:id,name,category_id',
                'inventory.categories:id,name',
                'order:id,order_number,customer_id,total,order_date,user_id',
                'order.customer:id,name', 'order.employee:id,first_name,last_name'])
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('created_at', [$start_date, $end_date]);
            })
            ->when($start_date && $end_date && $category, function ($query) use ($start_date, $end_date, $filter) {
                $query->whereBetween('created_at', [$start_date, $end_date])
                    ->whereHas('inventory', $filter);
            })
            ->when($category, function ($query) use ($filter) {
                $query->whereHas('inventory', $filter);
            });

        $inventoryReport = $inventoryReport->paginate($perPage)->through( function ($items) {

            return [
                "order_id" => $items['order_id'],
                'order' => $items['order'],
                "inventory_id" =>  $items['inventory_id'],
                "quantity" => $items['quantity'],
                "price" => $items['price'],
                'total_lpg_sold' => (int)$items['quantity'] * (int)$items['price'],
                "created_at" => $items['created_at'],
                "inventory" => $items['inventory'],
            ];
        });

        if(isset($data['export_to_excel']))
        {
            return [
                'data' => $inventoryReport,
                'header' => [ "DATE/TIME", "ORDER NUMBER", "CASHIER",  "PRODUCT TYPE", "CYLINDER TYPE", "QUANTITY", "TOTAL_LPG_SOLD", "PRICE",  "TOTAL AMOUNT" ]
            ];
        }

        return [
            'status' => true,
            'message' => 'Cashier Daily Report Successfully Retrieved',
            'statusCode' => 200,
            'data' => $inventoryReport
        ];
    }
    public function inventoryHistoryReport(array $data): array
    {
        $perPage = $data['limit'] ?? 10;
        $start_date = $data['start_date'] ?? null;
        $end_date = $data['end_date'] ?? null;
        $inventory = $data['inventory_id'] ?? null;
        $inventoryHistory = $this->inventoryReStockHistory->inventoryHistory()
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('created_at', [$start_date, $end_date]);
            })
            ->when($start_date && $end_date && $inventory, function ($query) use ($start_date, $end_date, $inventory) {
                $query->whereBetween('created_at', [$start_date, $end_date])
                    ->where('inventory_id', $inventory);
            })
            ->when($inventory, function ($query) use ($inventory) {
                $query->where('inventory_id', $inventory);
            });


        if(isset($data['export_to_excel']))
        {
            return [
                'data' => $inventoryHistory->get(),
                'header' => [ "NAME", "QUANTITY", "PRICE", "DATE", "TIME"]
            ];
        }

        return [
            'status' => true,
            'message' => 'Cashier Daily Report Successfully Retrieved',
            'statusCode' => 200,
            'data' => $inventoryHistory->paginate($perPage)
        ];
    }
}
