<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Helpers\GenerateRandomNumber;
use App\Http\Resources\OrderResource;
use App\Models\BusinessSegment;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Debtor;
use App\Models\DebtorDetail;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentBreakDown;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ResponseTraits;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use function auth;

class OrderServices
{

    use ResponseTraits;
    public const OnCredit = 'on-credit';
    public const PhoneNumber = '070-1234-1234';
    public const Name = 'walk-in-customer';
    public const Email =  'customer@ugl-pos.com';
    public const Address = 'Lagos - Nigeria';
    public const customerTypeRetailId = 1;
    public const customerTypeDealerId = 2;
    public const customerTypeStaffId = 3;
    public const customerTypeCrsId = 4;
    public const customerId = 1;


    protected $order;
    protected $order_details;
    protected $transaction;
    protected $customer;
    protected $inventory;
    protected $debtor;
    protected $debtor_details;
    protected $business_segment;
    protected $user;
    protected PaymentBreakDownServices $paymentBreakDownServices;
    protected $payment_break_down;
    protected $loyaltyServices;
    protected $category;

    public function __construct(User $user,
                                BusinessSegment $business_segment,
                                Order $order,
                                OrderDetail $order_details,
                                Transaction $transaction,
                                Customer $customer,
                                PaymentBreakDown $payment_break_down,
                                Inventory $inventory,
                                Debtor $debtor,
                                DebtorDetail $debtor_details,
                                PaymentBreakDownServices $paymentBreakDownServices,
                                LoyaltyServices $loyaltyServices,
                                Category $category)
    {
        $this->user = $user;
        $this->order = $order;
        $this->order_details = $order_details;
        $this->transaction = $transaction;
        $this->customer = $customer;
        $this->inventory = $inventory;
        $this->debtor = $debtor;
        $this->debtor_details = $debtor_details;
        $this->business_segment = $business_segment;
        $this->payment_break_down = $payment_break_down;
        $this->paymentBreakDownServices = $paymentBreakDownServices;
        $this->loyaltyServices = $loyaltyServices;
        $this->category = $category;
    }
    /**
     * @throws Exception
     */
    public function store(array $data)
    {
        $StockService = new StockServices();
        $StockService->checkStockLevels($data['items']);
        try {
            DB::beginTransaction();
            $authUser = auth()->user();
            $order = $this->order->create([ 'order_number' => (new GenerateRandomNumber)->uniqueRandomNumber( 'UGL-ODR-',10),
                                                'order_date' => Carbon::now()->toDateTimeString(),
                                                'staff_id' => $authUser->staff_id,
                                                'user_id' => $authUser->id,
                                                'payment_type' => $data['payment_type'] ?? self::OnCredit,
                                                'branch_id' => $authUser->branch_id,
                                            ] + $data
                                         );
            foreach ($data['items'] as $key => $item)
            {
                $amount = $item['quantity'] * $item['price'];
                $this->order_details->create([  'order_id' => $order->id,
                                                'inventory_id' => $item['id'],
                                                'quantity' => $item['quantity'],
                                                'price' => $item['price'],
                                                'amount' => $amount,
                                                'branch_id' => $authUser->branch_id,
                                        ]);

                // add amount gained to loyalty_users table
                $inventory = $this->inventory::getFirstRecord($item['id']);
                $category =  $this->category::getCategory($inventory['category_id']);

                if($category['id'] === $this->category::REFILL){
                    $loyaltyData = [
                        'category_id' => $inventory['category_id'],
                        'order_id' => $order->id,
                        'customer_id' => $data['customer_id'],
                        'quantity' => $item['quantity'],
                        'loyalty_discount' => $data['loyalty_discount'] ?? null
                    ];
                    $this->loyaltyServices->addLoyaltyAmount($loyaltyData);
                }
            }

           $transaction = $this->transaction->create([
                                             'reference_number' => (new GenerateRandomNumber)->uniqueRandomNumber( 'UGL-TRN-',10),
                                            'staff_id' => $authUser->staff_id,
                                            'user_id' => $authUser->id,
                                            'order_id' => $order->id,
                                            'customer_id' => $data['customer_id'],
                                            'transaction_mode_id' => $data['payment_break_down'][0]['transaction_mode_id'],
                                            'transaction_date' => Carbon::now(),
                                            'description' => "Payment For Inventory Items With Order Number: , " . $order['order_number'],
                                            'amount' => $data['total'], 'branch_id' => $authUser->branch_id,
                                        ]);


            $this->paymentBreakDownServices->storePaymentBreakDown(['transaction_id' => $transaction->id,
                                                                    'reference_number' => $transaction->reference_number,
                                                                    'payment_break_down' => $data['payment_break_down'],
                                                                    'branch_id' => $authUser->branch_id,
                                                                ]);

            DB::commit();
            return [
                'data' => new OrderResource($order),
                'message' => 'Order Created Successfully',
                'statusCode' => 200,
                'status' => true
            ];
        } catch (Exception $exception) {
            DB::rollback();
            return $exception;
        }
    }

