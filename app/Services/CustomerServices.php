<?php

namespace App\Services;

use App\Helpers\CheckIfdExist;
use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\BusinessSegmentResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerTypeResource;
use App\Models\BusinessSegment;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Traits\ResponseTraits;
use Exception;

class CustomerServices
{
    use ResponseTraits;
    protected $customer;
    protected $customer_type;
    protected $business_segment;

    public function __construct(Customer $customer, CustomerType $customer_type, BusinessSegment $business_segment)
    {
        $this->customer = $customer;
        $this->customer_type = $customer_type;
        $this->business_segment = $business_segment;
    }

    public function index(array $data )
    {
        $customers = $this->customer->with('customerType','businessSegment')->orderByDesc('id');
        if(isset($data['name']))
        {
            $customers = $customers->where('name', 'LIKE', '%' . $data['name'] . '%' );
        }

        if(isset($data['email']))
        {
            $customers = $customers->where('email', 'LIKE', '%' . $data['email'] . '%'  );
        }

        if(isset($data['phone_number']))
        {
            $customers = $customers->where('phone_number', 'LIKE', '%' . $data['phone_number']. '%' );
        }

        return [
                'data' => $customers->paginate(10),
                'message' => 'Customer Successfully Retrieved',
                'statusCode' => 200,
                'status' => true ];
    }

    public function store(array $data)
    {
        $customers = $this->customer->updateOrCreate(
            ['phone_number' => $data['phone_number']],
              [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                 'customer_type_id' => $data['customer_type_id'],
                  'gender' => $data['gender'] ?? null,
                  'address' => $data['address'] ??  null,
                  'business_segment_id' => $data['business_segment_id'],
            ]);
      return  [ 'data' => new CustomerResource($customers),
                'message' => 'Customer Added or Updated Successfully',
                'status' => true,
                'statusCode' => 200
      ];
    }

    public function update(array $data)
    {
        try{
        $customers = $this->customer->firstCustomer($data['id']);
        $customers->update($data);
        return [
            'data' => new CustomerResource($customers),
            'message' => 'Customer Updated Successfully',
            'status' => true,
            'statusCode' => 200,
        ];
      }catch(Exception $exception){
            return $exception;
        }
    }

    public function delete($id)
    {
        CheckingIdHelpers::preventIdDeletion($id, [1]);
        try {
                $customers = $this->customer->firstCustomer($id);
            if (!$customers) {
                return [
                    'data' => null,
                    'status' => false,
                    'statusCode' => 401,
                    'message' => 'Customer Not Found'
                ];
                }
                $customers->delete();
                return [
                    'data' => null,
                    'statusCode' => 200,
                    'status' => true,
                    'message' => 'Customer Deleted Successfully'
                ];
            }catch(Exception $exception){
           return $exception;
        }
    }

    public function storeCustomerType(array $data)
    {
        if(isset($data['id']))
        {
            $customer_type = $this->customer_type->find($data['id']);
            $customer_type->update($data);
            return [
              'data' => new CustomerTypeResource($customer_type),
              'message' => 'Customer Type Updated Successfully',
              'status' => true,
              'statusCode' => 200,
            ];
        }
        $customer_type = $this->customer_type->updateOrCreate($data);
        return [
                'data' => new CustomerTypeResource($customer_type),
                'message' => 'Customer Type Added Successfully',
                'statusCode' => 200,
                'status' => true
        ];
    }

    public function indexCustomerType(array $data): array
    {
        $customer_type = $this->customer_type->query();
        if(isset($data['types']))
        {
            $customer_type->where('types', 'LIKE', '%' . $data['types']. '%' );
        }
        return [
            'data' => CustomerTypeResource::collection($customer_type->get()),
            'message' => 'Customer Type Retrieved Successfully',
            'status' =>  true,
            'statusCode' => 200
        ];
    }

