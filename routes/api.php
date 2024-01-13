<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtorController;
use App\Http\Controllers\ExportReportToExcelController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentDurationController;
use App\Http\Controllers\ReportBetaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SynchronizationController;
use App\Http\Controllers\TransactionModeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Excel;

//use Maatwebsite\Excel\Facades\Excel;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('refresh', 'refresh');
    Route::post('forget-password', 'forgetPassword');
    Route::post('reset-password', 'resetPassword');
});

    Route::controller(ExportReportToExcelController::class)->group(function () {
        Route::get('export-daily-report', 'dailyReportExport');
        Route::get('export-transaction-report', 'transactionReportExport');
        Route::get('export-cashier-sale-report', 'cashierReportExport');
        Route::get('export-debtor-report', 'debtorReportExport');
        Route::get('export-cashier-daily-report', 'cashierDailyReportExport');
        Route::get('export-sales-type-report', 'saleTypeReportExport');
        Route::get('export-business-type-report', 'businessTypeReportExport');
        Route::get('export-customer-type-report', 'customerTypeReportExport');
        Route::get('export-inventory-or-product-type-report', 'InventoryOrProductTypeReportExport');
        Route::get('export-inventory-history-report', 'inventoryHistoryReportExport');
        Route::get('export-daily-deleted-report', 'dailyReportWithTrashed');
        Route::get('export-accessories-daily-report', 'accessoriesDailyReportExports');
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('cashier')->group(base_path('routes/cashier.php'));
        Route::group(['middleware' => ['admin']], static function () {
            Route::post('register', [AuthController::class, 'register']);
            Route::controller(UserController::class)->group(function () {
                Route::prefix('user')->group(function () {
                    Route::get('index', 'index');
                    Route::post('change-password', 'changePassword');
                    Route::get('show', 'show');
                    Route::post('update', 'update');
                    Route::get('authenticated', 'authUser');
                    Route::post('change-status', 'changeStatus');
                    Route::post('change-user-role', 'changeUserRole');
                    Route::post('update-profile', 'updateUserProfile');
                    Route::delete('delete/{id}', 'delete');
                });
            });

            Route::controller(InventoryController::class)->group(function () {
                Route::prefix('inventories')->group(function () {
                    Route::get('/', 'index');
                    Route::post('store', 'store');
                    Route::get('edit', 'edit');
                    Route::post('update', 'update');
                    Route::delete('delete/{id}', 'delete');
                    Route::post('quantity-restock', 'inventoryQuantityRestock');
                });
            });

            Route::controller(CustomerController::class)->group(function () {
                Route::prefix('customer')->group(function () {
                    Route::get('index', 'index');
                    Route::post('store', 'store');
                    Route::post('update', 'update');
                    Route::delete('delete/{id}', 'delete');
                    Route::get('show', 'show');
                });
            });
            Route::controller(CustomerController::class)->group(function () {
                Route::prefix('customer-type')->group(function () {
                    Route::get('index', 'indexCustomerType');
                    Route::post('store', 'storeCustomerType');
                    Route::delete('delete/{id}', 'deleteCustomerType');
                    Route::get('show', 'showCustomerType');
                    Route::get('fetch-all', 'fetchCustomerType');
                    Route::get('fetch-price-type', 'getPriceType');
                });
            });
            Route::controller(CustomerController::class)->group(function () {
                Route::prefix('business-segment')->group(function () {
                    Route::get('index', 'indexBusinessSegment');
                    Route::get('show', 'showBusinessSegment');
                    Route::post('store', 'storeBusinessSegment');
                    Route::delete('delete/{id}', 'deleteBusinessSegment');
                });
            });
        });

        //Inventory Controller
        Route::controller(InventoryController::class)->group(function(){
            Route::prefix('inventories')->group(function(){
                Route::get('/','index');
                Route::post('store','store');
                Route::get("edit","edit");
                Route::put("update/{id}","update");
                Route::delete("delete/{id}","delete");
            });
        });



        Route::controller(BankController::class)->group(function () {
            Route::prefix("banks")->group(function () {
                Route::get('/', 'index');
                Route::post('store', 'store');
                Route::get("edit", "edit");
                Route::delete("delete/{id}", "delete");
                Route::get('fetch-all-banks', 'fetchAllBanks');
            });
        });


        Route::controller(BranchController::class)->group(function () {
            Route::prefix('branch')->group(function () {
                Route::get('/', 'index');
                Route::post('store', 'store');
                Route::get("edit", "edit");
                Route::delete("delete/{id}", "delete");
            });
        });

        Route::controller(SettingController::class)->group(function () {
            Route::prefix('settings')->group(function () {
                Route::get('show', 'show');
                Route::post('store', 'store');
                Route::post('add-to-gas', 'addToGas');
                Route::get('get-gas-quantity', 'getGasRefillQuantity');
            });
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::prefix("categories")->group(function () {
                Route::get('/', 'index');
                Route::post('store', 'store');
                Route::get("edit", "edit");
                Route::delete("delete/{id}", "delete");
                Route::get('fetch-all-category', 'getAllCategory');
            });
        });

        Route::controller(TransactionModeController::class)->group(function () {
            Route::prefix('payment-mode')->group(function () {
                Route::get('/', 'index');
                Route::get('show', 'show');
                Route::post('store', 'store');
                Route::delete('delete/{id}', 'delete');
                Route::post('store-pos', 'storePos');
                Route::get('show-pos', 'showPos');
                Route::delete('delete-pos/{id}', 'deletePos');
                Route::get('fetch-all-pos', 'fetchAllPos');
            });
        });


        Route::controller(OrderController::class)->group(function () {
            Route::prefix('orders')->group(function () {
                Route::get('index', 'index');
                Route::post('store', 'store');
                Route::get('show/{id}', 'show');
                Route::post('order-details-store', 'orderDetails');
                Route::post('order-transaction-store', 'orderTransaction');
                Route::get('user-order', 'getCashierByOrderId');
                Route::delete('delete/{id}', 'deleteOrder');
                Route::get('index-trashed', 'getOrdersWithTrash');
                Route::get('show-trashed/{id}', 'showTrashed');
            });
        });

        Route::controller(DashboardController::class)->group(function () {
            Route::prefix('dashboard')->group(function () {
                Route::get('counter', 'index');
                Route::get('debtors-still-owing', 'fetchAllDebtorStillOwing');

            });
        });

        Route::controller(PaymentDurationController::class)->group(function () {
            Route::prefix('payment-duration')->group(function () {
                Route::get('index', 'index');
                Route::post('store', 'store');
                Route::get('show', 'show');
                Route::delete('delete/{id}', 'delete');
            });
        });


        Route::controller(DebtorController::class)->group(function () {
            Route::prefix('debtors')->group(function () {
                Route::get('index', 'index');
                Route::post('store', 'store');
                Route::get('show', 'show');
                Route::delete('delete/{id}', 'delete');
                Route::post('customer-debt-payment', 'customerDebtPayment');
                Route::get('fetch-all-order', 'fetchAllOrders');
            });
        });

        Route::controller(OrderController::class)->group(function () {
                Route::get('fetch-all-inventory', 'fetchAllInventory');
                Route::get('fetch-all-customer', 'fetchAllCustomer');
                Route::get('fetch-all-orders', 'fetchAllOrders');
        });



        Route::controller(ReportController::class)->group(function () {
                Route::get('daily-report', 'dailyReport');
                Route::get('transaction-report', 'transactionReport');
                Route::get('cashier-sales-report', 'cashierSalesReport');
                Route::get('report-fetch-all-user', 'fetchAllUser');
                Route::get('customer-debtor-report', 'debtorReport');
                Route::get('cashier-daily-sales-report', 'cashierDailyReport');
                Route::get('order-detail-report', 'orderDetailReport');
                Route::get('accessories-daily-report', 'accessoriesDailyReport');
        });

//        Route::controller(ExportReportToExcelController::class)->group(function () {
//            Route::get('export-daily-report', 'dailyReportExport');
//        });

        Route::controller(ReportBetaController::class)->group(function () {
            Route::get('business-segment-report', 'businessSegmentReport');
            Route::get('customer-type-report', 'customerTypeReport');
            Route::get('inventory-type-report', 'InventoryOrProductTypeReport');
            Route::get('sales-type-report', 'salesTypeReport');
            Route::get('inventory-history-report', 'inventoryHistoryReport');
        });

        Route::controller(SynchronizationController::class)->group(function () {
            Route::get('synchronization-to-db', 'synchronizationToDb');
        });


        Route::controller(LoyaltyController::class)->group(function () {
            Route::prefix('loyalty')->group(function () {
                Route::get('index', 'index');
                Route::post('store', 'store');
            });
        });

    });


});


