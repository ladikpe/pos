<?php

namespace App\Services\Synchronization;

use App\Models\Order;
use App\Models\SynchronizeDebtor;
use App\Models\SynchronizeOrder;
use App\Models\SynchronizeTransaction;
use App\Models\SynchronizeUser;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ReportServices;
use Exception;
use Illuminate\Support\Facades\DB;

class DataSynchronizationServices{

    public function __construct(Protected SynchronizeOrder $synchronizeOrder,
                                Protected SynchronizeDebtor $synchronizeDebtor,
                                Protected SynchronizeTransaction $synchronizeTransaction,
                                Protected SynchronizeUser $synchronizeUser,
                                Protected Order $order,
                                Protected Transaction $transaction,
                                Protected User $user,
                                Protected ReportServices $reportServices)
    {

    }
    //    daily and transaction report
    public function synchronizeDailyReport(array $data): array
    {
        $orderData  = $this->reportServices->cashierReport($data + ['branch_id'  => auth()->user()->id]);
                                          foreach ($orderData['data'] as $item)
                                          {
                                              $datum =  [
                                                  'pos' =>  $item->pos,
                                                  'cash' => $item-> cash,
                                                  'transfer' => $item-> transfer,
                                                  'pay_later' => $item->pay_later,
                                                  'total_amount' => $item->total_amount,
                                                  'transaction_date' => $item->transaction_date,
                                                  'cashier_name' => $item['user']['first_name'] .', '.$item['user']['last_name'] ?? null ,
                                                  'cashier_staff_id' => $item['staff_id'] ?? null,
                                                  'branch_id' => $item['branch_id']
                                              ];
                                              dd($datum);
                                              /// for daily report
//                                              DB::table('')->updateOrInsert([]);
                                          }
        return [
            'data' => null,
            'message' => '----',
            'status' => 'true',
            'statusCode' => 200
        ];
    }

    public function synchronizeToTransactionReport(array $data): array
    {
        $transactionData  = $this->reportServices->transactionReport($data + ['branch_id'  => auth()->user()->id]);
        foreach ($transactionData['data'] as $item)
        {
            $datum =  [
                'pos' =>  $item->pos,
                'cash' => $item-> cash,
                'bank_transfer' => $item->BANK_TRANSFER,
                'pay_later' => $item->PAY_LATER,
                'total_amount' => $item->TOTAL_AMOUNT,
                'transaction_date' => $item->transaction_date,
                'branch_id' => $item['branch_id']
            ];
            dd($datum);
            /// for transaction report
//            DB::table('')->updateOrInsert([]);
        }
        return [
            'data' => null,
            'message' => '----',
            'status' => 'true',
            'statusCode' => 200
        ];
    }

    public function synchronizeToDatabase(array $data)
    {
        try{
            $this->synchronizeDailyReport($data);
            $this->synchronizeToTransactionReport($data);
       }
       catch(Exception $exception){
            return $exception;
        }
    }


    public function synchronizeReportToMainBranch(array $data)
    {
        $live_connection  = DB::connection('lagos_server');
        $daily_reports = $live_connection->table('synchronize_daily_report')->get();
        $transaction_reports = $live_connection->table('synchronize_transaction_report')->get();

        foreach($daily_reports->chunk(1000) as $index => $daily_report)
        {
            $daily_report->updateOrInsert();
            return true;
        }

        foreach ($transaction_reports->chunk(1000) as $index => $transaction_report) {
              $transaction_report->updateOrInsert();
              return true;
        }

//        DB::table('posts')->orderBy('id')->chunk(50, function (＄posts) {
//            foreach (＄posts as ＄post) {
//                echo ＄post->title;
//            }
//        });
    }





}
