<?php
namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Models\Category;
use App\Traits\ResponseTraits;
use Illuminate\Support\Facades\Auth;

class  SettingServices{

    use ResponseTraits;
    protected $setting;
    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function show()
    {
        $setting = CheckingIdHelpers::checkAuthUserBranch($this->setting)->first();
        return ['data' => new SettingResource($setting),
            'message' => 'Fetch All Settings Successfully',
            'statusCode' => 200,
            'status' => true
        ];
    }

    public function store(array $data)
    {
        try{
            $data['invoice_description'] = $data['invoice_description'] ?? 'UGL POS INVOICE';
            $branchId =  auth()->user()->branch_id;
            $setting = $this->setting->where('branch_id', $branchId);
            if($setting->exists()){
                $settings = $setting->first();
                $settings->update(array_merge($data, ['branch_id' => $branchId],
                    ['invoice_description' => $data['invoice_description']]
                ));
            }else{
                $settings = $this->setting->create(array_merge($data, ['branch_id' => $branchId],
                    ['invoice_description' => $data['invoice_description']]
                ));
            }
            return [
                'data' => new SettingResource($settings),
                'statusCode' => 200,
                'status' => true,
                'message' => 'Setting Added or Updated successfully',
            ];
        }
        catch(\Exception $exception) {
            return $exception->getMessage();
        }
    }


    public function addToGas(array $data)
    {
        $category = Category::where('id', 1)->first();
        $newGasQuantity = $category->gas_quantity + $data['quantity'];
        $category->update(['gas_quantity' => $newGasQuantity]);
        return [
            'data' => $category->refresh()->gas_quantity,
            'statusCode' => 200,
            'status' => true,
            'message' => 'Gas Refill Value Increased Successfully',
        ];
    }

    public function getGasRefillQuantity()
    {
        $category = Category::first();
         return [
            'data' => $category->gas_quantity,
            'statusCode' => 200,
            'status' => true,
            'message' => 'Gas Refill Value Retrieved Successfully'
        ];
    }
}
