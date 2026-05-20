<?php
use App\Exports\MonthlyReportExport;
use App\Http\Controllers\couponController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\logController;
use App\Http\Controllers\supplier;
use App\Http\Controllers\vendorController;
use App\Http\Controllers\bankingController;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\orderController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\salsController;
use App\Http\Controllers\systemController;
use App\Http\Controllers\userController;
use App\Http\Controllers\validationController;
use App\Http\Controllers\BalanceCheckController;
use App\Models\expensesModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homeController;
use App\Http\Controllers\expensesController;
use App\Http\Controllers\notification;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\itemRequestController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\FaceRecognitionController;
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

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [validationController::class, 'index'])->name(name: 'login');

Route::get('/signout', [validationController::class, 'logoutAndRedirect'])->name('signout');

// Redirect /logout to /signout for compatibility
Route::get('/logout', function() {
    return redirect()->route('signout');
});

Route::post('/login', action: [validationController::class, 'login'])->name('login');

// Emergency Admin Access Routes (bypass system shutdown/block)
Route::get('/admin/emergency-login', [validationController::class, 'emergencyLogin'])->name('admin.emergency.login');
Route::post('/admin/emergency-login', [validationController::class, 'processEmergencyLogin'])->name('admin.emergency.login.process');
Route::post('/admin/emergency-extend', [validationController::class, 'extendEmergencyAccess'])->name('admin.emergency.extend');

Route::post('/select-account', [validationController::class, 'selectAccount'])->name('select.account');

// Face Recognition Routes (require authentication)
Route::middleware(['auth'])->prefix('face')->name('face.')->group(function () {
    Route::get('/register', [FaceRecognitionController::class, 'showRegistrationPage'])->name('register.page');
    Route::post('/register', [FaceRecognitionController::class, 'register'])->name('register');
    Route::get('/verify-page', [FaceRecognitionController::class, 'showVerificationPage'])->name('verify.page');
    Route::post('/verify', [FaceRecognitionController::class, 'verify'])->name('verify');
    Route::get('/encodings', [FaceRecognitionController::class, 'getEncodings'])->name('encodings');
    Route::delete('/encoding/{id}', [FaceRecognitionController::class, 'deleteEncoding'])->name('delete.encoding');
    Route::get('/logs', [FaceRecognitionController::class, 'getVerificationLogs'])->name('logs');
});

//Admin group starts here

// System status API (accessible to all authenticated users)
Route::middleware(['auth'])->get('/api/system-status', [systemController::class, 'getSystemStatus'])->name('api.system.status');

