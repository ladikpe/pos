<?php

namespace App\Services;

use App\Helpers\CheckingIdHelpers;
use App\Models\Customer;
use App\Models\Debtor;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Builder;

class DashBoardServices
{
    use ResponseTraits;
    protected User $user;
    protected Customer $customer;
    protected Transaction $transaction;
    protected Debtor $debtor;

    public function __construct(User $user, Customer $customer, Transaction $transaction, Debtor $debtor)
    {
        $this->user = $user;
        $this->customer = $customer;
        $this->transaction = $transaction;
        $this->debtor = $debtor;
    }

    public function index(): array
    {
        $counter = [
            'nos_of_user' => CheckingIdHelpers::checkAuthUserBranch($this->user)->count(),
            'nos_of_customer' => $this->customer->count(),
            'total_transaction' => CheckingIdHelpers::checkAuthUserBranch($this->transaction)->sum('amount'),
            'total_pos_transaction' => CheckingIdHelpers::checkAuthUserBranch($this->transaction)
                                                                                        ->withSum(
                                                                                            [ 'paymentBreakDown' => fn ($query) => $query->where('transaction_mode_id', 1)],
                                                                                            'amount'
                                                                                        )->get()->sum('payment_break_down_sum_amount'),
            'total_cash_transaction' => CheckingIdHelpers::checkAuthUserBranch($this->transaction)
                                                                                                ->withSum(
                                                                                                    [ 'paymentBreakDown' => fn ($query) => $query->where('transaction_mode_id', 2)],
                                                                                                    'amount'
                                                                                                )->get()->sum('payment_break_down_sum_amount'),
            'total_transfer_transaction' => CheckingIdHelpers::checkAuthUserBranch($this->transaction)
                                                                    ->whereHas('paymentBreakDown',  static function($query){$query->where('transaction_mode_id', 3);})
                                                                    ->withSum('paymentBreakDown','amount')->get()->sum('payment_break_down_sum_amount'),
            'total_pay_later' =>  CheckingIdHelpers::checkAuthUserBranch($this->transaction)->withSum([ 'paymentBreakDown' => fn ($query) => $query->where('transaction_mode_id', 4)],
                                                                                                'amount'
                                                                                            )->get()->sum('payment_break_down_sum_amount'),
            'nos_of_owing_debtor' => $this->counterForOwingDebtor(),
        ];
        return [
                'data' => $counter,
                'message' =>  'Total Dashboard Counter retrieved Successfully',
                'status' => true,
                'statusCode' => 200
        ];
    }

    public function fetchAllDebtorStillOwing(array $data): array
    {
        $search = $data['search'] ?? null;
        $debtors = CheckingIdHelpers::checkAuthUserBranch($this->debtor);
        $debtors = $debtors
                            ->select('id','debtor_number', 'order_number','customer_id','payment_duration','total_amount', 'branch_id', 'user_id', 'created_at')
                            ->when($search, function ($query) use ($search) {
                                $query->whereDate('created_at', $search);
                            })
                            ->with(['debtorDetails:id,debtor_id,debtor_number,initial_payment,total_amount,discount,payment_date'])
                            ->with(['customer:id,name,email'])
                             ->with(['employee:id,first_name,last_name,staff_id'])
                            ->withSum('debtorDetails', 'initial_payment')
                            ->get()
                            ->filter(static function ($items){
                                return $items['total_amount'] < $items['debtor_details_sum_initial_payment'] || $items['debtor_details_sum_initial_payment'] === null;
                            })->values();

        return [
            'message' => 'All Still Owing Debtors selected Successfully',
            'data' => $debtors,
            'status' => true,
            'statusCode' => 200,
        ];
    }

    public function counterForOwingDebtor()
    {
         return CheckingIdHelpers::checkAuthUserBranch($this->debtor)->select('id', 'total_amount')
                                    ->with('debtorDetails')
                                    ->withSum('debtorDetails', 'initial_payment')
                                    ->get()
                                    ->filter( function ($items) {
                                        return $items['total_amount'] < $items['debtor_details_sum_initial_payment'] || $items['debtor_details_sum_initial_payment'] === null;
                                    })
                                   ->count();

    }


}