    public function index(array $data): array
    {
        $orders = CheckingIdHelpers::checkAuthUserBranch($this->order);
        $orders = $orders->with('customer:id,name', 'employee:id,first_name,last_name',
                                                        'orderDetail:id,order_id,inventory_id,price,quantity,amount',
                                                        'orderDetail.inventory:id,name')->orderByDesc('id');
        if (isset($data['order_number'])) {
            $orders->where('order_number', 'like', '%' . $data['order_number'] . '%');
        }
        return [
            'data' => $orders->paginate(10),
            'message' => 'All Orders Selected Successfully',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function show($id): array
    {
        $orders = $this->order->getOrderById($id);
        return [
                 'data' => new OrderResource($orders),
                 'message' => 'Fetch Single Order',
                 'statusCode' => 200,
                 'status' => true
        ];
    }

    public function showTrashed($id): array
    {
        $orders  = $this->order->getTrashedOrderById($id);
        return [
            'data' => $orders,
            'message' => 'Fetch Single Order',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function fetchAllCustomer(array $data): array
    {
        $perPage = $data['limit'] ?? 10;
        $customer = $this->customer->with('customerType');
        if (isset($data['search'])) {
            $search = $data['search'];
            $customer = $customer->where('name', 'LIKE', '%' . $search . '%');
        }
        return [
            'data' => $customer->paginate($perPage),
            'message' => 'Fetch All Customer',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function fetchAllInventory(array $data): array
    {
        $search = $data['search'] ?? null;
        $perPage = $data['limit'] ?? 10;
        $customer = $this->customer->firstRecord($data['customer_id'] ?? self::customerId);
        $inventories = $this->inventory->getInventoryWithCategory($search, $perPage);

        $customerType = $customer->customerType->types;
        $customerTypeId = $customer->customerType->id;

        if(($customerType  === CustomerType::Retail) || ($customerTypeId === self::customerTypeRetailId)){
          $inventories = $this->inventoryMap($inventories,'price');
        }

         if(($customerType  === CustomerType::Dealer) || ($customerTypeId === self::customerTypeDealerId)){
            $inventories = $this->inventoryMap($inventories, 'dealer_price');
        }

         if(($customerType  === CustomerType::Staff) || ($customerTypeId === self::customerTypeStaffId)){
            $inventories = $this->inventoryMap($inventories,'staff_price');
        }

         if(($customerType  === CustomerType::Crs) || ($customerTypeId === self::customerTypeCrsId)){
            $inventories = $this->inventoryMap($inventories,'crs_price');
        }

        return [
                'data' => $inventories,
                'message' => 'Fetch All Inventory',
                'statusCode' => 200,
                'status' => true
        ];
    }

    public function inventoryMap($inventories,$value)
    {
         return   $inventories->through(static function ($items) use ($value){
                return [
                    'id' => $items->id,
                    'name' => $items->name,
                    'price' =>  $items[$value] ?? $items['price'],
                    'quantity' => $items->quantity,
                    'unit_of_measurement' => $items->unit_of_measurement,
                    'category' => $items->categories
                ];
            });
    }

    public function fetchAllOrders(array $data): array
    {
        $getALlOrder = CheckingIdHelpers::checkAuthUserBranch($this->order);
        $getALlOrder = $getALlOrder->select('id', 'order_number', 'branch_id');
        if (isset($data['search'])) {
            $search = $data['search'];
            $getALlOrder->where(function ($query) use ($search) {
                $query->orWhere('id', 'like', '%' . $search . '%');
                $query->orWhere('order_number', 'like', '%' . $search . '%');
            })->orderBy('id', 'desc');
        }
        $limit = $data['limit'] ?? 50;
        $getALlOrder = $getALlOrder->orderByDesc('id');
        return [
            'data' => $getALlOrder->paginate($limit),
            'message' => 'All Retrieved Successfully',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function getCashierByOrderId($data): array
    {
        $orderId = $data['order_id'];
        $user = $this->user->getUserWithBranch($orderId);
        return [
            'data' => $user,
            'message' => 'Employee Data Fetched SuccessFully',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function deleteOrder($id)
    {
//        DB::beginTransaction();
        try{
            $order = $this->order->where('id', $id)->first();
            if(!$order)
            {
                return [
                    'data' => null,
                    'status' =>  true,
                    'statusCode' => 401,
                    'message' => 'Order Not Found'
                ];
            }
            $transactions = $this->transaction->where('order_id', $order->id)
                                                ->with('paymentBreakDown')
                                                ->first();

            $order->delete();
            $order->orderDetail()->delete();
            $order->transaction()->delete();
            return [
                'data' => null,
                'message' => 'Order deleted successfully',
                'status' => true,
                'statusCode' =>  200
            ];
        }
        catch(Exception $exception){
            return $exception;
        }
//        DB::rollback();

    }

    public function getOrdersWithTrash(): array
    {
        $trashedOrder = $this->order->getOrdersWithTrash();
        return [
            'data' => $trashedOrder,
            'message' => 'Fetch All Trashed Orders',
            'status' => true,
            'statusCode' => 200
        ];
    }

}