Route::middleware(['system.security'])->group(function () {
    Route::middleware(['auth'])->group(function () {

        // Customer KPI route (accessible to both admin and user)
        Route::get('/customer-kpi', [salsController::class, 'customerKPI'])->name('customer.kpi');

 Route::prefix('admin')->name('admin.')->group(function () {
       
 Route::get('/home', fn () => view('home'))->name('home');
    
 Route::get('/dashboard', [homeController::class, 'dashboard'])->name('dashboard');

 Route::get('/signout', [validationController::class, 'logoutAndRedirect'])->name('signout');


    Route::get('/products', [productsController::class, 'index'])->name('products.index');

    Route::get('/newProducts', function() {
        return view('admin/newProduct');
    });

    Route::post('/addProducts', [productsController::class, 'saveProduct']);
    
    Route::get('/downloadTemplate', [productsController::class, 'downloadTemplate'])->name('downloadTemplate');

    Route::post('/viewProduct', [productsController::class, 'viewProduct']);

    Route::post('/dltProduct', [productsController::class, 'dltProduct']);

    Route::get('/viewProduct', [productsController::class, 'viewProduct']);

    Route::post('/updateProducts', action: [productsController::class, 'updateProducts']);

    Route::get('/newOrder', [productsController::class, 'newOrder']);

    Route::get('/searchProduct', [productsController::class, 'search']);

    Route::get('/searchCustomers', [customerController::class, 'searchCustomer']);
    Route::get('/admin/searchCustomers', [customerController::class, 'searchCustomer']);

    Route::get('/getCustomerDetails', [customerController::class, 'getCustomerDetails']);

    Route::get('/searchSellers', [userController::class, 'searchSeller']);
    
    // Manual invoice creation for admin
    Route::post('/createManualInvoice', [systemController::class, 'createManualInvoice'])->name('createManualInvoice');

    Route::post('/newOrder', [orderController::class, 'newOrder']);

    Route::get('/changeShop', [orderController::class, 'changeShop'])->name('admin.changeShop');

    Route::post('/updateCartItem', [orderController::class, 'updateCartItem']);

    Route::post('/resumeOrder', [orderController::class, 'resumeOrder']);

    Route::post('/updateOrder', [orderController::class, 'updateOrder']);

    Route::get('/updateOrder', [productsController::class, 'newOrder']);

    Route::post('/updQuant', [orderController::class, 'updQuant']);

    Route::post('/updDisc', [orderController::class, 'updDisc']);

    Route::get('/itemRequest', [itemRequestController::class, 'index']);

    Route::get('/viewRequest', [itemRequestController::class, 'viewRequest']);

    Route::post('/itemRequest', [itemRequestController::class, 'itemRequest']);

    Route::post('/removeFromCart', [orderController::class, 'dltProdOrdcart']);

    Route::post('/saveInfos', [orderController::class, 'saveInfo']);
    Route::post('/saveSeller', [orderController::class, 'saveSeller']);

    Route::get('/api/receivings-by-date', [productsController::class, 'getReceivingsByDate']);

    Route::post('/saveOrder', [orderController::class, 'saveOrder']);

    Route::get('/customers', [customerController::class, 'index']);

    Route::post('/newCustomer', [customerController::class, 'addCustomer']);

    Route::post('/editCustomer', [customerController::class, 'editCustomer']);

    Route::get('customerView', [customerController::class, 'customerView']);

    Route::post('/dltCustomer', [customerController::class, 'dltCustomer']);

    Route::post('/debt', [orderController::class, 'debt']);

    Route::get('/ordersList', [orderController::class, 'index']);

    Route::get('/supplier-credit', [vendorController::class, 'supplierCredit']);
    
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

    Route::get('/kpi', [salsController::class, 'kpiDashboard'])->name('kpi');
    Route::get('/customer-kpi', [salsController::class, 'customerKPI'])->name('customer.kpi');

    Route::post('/viewInvoice', [orderController::class, 'viewInvoice']);

    Route::get('/viewSales', [salsController::class, 'viewSales']);

    Route::get('/expenses', [expensesController::class, 'index']);

    Route::post('/expenseInsert', [expensesController::class, 'expenseInsert']);

    Route::post('/expenseDate', [expensesController::class, 'index']);

    Route::match(['get', 'post'], '/saleDate', [salsController::class, 'index'])->name('saleDate');

    Route::get('/settings', [systemController::class, 'index']);

    Route::get('/security', [systemController::class, 'security'])->name('admin.security');

    // System security toggle routes
    Route::post('/toggle-face-recognition', [systemController::class, 'toggleFaceRecognition'])->name('toggle.face.recognition');
    Route::post('/toggle-block-signins', [systemController::class, 'toggleBlockSignins'])->name('toggle.block.signins');
    Route::post('/toggle-system-shutdown', [systemController::class, 'toggleSystemShutdown'])->name('toggle.system.shutdown');

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
    
    Route::match(['get', 'post'], '/customerDebtProducts', [systemController::class, 'customerDebtProducts']);
    
    Route::post('/payInvoiceDebt', [systemController::class, 'payInvoiceDebt']);
    Route::post('/undoInvoiceDebt', [systemController::class, 'undoInvoiceDebt']);
    
    Route::get('/paidInvoices', [systemController::class, 'paidInvoices'])->name('paidInvoices');
    
    // New route for paid invoices
    Route::post('/deletePaidInvoice', [systemController::class, 'deletePaidInvoice'])->name('deletePaidInvoice');
    
    Route::get('/paidInvoices', [systemController::class, 'paidInvoices'])->name('paidInvoices');

    Route::post('/duplicateProducts', [productsController::class, 'duplicateProducts'])->name('duplicateProducts');

    // Offer management routes
    Route::post('/saveOffer', [productsController::class, 'saveOffer'])->name('saveOffer');
    Route::get('/getOffers/{productId}', [productsController::class, 'getOffers'])->name('getOffers');
    Route::get('/allOffers', [productsController::class, 'allOffers'])->name('allOffers');
    Route::get('/getAllOffersApi', [productsController::class, 'getAllOffersApi'])->name('getAllOffersApi');
    Route::post('/deleteOffer', [productsController::class, 'deleteOffer'])->name('deleteOffer');
    Route::get('/checkOffer/{productId}/{quantity}', [productsController::class, 'checkOffer'])->name('checkOffer');
    Route::get('/offeredProductsReport', [productsController::class, 'offeredProductsReport'])->name('offeredProductsReport');
    Route::get('/search-products-for-offer', [productsController::class, 'searchProductsForOffer'])->name('searchProductsForOffer');

    Route::post('/dltExpense', [expensesController::class, 'dltExpense']);


    Route::get('/employees', [userController::class, 'index'])->name('admin.employees');

    Route::post('/registerEmployee', [userController::class, 'registerEmployee'])->name('admin.registerEmployee');

    Route::get('/sales/export', [salsController::class, 'export'])->name('sales.export');

    Route::get("/notifications", [notification::class, 'index']);

    Route::get('/export-product-report', [ReportController::class, 'exportProductReport'])->name('product.report.export');

    Route::get('/export-shop-report', [salsController::class, 'exportShopReport'])->name('export.shop.report');

    Route::post('/sendNotif', [notification::class, 'notification']);

    Route::post('/undoSales', [salsController::class, 'undoSales']);

    Route::get('/searchSales', [salsController::class, 'searchSales']);

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

    Route::get('/employeeView', [userController::class, 'employeeView'])->name('admin.employeeView');

    Route::post('/employeeView', [userController::class, 'employeeView'])->name('admin.employeeView.post');

    Route::post('/updateEmployee', [userController::class, 'updateEmployee'])->name('admin.updateEmployee');

    Route::post('/banUser', [userController::class, 'banUser'])->name('admin.banUser');

    Route::post('/deleteUser', [userController::class, 'deleteUser'])->name('admin.deleteUser');

    Route::post('/changePassword', [userController::class, 'changePassword'])->name('admin.changePassword');

    Route::post('/dltItemReq', [itemRequestController::class, 'dltItemReq']);

    Route::post('/request/approve-all', [itemRequestController::class, 'approveAll'])->name('request.approveAll');

    Route::post('/request/delete', [itemRequestController::class, 'deleteRequest'])->name('request.delete');

    Route::get('/coupons', [couponController::class, 'index']);

    Route::post('dltcoupon', [couponController::class, 'deltCoupon']);

    Route::post('/couponnew', [couponController::class, 'couponnew']);

    Route::get('restock', [productsController::class, 'restock']);

    Route::post('restock', [productsController::class, 'restock']);

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
    
    Route::get('make-return', [productsController::class, 'makeReturn'])->name('make-return');
    Route::post('make-return/process', [productsController::class, 'processReturn'])->name('process-return');

    Route::post('return/approve', [productsController::class, 'approveReturn'])->name('return.approve');
    Route::post('return/reject', [productsController::class, 'rejectReturn'])->name('return.reject');

    Route::get('view-returns', [productsController::class, 'viewReturns']);

    Route::get('receiving-report', [productsController::class, 'receivingReport'])->name('receiving-report');

    Route::get('items-report', [productsController::class, 'itemsReport'])->name('items-report');

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
        Route::get('/banking/supplier-deposit-report', [bankingController::class, 'supplierDepositReport'])->name('admin.banking.supplierDepositReport');
        Route::get('/banking/supplier-deposit-report/export', [bankingController::class, 'exportSupplierDepositReport'])->name('admin.banking.exportSupplierDepositReport');
        Route::get('/banking/get-suppliers-by-shop', [bankingController::class, 'getSuppliersByShop'])->name('admin.banking.getSuppliersByShop');

        // Banking Chips Routes (Admin)
        Route::get('/banking-chips', [bankingController::class, 'chips'])->name('admin.banking-chips');
        Route::post('/banking-chip/store', [bankingController::class, 'storeChip']);
        Route::post('/banking-chip/update/{id}', [bankingController::class, 'updateChip']);
        Route::post('/banking-chip/delete/{id}', [bankingController::class, 'deleteChip']);
    
        Route::get('/vendors', [vendorController::class, 'index']);

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

    Route::post('saveInfo', [itemRequestController::class, 'saveInfo']);

    Route::post('/approveRequest', [itemRequestController::class, 'approveRequest']);

    Route::post('/rejectRequest', [itemRequestController::class, 'rejectRequest']);

    Route::post('/outOfStockRequest', [itemRequestController::class, 'outOfStockRequest']);

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

    // Debug route - temporary
    Route::get('/admin/migration-debug', function() {
        $database = config('database.connections.mysql.database');
        $tables = DB::select("SHOW TABLES FROM `$database`");
        $output = "Database: $database\n";
        $output .= "Total tables: " . count($tables) . "\n\n";
        
        foreach ($tables as $tableObj) {
            $tableName = array_values((array)$tableObj)[0];
            $columns = DB::select("SHOW COLUMNS FROM `$tableName`");
            $hasAccount = false;
            foreach ($columns as $col) {
                if (stripos($col->Field, 'account') !== false) {
                    $hasAccount = true;
                    $output .= "Table: $tableName - Column: {$col->Field} (Type: {$col->Type})\n";
                }
            }
            if ($hasAccount) {
                $output .= "  Sample data:\n";
                $samples = DB::table($tableName)->whereNotNull($col->Field)->limit(3)->pluck($col->Field);
                foreach ($samples as $sample) {
                    $output .= "    - $sample\n";
                }
                $output .= "\n";
            }
        }
        
        return '<pre>' . htmlspecialchars($output) . '</pre>';
    })->name('migration.debug');

    });

});

/*#######################################################################
 user routes starts here
 ########################################################################
 */
Route::middleware(['system.security'])->group(function () {
Route::prefix('user')->name('user.')->group(function () {

Route::get('/login', [validationController::class, 'index'])->name('login');

Route::post('/login', action: [validationController::class, 'login']);

Route::get('/home', fn () => view('home'))->name('home');
    
Route::get('/dashboard', [homeController::class, 'dashboard'])->name('dashboard');

Route::get('/signout', [validationController::class, 'logoutAndRedirect'])->name('signout');

Route::get('/products', [productsController::class, 'index'])->name('products.index');

Route::get('/newProducts', function() {
    return view('user/newProduct');
});

    Route::post('processDebt', [orderController::class, 'debt']);

Route::post('/addProducts', [productsController::class, 'saveProduct'])->withoutMiddleware([\App\Http\Middleware\SystemSecurityMiddleware::class]);

    Route::post('cashSubmit', action: [salsController::class, 'cashSubmit']);
    Route::post('cashDelete', action: [salsController::class, 'cashDelete']);

Route::get('/download-template', [productsController::class, 'downloadTemplate'])->name('downloadTemplate');

Route::post('/viewProduct', [productsController::class, 'viewProduct']);

Route::post('/dltProduct', [productsController::class, 'dltProduct'])->name('dltProduct');

Route::get('/viewProduct', [productsController::class, 'viewProduct']);

Route::post('/updateProducts', action: [productsController::class, 'updateProducts']);

Route::get('/newOrder', [productsController::class, 'newOrder']);

Route::get('/changeShop', [orderController::class, 'changeShop'])->name('user.changeShop');

Route::post('/newOrder', [orderController::class, 'newOrder']);

Route::get('/searchProduct', [productsController::class, 'search']);

Route::get('/searchCustomers', [customerController::class, 'searchCustomer']);

Route::get('/getCustomerDetails', [customerController::class, 'getCustomerDetails']);

Route::get('/searchSellers', [userController::class, 'searchSeller']);

Route::post('/createOrder', [productsController::class, 'createOrder']);

Route::post('/updateOrder', [orderController::class, 'updateOrder']);

Route::get('/updateOrder', [productsController::class, 'newOrder']);

Route::post('/resumeOrder', [orderController::class, 'resumeOrder']);

Route::post('/updateCartItem', [orderController::class, 'updateCartItem']);

Route::get('/fullReport', [salsController::class, 'fullReport']);

Route::get('/shopReport', [salsController::class, 'AllShopReport']);

Route::get('/kpi', [salsController::class, 'kpiDashboard'])->name('kpi');
Route::get('/customer-kpi', [salsController::class, 'customerKPI'])->name('customer.kpi');
Route::get('/customer-kpi', [salsController::class, 'customerKPI'])->name('customer.kpi');

Route::post('/updQuant', [orderController::class, 'updQuant']);

Route::post('/updDisc', [orderController::class, 'updDisc']);

Route::get('/itemRequest', [itemRequestController::class, 'index']);

Route::get('/viewRequest', [itemRequestController::class, 'viewRequest']);

Route::post('/itemRequest', [itemRequestController::class, 'itemRequest']);

Route::post('/dltProdOrd', [orderController::class, 'dltProdOrd']);

Route::post('/dltItemReq', [itemRequestController::class, 'dltItemReq']);

Route::post('/saveInfo', [itemRequestController::class, 'saveInfo']);

Route::post('/saveOrder', [orderController::class, 'saveOrder']);

Route::get('/customers', [customerController::class, 'index'])->name('customer');

Route::post('/newCustomer', [customerController::class, 'addCustomer']);

Route::post('/editCustomer', [customerController::class, 'editCustomer']);

Route::post('/dltCustomer', [customerController::class, 'dltCustomer']);

Route::post('/debt', [orderController::class, 'debt']);

Route::get('/ordersList', [orderController::class, 'index']);

Route::get('/supplier-credit', [vendorController::class, 'supplierCredit']);

Route::post('/supplier-items', [vendorController::class, 'supplierItems']);

Route::post('/supplierPay', [vendorController::class, 'supplierPay']);

Route::get('/report', [salsController::class, 'index']);

Route::get('/ads', [systemController::class, 'ads']);

Route::post('/ads', [systemController::class, 'ads']);
    
Route::get('/viewOrder', [orderController::class, 'viewOrder']);

Route::post('/viewOrder', [orderController::class, 'viewOrder']);

Route::get('/viewOrder', [orderController::class, 'viewOrder']);


Route::post('/discount', [orderController::class, 'discount']);

Route::post('/coupon', [orderController::class, 'coupon']);

    Route::get('/sales', [salsController::class, 'index']);

Route::post('/viewSales', [salsController::class, 'viewSales']);

Route::post('/viewInvoice', [orderController::class, 'viewInvoice']);

Route::get('/viewSales', [salsController::class, 'viewSales']);

Route::post('/undoSales', [salsController::class, 'undoSales']);

Route::get('/searchSales', [salsController::class, 'searchSales']);

Route::get('/getSalesDates', [salsController::class, 'getSalesDates']);

// Check today's balance
Route::get('/check-today-balance', [salsController::class, 'checkTodayBalance'])->name('check.today.balance');

// Balance Check & Discrepancy Routes (User)
Route::prefix('balance-check')->name('balance-check.')->group(function () {
    Route::get('/discrepancies/{shopId}/{date}', [App\Http\Controllers\BalanceCheckController::class, 'showDiscrepancies'])->name('discrepancies');
    Route::get('/discrepancy/{id}', [App\Http\Controllers\BalanceCheckController::class, 'showDiscrepancyDetail'])->name('discrepancy.detail');
});

Route::get('/expenses', [expensesController::class, 'index']);

Route::post('/expenseInsert', [expensesController::class, 'expenseInsert']);

Route::post('/dltExpense', [expensesController::class, 'dltExpense']);

Route::post('/expenseDate', [expensesController::class, 'index']);

    Route::post('/removeFromCart', [orderController::class, 'dltProdOrdcart']);

Route::match(['get', 'post'], '/saleDate', [salsController::class, 'index'])->name('saleDate');

Route::get('/settings', [systemController::class, 'index']);

Route::post('/personalData', [systemController::class, 'personalData']);
Route::post('/businessDetails', [systemController::class, 'businessDetails']);
Route::post('/newAccount', [systemController::class, 'newAccount']);
Route::post('/updateAccountProducts', [systemController::class, 'updateAccountProducts']);
Route::get('/getAccountProducts/{accountId}', [systemController::class, 'getAccountProducts']);
Route::get('/getAllProducts', [systemController::class, 'getAllProducts']);
Route::post('/deleteAccount', [systemController::class, 'deleteAccount']);
Route::post('/updateAccount', [systemController::class, 'updateAccount']);
Route::post('/switch', [systemController::class, 'switchAccount']);

Route::get('/getAllAccounts', [systemController::class, 'getAllAccounts']);

Route::get('/allInvoices', [systemController::class, 'allInvoices']);

// Manual invoice creation for users
Route::post('/createManualInvoice', [systemController::class, 'createManualInvoice'])->name('createManualInvoice');

// New routes for shop invoices with debts
Route::get('/shopInvoices', [systemController::class, 'shopInvoices']);

Route::get('/shopDebtors/{shopId}', [systemController::class, 'shopDebtors']);

Route::match(['get', 'post'], '/customerDebtProducts', [systemController::class, 'customerDebtProducts']);

Route::post('/payInvoiceDebt', [systemController::class, 'payInvoiceDebt']);
Route::post('/undoInvoiceDebt', [systemController::class, 'undoInvoiceDebt']);

// New route for paid invoices
Route::get('/paidInvoices', [systemController::class, 'paidInvoices'])->name('paidInvoices');

// Manual invoice creation
Route::post('/createManualInvoice', [systemController::class, 'createManualInvoice'])->name('createManualInvoice');

Route::post('/duplicateProducts', [productsController::class, 'duplicateProducts'])->name('duplicateProducts');

// Offer management routes
Route::post('/saveOffer', [productsController::class, 'saveOffer'])->name('saveOffer');
Route::get('/getOffers/{productId}', [productsController::class, 'getOffers'])->name('getOffers');
Route::get('/allOffers', [productsController::class, 'allOffers'])->name('allOffers');
Route::get('/getAllOffersApi', [productsController::class, 'getAllOffersApi'])->name('getAllOffersApi');
Route::post('/deleteOffer', [productsController::class, 'deleteOffer'])->name('deleteOffer');
Route::get('/checkOffer/{productId}/{quantity}', [productsController::class, 'checkOffer'])->name('checkOffer');
Route::get('/offeredProductsReport', [productsController::class, 'offeredProductsReport'])->name('offeredProductsReport');
Route::get('/search-products-for-offer', [productsController::class, 'searchProductsForOffer'])->name('searchProductsForOffer');

Route::get('/employees', [userController::class, 'index']);

Route::post('/registerEmployee', [userController::class, 'registerEmployee']);

Route::get('/sales/export', [salsController::class, 'export'])->name('sales.export');

Route::get("/notifications", [notification::class, 'index']);

Route::get('/export-product-report', [ReportController::class, 'exportProductReport'])->name('product.report.export');

Route::post('/sendNotif', [notification::class, 'notification']);

Route::post('/deleteNotif', [notification::class, 'delete']);

Route::get('/employeeView', [userController::class, 'employeeView'])->name('user.employeeView');
Route::post('/employeeView', [userController::class, 'employeeView'])->name('user.employeeView.post');

Route::post('/updateEmployee', [userController::class, 'updateEmployee'])->name('user.updateEmployee');

Route::post('/banUser', [userController::class, 'banUser'])->name('user.banUser');

Route::post('/deleteUser', [userController::class, 'deleteUser'])->name('user.deleteUser');

Route::post('/changePassword', [userController::class, 'changePassword'])->name('user.changePassword');

Route::get('/coupons', [couponController::class, 'index']);

Route::post('dltcoupon', [couponController::class, 'deltCoupon']);

Route::post('/couponnew', [couponController::class, 'couponnew']);

Route::get('/stock-report', [productsController::class, 'report']);

Route::get('restock', [productsController::class, 'restock']);

Route::post('restock', [productsController::class, 'restock']);

// New routes for separate receiving and return management
Route::get('make-receiving', [productsController::class, 'makeReceiving'])->name('make-receiving');
Route::post('make-receiving/process', [productsController::class, 'processReceiving'])->name('process-receiving');
    Route::get('view-receivings', [productsController::class, 'viewReceivings'])->name('view-receivings');

    // Allocation display routes (for makeReceiving)
    Route::get('/get-supplier-users', [salsController::class, 'getSupplierUsers'])->name('get-supplier-users');
    Route::get('/get-user-suppliers', [salsController::class, 'getUserSuppliers'])->name('get-user-suppliers');

    Route::get('make-return', [productsController::class, 'makeReturn'])->name('make-return');

Route::post('approve-selected-receivings', [productsController::class, 'approveSelectedReceivings'])->name('approve-selected-receivings');
Route::post('approve-all-receivings', [productsController::class, 'approveAllReceivings'])->name('approve-all-receivings');
Route::post('approve-all-receivings-all-dates', [productsController::class, 'approveAllReceivingsAllDates'])->name('approve-all-receivings-all-dates');
Route::post('undo-receivings', [productsController::class, 'undoReceivings'])->name('undo-receivings');
Route::post('delete-selected-receivings', [productsController::class, 'deleteSelectedReceivings'])->name('delete-selected-receivings');

Route::get('view-receivings', [productsController::class, 'viewReceivings'])->name('view-receivings');

Route::get('make-return', [productsController::class, 'makeReturn'])->name('make-return');
Route::post('make-return/process', [productsController::class, 'processReturn'])->name('process-return');

Route::get('view-returns', [productsController::class, 'viewReturns']);

Route::get('receiving-report', [productsController::class, 'receivingReport'])->name('receiving-report');

Route::get('items-report', [productsController::class, 'itemsReport'])->name('items-report');

Route::get('/stock.report', [productsController::class, 'report']);

Route::get('/suppliers', [supplier::class, 'index']);
    Route::post('/saveInfos', [orderController::class, 'saveInfo']);
    Route::post('/saveSeller', [orderController::class, 'saveSeller']);

    // Banking Routes (User)
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

    // Banking Transfers Routes (User)
    Route::get('/banking-transfers', [bankingController::class, 'transfers']);
    Route::post('/banking-transfer/store', [bankingController::class, 'storeTransfer']);
    Route::post('/banking-transfer/delete/{id}', [bankingController::class, 'deleteTransfer']);

    // Banking Chips Routes (User)
    Route::get('/banking-chips', [bankingController::class, 'chips']);
    Route::post('/banking-chip/store', [bankingController::class, 'storeChip']);
    Route::post('/banking-chip/update/{id}', [bankingController::class, 'updateChip']);
    Route::post('/banking-chip/delete/{id}', [bankingController::class, 'deleteChip']);
    
    // Supplier Deposit Report (User)
    Route::get('/banking/supplier-deposit-report', [bankingController::class, 'supplierDepositReport'])->name('user.banking.supplierDepositReport');
    Route::get('/banking/supplier-deposit-report/export', [bankingController::class, 'exportSupplierDepositReport'])->name('user.banking.exportSupplierDepositReport');
    Route::get('/banking/get-suppliers-by-shop', [bankingController::class, 'getSuppliersByShop'])->name('user.banking.getSuppliersByShop');
        




Route::get('/vendors', [vendorController::class, 'index']);

Route::post('newVendor', [vendorController::class, 'newVendor']);

Route::post('returnStock', [productsController::class, 'returnStock']);

Route::post('dltrestock', [productsController::class, 'dltrestock']);

Route::post('dltVendeor', [vendorController::class, 'dltVendeor']);

Route::post('restockProd', [productsController::class, 'restockProd']);

Route::post('payout', [orderController::class, 'payout']);

Route::post('requpdQuant', [itemRequestController::class, 'updQuant']);

Route::post('requestSubmit', action: [itemRequestController::class, 'payout']);

Route::post('reqdltProdOrd', [itemRequestController::class, 'dltProdOrd']);

Route::post('saveInfo', [itemRequestController::class, 'saveInfo']);

Route::post('/approveRequest', [itemRequestController::class, 'approveRequest']);

Route::post('/rejectRequest', [itemRequestController::class, 'rejectRequest']);

Route::post('/outOfStockRequest', [itemRequestController::class, 'outOfStockRequest']);

Route::post('processPayment', [orderController::class, 'debt']);

Route::get('/details/{id}', [customerController::class, 'details']);

    Route::post('returnToMainStore', [productsController::class, 'returnToMainStore']);
Route::post('newAccount', [systemController::class, 'newAccount']);

Route::get('logs', [logController::class, 'index']);

Route::post('viewVendor', [vendorController::class, 'viewVendor']);

Route::post('madeniPay', [productsController::class, 'madeni']);

Route::post('deleteDebt', [orderController::class, 'deleteDebt']);

Route::match(['get', 'post'], 'customerView', [customerController::class, 'customerView']);

Route::get('/customer-kpi', [salsController::class, 'customerKPI'])->name('customer.kpi');

Route::get('/security', [systemController::class, 'security'])->name('security');


Route::get('/getAccountProducts/{accountId}', [systemController::class, 'getAccountProducts']);

Route::post('updateAccountProducts', [systemController::class, 'updateAccountProducts']);
});

});

});

