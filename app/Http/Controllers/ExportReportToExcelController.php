<?php

namespace App\Http\Controllers;

use App\Exports\DeletedDailyReportExport;
use App\Exports\businessSegmentReportExport;
use App\Exports\CashierDailyExport;
use App\Exports\CashierReportExport;
use App\Exports\customerTypeReportExport;
use App\Exports\DailyReportExport;
use App\Exports\DebtorReportExport;
use App\Exports\inventoryHistoryReportExport;
use App\Exports\InventoryOrProductTypeReportExport;
use App\Exports\SalesTypeReportExport;
use App\Exports\TransactionReportExport;
use App\Exports\accessoriesDailyReportExport;
use App\Http\Requests\IndexBusinessTypeReportForExcelRequest;
use App\Http\Requests\IndexCashierSalesReportForExcelRequest;
use App\Http\Requests\IndexCustomerTypeReportForExcelRequest;
use App\Http\Requests\indexDailyNullReportForExcelRequest;
use App\Http\Requests\IndexDebtorReportForExcelRequest;
use App\Http\Requests\IndexReportForExcelRequest;
use App\Http\Requests\IndexSalesTypeReportForExcelRequest;
use App\Http\Requests\IndexTransactionReportForExcelRequest;
use App\Http\Requests\InventoryHistoryReportForExcelRequest;
use App\Http\Requests\InventoryOrProductReportForExcelRequest;
use App\Http\Requests\InventoryOrProductTypeReportForExcelRequest;
use App\Http\Requests\IndexAccessoriesDailyReportForExcelRequest;
use App\Services\ReportBetaServices;
use App\Services\ReportServices;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportReportToExcelController extends Controller
{
    private $reportServices;
    private $reportBetaServices;

    public function __construct(ReportServices $reportServices, ReportBetaServices $reportBetaServices){
        $this->reportServices = $reportServices;
        $this->reportBetaServices = $reportBetaServices;
    }

    public function dailyReportExport(IndexReportForExcelRequest $indexReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportServices->dailyReport($indexReportForExcelRequest->all());
        return Excel::download(new DailyReportExport( $response['header'], $response['data']),
            'daily_report.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function dailyReportWithTrashed(IndexDailyNullReportForExcelRequest $dailyNullReportForExcelRequest) : BinaryFileResponse
    {
          $response = $this->reportServices->dailyReport($dailyNullReportForExcelRequest->all());
           return Excel::download(new DeletedDailyReportExport( $response['header'], $response['data']),
            'daily_deleted_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }

    public function transactionReportExport(IndexTransactionReportForExcelRequest $indexTransactionReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportServices->transactionReport($indexTransactionReportForExcelRequest->all());
        return Excel::download(new TransactionReportExport( $response['header'], $response['data']),
            'transaction_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }

    public function cashierReportExport(IndexCashierSalesReportForExcelRequest $indexCashierSalesReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportServices->cashierReport($indexCashierSalesReportForExcelRequest->all());
        return Excel::download(new CashierReportExport( $response['header'], $response['data']),
            'transaction_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }

    public function debtorReportExport(IndexDebtorReportForExcelRequest $indexDebtorReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportServices->debtorReport($indexDebtorReportForExcelRequest->all());
        return Excel::download(new DebtorReportExport($response['header'], $response['data']),
            'debtor_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }

    public function cashierDailyReportExport(IndexDebtorReportForExcelRequest $indexDebtorReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportServices->cashierDailyReport($indexDebtorReportForExcelRequest->all());
        return Excel::download(new CashierDailyExport($response['header'], $response['data']),
            'cashier_daily_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }

    public function saleTypeReportExport(IndexSalesTypeReportForExcelRequest $indexSalesTypeReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportBetaServices->salesTypeReport($indexSalesTypeReportForExcelRequest->all());
        return Excel::download(new SalesTypeReportExport($response['header'], $response['data']),
            'sales_type_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }

    public function businessTypeReportExport(IndexBusinessTypeReportForExcelRequest $indexBusinessTypeReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportBetaServices->businessSegmentReport($indexBusinessTypeReportForExcelRequest->validated());
        return Excel::download(new businessSegmentReportExport($response['header'], $response['data']),
            'business_type_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }


    public function customerTypeReportExport(IndexCustomerTypeReportForExcelRequest $indexCustomerTypeReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportBetaServices->customerTypeReport($indexCustomerTypeReportForExcelRequest->all());
        return Excel::download(new customerTypeReportExport($response['header'], $response['data']),
            'customer_type_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }


    public function InventoryOrProductTypeReportExport(InventoryOrProductReportForExcelRequest $inventoryOrProductReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportBetaServices->InventoryOrProductTypeReport($inventoryOrProductReportForExcelRequest->all());
        return Excel::download(new InventoryOrProductTypeReportExport($response['header'], $response['data']),
            'product_type_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }


    public function inventoryHistoryReportExport(InventoryHistoryReportForExcelRequest $inventoryHistoryReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportBetaServices->inventoryHistoryReport($inventoryHistoryReportForExcelRequest->all());
        return Excel::download(new inventoryHistoryReportExport($response['header'], $response['data']),
            'inventory_history_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }


        public function accessoriesDailyReportExports(IndexAccessoriesDailyReportForExcelRequest $indexAccessoriesDailyReportForExcelRequest): BinaryFileResponse
    {
        $response = $this->reportServices->accessoriesDailyReport($indexAccessoriesDailyReportForExcelRequest->all());
        return Excel::download(new accessoriesDailyReportExport($response['header'], $response['data']),
            'inventory_history_report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
    }

}
