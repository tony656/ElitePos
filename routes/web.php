<?php

use App\Http\Controllers\bankingController;
use App\Http\Controllers\couponController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\expensesController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\itemRequestController;
use App\Http\Controllers\logController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\notification;
use App\Http\Controllers\orderController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\salsController;
use App\Http\Controllers\supplier;
use App\Http\Controllers\systemController;
use App\Http\Controllers\userController;
use App\Http\Controllers\validationController;
use App\Http\Controllers\vendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Language switcher route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'sw'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    return redirect()->back();
})->name('lang.switch')->middleware(['web']);

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [validationController::class, 'index'])->name(name: 'login');

Route::post('/login', action: [validationController::class, 'login']);

Route::get('/signout', [validationController::class, 'logoutAndRedirect'])->name('signout');

// Redirect /logout to /signout for compatibility
Route::get('/logout', function () {
    return redirect()->route('signout');
});

Route::get('/terms', function () {
    return view('terms');
});

// Emergency Admin Access Routes (bypass system shutdown/block)
Route::get('/emergency-login', [validationController::class, 'emergencyLogin'])->name('emergency.login');
Route::post('/emergency-login', [validationController::class, 'processEmergencyLogin'])->name('emergency.login.process');
Route::post('/emergency-extend', [validationController::class, 'extendEmergencyAccess'])->name('emergency.extend');

Route::post('/select-account', [validationController::class, 'selectAccount'])->name('select.account');

Route::post('/storeSession', [validationController::class, 'storeSession'])->name('storeSession');

// Public receipt route - accessible without authentication for QR scanning
Route::get('/receipt', [salsController::class, 'viewSalesPublic'])->name('receipt.public');

//Admin group starts here
Route::post('/cache/clear/products', function () {
    $accountId = request('account_id');
    ProductsController::clearProductCache($accountId);

    return response()->json(['message' => 'Cache cleared successfully']);
})->middleware(['auth', 'admin']);

// System status API (accessible to all authenticated users)
Route::middleware(['auth'])->get('/api/system-status', [systemController::class, 'getSystemStatus'])->name('api.system.status');

Route::middleware(['auth'])->get('/api/active-users-count', [systemController::class, 'getActiveUsersCount'])->name('api.active.users.count');