   public function deleteCustomerType($id)
   {
       CheckingIdHelpers::preventIdDeletion($id, [1,2,3,4]);
       try {
           $customer = $this->customer_type->firstCustomerType($id);
           if (!$customer) {
               return [
                   'message' => 'Customer Type Not Found',
                   'status' => true,
                   'statusCode' => 401,
                   'data' => null
               ];
           }
           $customer->delete();
           return [
               'statusCode' => 200,
               'status' => true,
               'data' => null,
               'message' => 'Customer Type Successfully Deleted'
           ];
       }
        catch(Exception $exception){
           return $exception;
        }
   }

   public function show(array $data)
   {
       $customer = $this->customer::where('id', $data['id'])
                                   ->with(['order' => function ($query){
                                            $query->orderByDesc('id');
                                     }])
                                   ->withSum(['transaction' => fn ($query) => $query->where('amount', '!=', null) ], 'amount')
                                    ->firstOrFail();
       if(!$customer)
       {
           return [
               'data' => null,
               'status' => true,
               'statusCode' => 401,
               'message' => 'Customer Detail Not Available'
           ];
       }

       return [
           'data' => new CustomerResource($customer),
           'message'  => 'Customer Data Selected Successfully',
           'status' => true,
           'statusCode' => 200,
       ];
   }

   public function showCustomerType(array $data): array
   {
       $customer_type = $this->customer_type->where('id', $data['id'])->first();
       if(!$customer_type) {
           return [
               'data' => null,
               'message' =>  'Customer Type Not Available',
               'status' => true,
               'statusCode' => 401
           ];
       }
       return [
           'data' => new CustomerTypeResource($customer_type),
           'message' => 'Customer Type Data Selected Successfully',
           'status' => true,
           'statusCode' => 200,
       ];
   }

   public function storeBusinessSegment(array $data)
   {
       if(isset($data['id']))
       {
           $businessSegment = $this->business_segment->find($data['id']);
           $businessSegment->update($data);
           return ['data' => new BusinessSegmentResource($businessSegment),
               'message' => 'Operation Successfully',
               'statusCode' => 200,
               'status' => true
           ];
       }
        $businessSegment = $this->business_segment->updateOrCreate(['name' => $data['name']], ['description' => $data['description'] ?? null]);
       return [
           'data' => new BusinessSegmentResource($businessSegment),
           'message' => 'Operation Successfully',
           'status' =>  true,
           'statusCode' => 200
       ];
   }

   public function deleteBusinessSegment($id)
   {
        CheckingIdHelpers::preventIdDeletion((int)$id, [1,2,3,4,5]);
       try {
           $business_segments = $this->business_segment->firstRecord($id);
           if (!$business_segments) {
               return [
                   'data' => null,
                   'message' => 'Data Not Found',
                   'status' => true,
                   'statusCode' => 401,
               ];
           }
           $business_segments->delete();
           return [
               'data' => null,
               'message' => 'Business Segment Deleted Successfully',
               'status' => true,
               'statusCode' => 200
           ];
       }
       catch (Exception $exception)
       {
           return $exception;
       }
   }

   public function indexBusinessSegment()
   {
        $business_segments = $this->business_segment->orderBy('name', 'asc')->get();
        return [
            'data' => BusinessSegmentResource::collection($business_segments),
            'message' => 'All Business Segment Selected Successfully',
            'status' => true,
            'statusCode' => 200,
        ];
   }

   public function showBusinessSegment(array $data): array
   {
       return [
           'data' => new BusinessSegmentResource($this->business_segment->findOrFail($data['id'])),
           'message' => 'Single Business Segment Selected Successfully',
           'status' => true,
           'statusCode' => 200
       ];
   }

   public function fetchAllCustomerType(): array
   {
       return [
           'data' => CustomerTypeResource::collection($this->customer_type->all()),
           'message' => 'All Customer Type Retrieved',
           'statusCode' => 200,
           'status' => true
       ];
   }

    public function getPriceType(array $data): array
    {
        $price_type = $this->customer_type->query()->select('price_type')->where('id', $data['id'])->first();
        return [
            'data' => $price_type,
            'message' => 'Selected Price Type Retrieved Successfully',
            'status' => true,
            'statusCode' => 200,
        ];
    }
}
