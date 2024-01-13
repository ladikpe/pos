<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\PaymentDurationResource;
use App\Models\PaymentDuration;
use App\Traits\ResponseTraits;

class PaymentDurationServices
{
    use ResponseTraits;
    protected $payment_duration;
    public function __construct(PaymentDuration $payment_duration)
    {
        $this->payment_duration = $payment_duration;
    }


    public function index(): array
    {
        return [
            'data' => PaymentDurationResource::collection($this->payment_duration->all()->sortByDesc('id')),
            'message' => 'Fetching All Payment Duration',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function store(array $data): array
    {
        $payment_duration = $this->payment_duration->updateOrCreate(['duration' => $data['duration'].' month']);
        return [
            'data' => new PaymentDurationResource($payment_duration),
            'message' => 'Payment Duration Added Successfully',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function delete($id)
    {
         CheckingIdHelpers::preventIdDeletion((int)$id, [1,2,3,4,5,6,7,8,9,10]);
        try{
            $payment_duration = $this->payment_duration::findorFail($id);
            $payment_duration->delete();
            return [
                'data' => null,
                'message' => 'Payment Duration was deleted successfully',
                'statusCode' => 200,
                'status' => true
            ];
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }


    public function show(array $data): array
    {
        $id = $data['id'];
        $payment_duration = $this->payment_duration->findOrFail($id);
        return  [
           'data' => new PaymentDurationResource($payment_duration),
           'message' => ' Single Payment Duration Selected',
           'status' => true,
           'statusCode' => 200
        ];
    }

}