Route::middleware(['system.security'])->group(function () {
    Route::middleware(['auth'])->group(function () {

        // Customer KPI route (accessible to both admin and user)
        Route::get('/customer-kpi', [salsController::class, 'customerKPI'])->name('customer.kpi');

        Route::get('/home', fn () => view('home'))->name('home');

        Route::get('/dashboard', [homeController::class, 'dashboard'])->name('dashboard');

        Route::get('/signout', [validationController::class, 'logoutAndRedirect'])->name('signout');

        Route::get('/products', [productsController::class, 'index'])->name('products.index');

        Route::get('/newProducts', function () {
            return view('newProduct');
        });

        Route::post('/addProducts', [productsController::class, 'saveProduct']);

        Route::get('/downloadTemplate', [productsController::class, 'downloadTemplate'])->name('downloadTemplate');

        Route::post('/viewProduct', [productsController::class, 'viewProduct']);

        Route::post('/dltProduct', [productsController::class, 'dltProduct']);

        Route::get('/viewProduct', [productsController::class, 'viewProduct']);

        Route::post('/updateProducts', action: [productsController::class, 'updateProducts']);

        Route::get('/newOrder', [productsController::class, 'newOrder']);

        Route::get('/searchProduct', [productsController::class, 'search']);

        Route::get('/requestSearch', [productsController::class, 'requestSearch']);

        Route::get('/searchCustomers', [customerController::class, 'searchCustomer']);

        Route::get('/getCustomerDetails', [customerController::class, 'getCustomerDetails']);

        Route::get('/searchSellers', [userController::class, 'searchSeller']);

        // Manual invoice creation for admin
        Route::post('/createManualInvoice', [systemController::class, 'createManualInvoice'])->name('createManualInvoice');

        Route::post('/newOrder', [orderController::class, 'newOrder']);

        Route::post('/setOrderType', [orderController::class, 'setOrderType']);

        Route::get('/changeShop', [orderController::class, 'changeShop'])->name('changeShop');

        Route::post('/updateCartItem', [orderController::class, 'updateCartItem']);

        Route::post('/resumeOrder', [orderController::class, 'resumeOrder']);

        Route::post('/updateOrder', [orderController::class, 'updateOrder']);

        Route::get('/updateOrder', [productsController::class, 'newOrder']);

        Route::post('/updQuant', [orderController::class, 'updQuant']);

        Route::post('/updDisc', [orderController::class, 'updDisc']);

        Route::get('/itemRequest', [itemRequestController::class, 'index']);

        Route::get('/viewRequest', [itemRequestController::class, 'viewRequest']);

        Route::get('/viewRequestDetails/{requestId}', [itemRequestController::class, 'viewRequestDetails'])->name('viewRequestDetails');

        Route::post('/itemRequest', [itemRequestController::class, 'itemRequest']);

        Route::post('/removeFromCart', [orderController::class, 'dltProdOrdcart']);
        Route::post('/removeOfferItem', [orderController::class, 'removeOfferItem']);
        Route::post('/clearCart', [orderController::class, 'clearCart']);

        Route::post('/saveInfos', [orderController::class, 'saveInfo']);
        Route::post('/saveSeller', [orderController::class, 'saveSeller']);

        Route::get('/api/receivings-by-date', [productsController::class, 'getReceivingsByDate']);

        Route::post('/saveOrder', [orderController::class, 'saveOrder']);

        Route::get('/customers', [customerController::class, 'index']);

        Route::post('/newCustomer', [customerController::class, 'addCustomer']);

        Route::post('/editCustomer', [customerController::class, 'editCustomer']);

        Route::get('customerView', [customerController::class, 'customerView']);

        Route::post('/dltCustomer', [customerController::class, 'dltCustomer']);

        Route::post('/customers/bulk-group', [customerController::class, 'bulkAssignGroup'])->name('customers.bulkGroup');

        Route::get('/groups', [customerController::class, 'getGroups']);
        Route::post('/groups', [customerController::class, 'storeGroup']);
        Route::delete('/groups/{id}', [customerController::class, 'destroyGroup']);

        Route::post('/debt', [orderController::class, 'debt']);

        Route::get('/ordersList', [orderController::class, 'index']);

        Route::get('/supplier-credit', [vendorController::class, 'supplierCredit']);

        Route::get('/main-credit', [vendorController::class, 'mainCredit']);

        Route::post('/dltSupPay', [vendorController::class, 'dltSupPay']);

        Route::get('/suplierPayments', [vendorController::class, 'suplierPayments']);

        Route::get('/main-paid', [vendorController::class, 'mainPaid']);

        Route::post('/supplier-items', [vendorController::class, 'supplierItems']);

        Route::post('/supplierPay', [vendorController::class, 'supplierPay']);

        Route::post('/supplier-items', [vendorController::class, 'supplierItems']);

        Route::get('/sales', [salsController::class, 'index']);

        Route::get('/viewOrder', [orderController::class, 'viewOrder']);

        Route::post('/viewOrder', [orderController::class, 'viewOrder']);

        Route::post('/deleteOrder', [orderController::class, 'deleteOrder']);

        Route::post('/discount', [orderController::class, 'discount']);

        Route::post('/coupon', [orderController::class, 'coupon']);

        Route::post('/viewSales', [salsController::class, 'viewSales']);

        Route::get('/fullReport', [salsController::class, 'fullReport']);

        Route::get('/shopReport', [salsController::class, 'AllShopReport']);

        Route::get('/shop-daily-item-report', [salsController::class, 'AllShopDailyItemReport'])->name('shop.daily.item.report');

        Route::get('/mainStoreReport', [itemRequestController::class, 'mainStoreReport'])->name('mainStoreReport');

        Route::get('/kpi', [salsController::class, 'kpiDashboard'])->name('kpi');
        Route::get('/customer-kpi', [salsController::class, 'customerKPI'])->name('customer.kpi');

        Route::post('/viewInvoice', [orderController::class, 'viewInvoice']);

        Route::get('/viewSales', [salsController::class, 'viewSales']);

        Route::get('/expenses', [expensesController::class, 'index']);

        Route::post('/expenseInsert', [expensesController::class, 'expenseInsert']);

        Route::post('/expenseDate', [expensesController::class, 'index']);

        Route::match(['get', 'post'], '/saleDate', [salsController::class, 'index'])->name('saleDate');

        Route::get('/settings', [systemController::class, 'index']);

        Route::post('/settings/fix-customer-refs', [systemController::class, 'fixCustomerRefs'])->name('settings.fixCustomerRefs');

        Route::get('/main-customers', [systemController::class, 'mainCustomers']);

        Route::get('/security', [systemController::class, 'security'])->name('security');

        // System security toggle routes
        Route::post('/toggle-block-signins', [systemController::class, 'toggleBlockSignins'])->name('toggle.block.signins');
        Route::post('/toggle-system-shutdown', [systemController::class, 'toggleSystemShutdown'])->name('toggle.system.shutdown');
        Route::post('/toggle-system-mode', [systemController::class, 'toggleSystemMode'])->name('toggle.system.mode');

        Route::post('/personalData', [systemController::class, 'personalData']);

        Route::post('/upload-profile-picture', [systemController::class, 'uploadProfilePicture']);

        Route::post('/businessDetails', [systemController::class, 'businessDetails']);

        Route::post('/newAccount', [systemController::class, 'newAccount']);

        Route::post('/updateAccountProducts', [systemController::class, 'updateAccountProducts']);

        Route::get('/getAccountProducts/{accountId}', [systemController::class, 'getAccountProducts']); // Fixed route

        Route::get('/getAllProducts', [systemController::class, 'getAllProducts']);

        Route::post('/deleteAccount', [systemController::class, 'deleteAccount']);

        Route::post('/updateAccount', [systemController::class, 'updateAccount']);

        Route::post('/switch', [systemController::class, 'switchAccount']);

        Route::get('/getAllAccounts', [systemController::class, 'getAllAccounts']);

        Route::get('/allInvoices', [systemController::class, 'allInvoices']);

        // New routes for shop invoices with debts
        Route::get('/shopInvoices', [systemController::class, 'shopInvoices']);

        Route::get('/shopDebtors/{shopId}', [systemController::class, 'shopDebtors']);

        Route::match(['get', 'post'], '/customerDebtProducts', [systemController::class, 'customerDebtProducts'])->name('customerDebtProducts');

        Route::post('/payInvoiceDebt', [systemController::class, 'payInvoiceDebt']);
        Route::post('/undoInvoiceDebt', [systemController::class, 'undoInvoiceDebt']);
        Route::post('/editDebtProduct', [systemController::class, 'editDebtProduct']);
        Route::post('/deleteDebtProduct', [systemController::class, 'deleteDebtProduct']);

        Route::get('/paidInvoices', [systemController::class, 'paidInvoices'])->name('paidInvoices');

        // New route for paid invoices
        Route::post('/deletePaidInvoice', [systemController::class, 'deletePaidInvoice'])->name('deletePaidInvoice');

        Route::get('/paidInvoices', [systemController::class, 'paidInvoices'])->name('paidInvoices');

        Route::post('/duplicateProducts', [productsController::class, 'duplicateProducts'])->name('duplicateProducts');

        // Offer management routes
        Route::get('/offers', [productsController::class, 'offers'])->name('offers');
        Route::post('/saveOffer', [productsController::class, 'saveOffer'])->name('saveOffer');
        Route::get('/getOffers/{productId}', [productsController::class, 'getOffers'])->name('getOffers');
        Route::get('/allOffers', [productsController::class, 'allOffers'])->name('allOffers');
        Route::get('/getAllOffersApi', [productsController::class, 'getAllOffersApi'])->name('getAllOffersApi');
        Route::post('/deleteOffer', [productsController::class, 'deleteOffer'])->name('deleteOffer');
        Route::get('/checkOffer/{productId}/{quantity}', [productsController::class, 'checkOffer'])->name('checkOffer');
        Route::patch('/updateOffer/{id}', [productsController::class, 'updateOffer'])->name('updateOffer');
        Route::post('/removeOffer', [productsController::class, 'removeOffer'])->name('removeOffer');
        Route::get('/activeOffers', [productsController::class, 'fetchActiveOffers'])->name('activeOffers');
        Route::get('/soldOffers', [productsController::class, 'fetchSoldOffers'])->name('soldOffers');
        Route::post('/removeSoldOffer', [productsController::class, 'removeSoldOffer'])->name('removeSoldOffer');
        Route::patch('/updateSoldOffer/{salesId}', [productsController::class, 'updateSoldOffer'])->name('updateSoldOffer');

        Route::get('/offeredProductsReport', [productsController::class, 'offeredProductsReport'])->name('offeredProductsReport');
        Route::get('/search-products-for-offer', [productsController::class, 'searchProductsForOffer'])->name('searchProductsForOffer');

        Route::post('/dltExpense', [expensesController::class, 'dltExpense']);

        Route::get('/employees', [userController::class, 'index'])->name('employees');

        Route::get('/employees/create-modal', function () {
            $accounts = getUserAccounts();

            return view('employee-create', compact('accounts'));
        })->name('employees.create.modal');

        Route::post('/registerEmployee', [userController::class, 'registerEmployee'])->name('registerEmployee');

        Route::get('/sales/export', [salsController::class, 'export'])->name('sales.export');

        Route::get('/notification', [notification::class, 'index']);

        Route::middleware(['auth'])->group(function () {
            Route::get('/ai-agent', [\App\Http\Controllers\AiAgentController::class, 'index'])->name('ai-agent.index');
            Route::post('/ai-agent', [\App\Http\Controllers\AiAgentController::class, 'ask'])->name('ai-agent.ask');
        });

        Route::post('/sendNotif', [notification::class, 'notification']);

        Route::post('/undoSales', [salsController::class, 'undoSales']);

        Route::get('/searchSales', [salsController::class, 'searchSales']);

        Route::get('/track-sale', [salsController::class, 'trackSale'])->name('track.sale');

        Route::post('/returnSaleToOrder', [salsController::class, 'returnSaleToOrder']);

        Route::get('/getSalesDates', [salsController::class, 'getSalesDates'])->name('getSalesDates');

        // Check today's balance
        Route::get('/check-today-balance', [salsController::class, 'checkTodayBalance'])->name('check.today.balance');

        // Balance Check & Discrepancy Routes (Admin)
        Route::prefix('balance-check')->name('balance-check.')->group(function () {
            Route::get('/discrepancies/{shopId}/{date}', [App\Http\Controllers\BalanceCheckController::class, 'showDiscrepancies'])->name('discrepancies');
            Route::get('/discrepancy/{id}', [App\Http\Controllers\BalanceCheckController::class, 'showDiscrepancyDetail'])->name('discrepancy.detail');
            Route::post('/resolve/{id}', [App\Http\Controllers\BalanceCheckController::class, 'resolveDiscrepancy'])->name('resolve');
            Route::post('/recalculate/{shopId}/{date}', [App\Http\Controllers\BalanceCheckController::class, 'recalculateBalance'])->name('recalculate');
            Route::get('/all', [App\Http\Controllers\BalanceCheckController::class, 'allDiscrepancies'])->name('all');
        });

        Route::post('/deleteNotif', [notification::class, 'delete']);

        Route::get('/employeeView/{employeeId}', [userController::class, 'employeeView'])->name('employeeView');

        Route::post('/employeeView/{employeeId}', [userController::class, 'employeeView'])->name('employeeView.post');

        Route::post('/employeeDelete', [userController::class, 'employeeDelete'])->name('employeeDelete.post');

        Route::post('/updateEmployee', [userController::class, 'updateEmployee'])->name('updateEmployee');

        Route::post('/banUser', [userController::class, 'banUser'])->name('banUser');

        Route::post('/deleteUser', [userController::class, 'deleteUser'])->name('deleteUser');

        Route::post('/changePassword', [userController::class, 'changePassword'])->name('changePassword');

        Route::post('/dltItemReq', [itemRequestController::class, 'dltItemReq']);

        Route::post('/request/bulk-submit', [itemRequestController::class, 'bulkRequest'])->name('request.bulkSubmit');

        Route::post('/request/approve-all', [itemRequestController::class, 'approveAll'])->name('request.approveAll');

        Route::post('/request/pay', [itemRequestController::class, 'payInterShopRequest'])->name('request.pay');

        Route::post('/request/redoRequest', [itemRequestController::class, 'redoRequest'])->name('request.redoRequest');

        Route::post('/request/delete', [itemRequestController::class, 'deleteRequest'])->name('request.delete');

        Route::get('/coupons', [couponController::class, 'index']);

        Route::post('dltcoupon', [couponController::class, 'deltCoupon']);

        Route::post('/couponnew', [couponController::class, 'couponnew']);

        Route::get('restock', [productsController::class, 'restock']);

        Route::post('restock', [productsController::class, 'restock']);

        Route::get('main-receiving', [productsController::class, 'mainReceiving'])->name('main-receiving');
        Route::get('main-receivings', [productsController::class, 'mainReceivings'])->name('main-receivings');

        // New routes for separate receiving and return management
        Route::get('make-receiving', [productsController::class, 'makeReceiving'])->name('make-receiving');
        Route::post('make-receiving/process', [productsController::class, 'processReceiving'])->name('process-receiving');

        Route::post('approve-selected-receivings', [productsController::class, 'approveSelectedReceivings'])->name('approve-selected-receivings');
        Route::post('approve-all-receivings', [productsController::class, 'approveAllReceivings'])->name('approve-all-receivings');
        Route::post('approve-all-receivings-all-dates', [productsController::class, 'approveAllReceivingsAllDates'])->name('approve-all-receivings-all-dates');
        Route::post('undo-receivings', [productsController::class, 'undoReceivings'])->name('undo-receivings');
        Route::post('delete-selected-receivings', [productsController::class, 'deleteSelectedReceivings'])->name('delete-selected-receivings');

        Route::get('view-receivings', [productsController::class, 'viewReceivings'])->name('view-receivings');

        // Allocation display routes (for makeReceiving)
        Route::get('/get-supplier-users', [salsController::class, 'getSupplierUsers'])->name('get-supplier-users');
        Route::get('/get-user-suppliers', [salsController::class, 'getUserSuppliers'])->name('get-user-suppliers');

        Route::get('make-return', [productsController::class, 'makeReturn'])->name('make-return');
        Route::get('view-receivings', [productsController::class, 'viewReceivings'])->name('view-receivings');
        Route::get('main-return', [productsController::class, 'mainReturn'])->name('main-return');

        Route::get('make-return', [productsController::class, 'makeReturn'])->name('make-return');
        Route::get('main-returns', [productsController::class, 'mainReturns'])->name('main-returns');
        Route::post('make-return/process', [productsController::class, 'processReturn'])->name('process-return');

        Route::post('return/approve', [productsController::class, 'approveReturn'])->name('return.approve');
        Route::post('return/reject', [productsController::class, 'rejectReturn'])->name('return.reject');

        Route::get('view-returns', [productsController::class, 'viewReturns']);

        Route::get('receiving-report', [productsController::class, 'receivingReport'])->name('receiving-report');

        Route::get('items-report', [productsController::class, 'itemsReport'])->name('items-report');

        Route::get('/most-sold-products', [productsController::class, 'mostSoldProducts'])->name('most-sold-products');

        Route::get('/stock-report', [productsController::class, 'report']);

        Route::get('/supplier', [supplier::class, 'index']);

        // Banking Admin Routes
        Route::get('/banking-partners', [bankingController::class, 'partners']);
        Route::get('/banking-suppliers', [bankingController::class, 'suppliers']);
        Route::post('/banking-supplier/store', [bankingController::class, 'storeSupplier']);
        Route::post('/banking-supplier/update/{id}', [bankingController::class, 'updateSupplier']);
        Route::post('/banking-supplier/delete/{id}', [bankingController::class, 'deleteSupplier']);
        Route::post('/banking-supplier/account/store/{supplierId}', [bankingController::class, 'storeSupplierAccount']);
        Route::post('/banking-supplier/account/update/{id}', [bankingController::class, 'updateAccount']);
        Route::post('/banking-supplier/account/delete/{id}', [bankingController::class, 'deleteAccount']);

        Route::get('/banking-beneficiaries', [bankingController::class, 'beneficiaries']);
        Route::post('/banking-beneficiary/store', [bankingController::class, 'storeBeneficiary']);
        Route::post('/banking-beneficiary/update/{id}', [bankingController::class, 'updateBeneficiary']);
        Route::post('/banking-beneficiary/delete/{id}', [bankingController::class, 'deleteBeneficiary']);
        Route::post('/banking-beneficiary/account/store/{beneficiaryId}', [bankingController::class, 'storeBeneficiaryAccount']);
        Route::post('/banking-beneficiary/account/update/{id}', [bankingController::class, 'updateAccount']);
        Route::post('/banking-beneficiary/account/delete/{id}', [bankingController::class, 'deleteAccount']);

        // Banking Transfers Routes
        Route::get('/banking-transfers', [bankingController::class, 'transfers']);
        Route::post('/banking-transfer/store', [bankingController::class, 'storeTransfer']);
        Route::post('/banking-transfer/delete/{id}', [bankingController::class, 'deleteTransfer']);

        // Supplier Deposit Report (Admin)
        Route::get('/banking/supplier-deposit-report', [bankingController::class, 'supplierDepositReport'])->name('banking.supplierDepositReport');
        Route::get('/banking/supplier-deposit-report/export', [bankingController::class, 'exportSupplierDepositReport'])->name('banking.exportSupplierDepositReport');
        Route::get('/banking/get-suppliers-by-shop', [bankingController::class, 'getSuppliersByShop'])->name('banking.getSuppliersByShop');
        Route::get('/banking/search-supplier', [bankingController::class, 'searchSupplier'])->name('banking.searchSupplier');
        Route::get('/banking/search-beneficiary', [bankingController::class, 'searchBeneficiary'])->name('banking.searchBeneficiary');

        // Banking Chips Routes (Admin)
        Route::get('/banking-chips', [bankingController::class, 'chips'])->name('banking-chips');
        Route::post('/banking-chip/store', [bankingController::class, 'storeChip']);
        Route::post('/banking-chip/update/{id}', [bankingController::class, 'updateChip']);
        Route::post('/banking-chip/delete/{id}', [bankingController::class, 'deleteChip']);

        Route::get('/vendors', [vendorController::class, 'index']);

        Route::get('/main-supplier', [supplier::class, 'index']);

        Route::post('newVendor', [vendorController::class, 'newVendor']);

        Route::post('returnStock', [productsController::class, 'returnStock']);

        Route::post('dltrestock', [productsController::class, 'dltrestock']);

        Route::post('dltVendeor', [vendorController::class, 'dltVendeor']);

        Route::post('restockProd', [productsController::class, 'restockProd']);

        Route::post('payout', [orderController::class, 'payout']);

        Route::post('requpdQuant', [itemRequestController::class, 'updQuant']);

        Route::post('requestSubmit', action: [itemRequestController::class, 'payout']);

        Route::post('reqdltProdOrd', [itemRequestController::class, 'dltProdOrd']);

        Route::post('dltProdOrd', [orderController::class, 'dltProdOrd']);
        Route::post('returnToMainStore', [productsController::class, 'returnToMainStore']);
        Route::post('/bulkClearQuantity', [productsController::class, 'bulkClearQuantity'])->name('bulk.clearQuantity');
        Route::post('/bulkSyncPrices', [productsController::class, 'syncPricesToShops'])->name('bulk.syncPrices');

        Route::post('saveInfo', [itemRequestController::class, 'saveInfo']);

        Route::post('/approveRequest', [itemRequestController::class, 'approveRequest'])->name('approveRequest');

        Route::post('/rejectRequest', [itemRequestController::class, 'rejectRequest'])->name('rejectRequest');

        Route::post('/outOfStockRequest', [itemRequestController::class, 'outOfStockRequest'])->name('outOfStockRequest');

        Route::post('cashSubmit', action: [salsController::class, 'cashSubmit']);
        Route::post('cashDelete', action: [salsController::class, 'cashDelete']);

        Route::post('processDebt', [orderController::class, 'debt']);

        Route::get('/details/{id}', [customerController::class, 'details']);

        Route::post('newAccount', [systemController::class, 'newAccount']);

        Route::get('/ads', [systemController::class, 'ads'])->name('ads');
        Route::post('/ads', [systemController::class, 'ads'])->name('ads.store');

        Route::delete('/deleteAd', [systemController::class, 'destroyAd'])->name('deleteAd');

        Route::get('logs', [logController::class, 'index']);

        Route::post('viewVendor', [vendorController::class, 'viewVendor']);

        Route::post('madeniPay', [productsController::class, 'madeni']);

        Route::post('deleteDebt', [orderController::class, 'deleteDebt']);

        Route::post('customerView', [customerController::class, 'customerView']);

        Route::get('/getAccountProducts/{accountId}', [systemController::class, 'getAccountProducts']);

        Route::post('updateAccountProducts', [systemController::class, 'updateAccountProducts']);
        // Account Migration Routes (Admin only)
        Route::get('/migration', [MigrationController::class, 'index'])->name('migration.index');
        Route::post('/migration/table', [MigrationController::class, 'migrateTable'])->name('migration.table');
        Route::post('/migration/dry-run', [MigrationController::class, 'migrateTable'])->name('migration.dry-run');
        Route::post('/migration/all', [MigrationController::class, 'migrateAll'])->name('migration.all');

    });

    // This should be the LAST route in your web.php file
    Route::fallback(function () {
        // Option 1: Redirect to custom 404 page
        return response()->view('404', [], 404);

        // Option 2: Redirect to home page
        // return redirect('/');

        // Option 3: Redirect to a specific page with message
        // return redirect('/')->with('error', 'The page you requested does not exist.');
    });

});
