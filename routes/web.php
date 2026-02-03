<?php
use App\Exports\MonthlyReportExport;
use App\Http\Controllers\couponController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\logController;
use App\Http\Controllers\supplier;
use App\Http\Controllers\vendorController;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\orderController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\salsController;
use App\Http\Controllers\systemController;
use App\Http\Controllers\userController;
use App\Http\Controllers\validationController;
use App\Models\expensesModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homeController;
use App\Http\Controllers\expensesController;
use App\Http\Controllers\notification;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\itemRequestController;
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

Route::post('/login', action: [validationController::class, 'login'])->name('login');

//Admin group starts here

Route::middleware(['auth'])->group(function () {

 Route::prefix('admin')->name('admin.')->group(function () {
     
Route::get('/home', fn () => view('home'))->name('home');
    
Route::get('/dashboard', [homeController::class, 'dashboard'])->name('dashboard');

Route::get('/signout', [validationController::class, 'logoutAndRedirect'])->name('signout');


    Route::get('/products', [productsController::class, 'index'])->name('products');

    Route::get('/newProducts', function() {
        return view('admin/newProduct');
    });

    Route::post('/addProducts', [productsController::class, 'saveProduct']);

    Route::post('/viewProduct', [productsController::class, 'viewProduct']);

    Route::post('/dltProduct', [productsController::class, 'dltProduct']);

    Route::get('/viewProduct', [productsController::class, 'viewProduct']);

    Route::post('/updateProducts', action: [productsController::class, 'updateProducts']);

    Route::get('/newOrder', [productsController::class, 'newOrder']);

    Route::get('/searchProduct', [productsController::class, 'search']);

    Route::post('/newOrder', [orderController::class, 'newOrder']);

    Route::post('/updateCartItem', [orderController::class, 'updateCartItem']);

    Route::post('/resumeOrder', [orderController::class, 'resumeOrder']);

    Route::post('/updateOrder', [orderController::class, 'updateOrder']);

    Route::get('/updateOrder', [productsController::class, 'newOrder']);

    Route::post('/updQuant', [orderController::class, 'updQuant']);

    Route::post('/updDisc', [orderController::class, 'updDisc']);

    Route::get('/itemRequest', [itemRequestController::class, 'index']);

    Route::get('/viewRequest', [itemRequestController::class, 'viewRequest']);

    Route::post('/itemRequest', [itemRequestController::class, 'itemRequest']);

    Route::post('/removeFromCart', [orderController::class, 'dltProdOrd']);

    Route::post('/saveInfos', [orderController::class, 'saveInfo']);

    Route::post('/saveOrder', [orderController::class, 'saveOrder']);

    Route::get('/customers', [customerController::class, 'index']);

    Route::post('/newCustomer', [customerController::class, 'addCustomer']);

    Route::post('/editCustomer', [customerController::class, 'editCustomer']);

    Route::get('customerView', [customerController::class, 'index']);

    Route::post('/dltCustomer', [customerController::class, 'dltCustomer']);

    Route::post('/debt', [orderController::class, 'debt']);

    Route::get('/ordersList', [orderController::class, 'index']);

    Route::get('/deptors', [orderController::class, 'deptors']);

    Route::get('/sales', [salsController::class, 'index']);

    Route::get('/viewOrder', [orderController::class, 'viewOrder']);

    Route::post('/viewOrder', [orderController::class, 'viewOrder']);

    Route::post('/discount', [orderController::class, 'discount']);

    Route::post('/coupon', [orderController::class, 'coupon']);

    Route::post('/viewSales', [salsController::class, 'viewSales']);

    Route::get('/fullReport', [salsController::class, 'fullReport']);

    Route::post('/viewInvoice', [orderController::class, 'viewInvoice']);

    Route::get('/viewSales', [salsController::class, 'viewSales']);

    Route::get('/expenses', [expensesController::class, 'index']);

    Route::post('/expenseInsert', [expensesController::class, 'expenseInsert']);

    Route::post('/expenseDate', [expensesController::class, 'index']);

    Route::post('/saleDate', [salsController::class, 'index'])->name('saleDate');

    Route::get('/settings', [systemController::class, 'index']);

    Route::post('/personalData', [systemController::class, 'personalData']);

    Route::post('/upload-profile-picture', [systemController::class, 'uploadProfilePicture']);

    Route::post('/businessDetails', [systemController::class, 'businessDetails']);

    Route::post('/newAccount', [systemController::class, 'newAccount']);

    Route::post('/updateAccountProducts', [systemController::class, 'updateAccountProducts']);

    Route::get('/getAccountProducts/{accountId}', [systemController::class, 'getAccountProducts']); // Fixed route

    Route::get('/getAllProducts', [systemController::class, 'getAllProducts']);

    Route::post('/deleteAccount', [systemController::class, 'deleteAccount']);

    Route::post('/switch', [systemController::class, 'switchAccount']);

    Route::get('/getAllAccounts', [systemController::class, 'getAllAccounts']);

    Route::post('/duplicateProducts', [productsController::class, 'duplicateProducts'])->name('duplicateProducts');

    Route::get('/employees', [userController::class, 'index']);

    Route::post('/registerEmployee', [userController::class, 'registerEmployee']);

    Route::get('/sales/export', [salsController::class, 'export'])->name('sales.export');

    Route::get("/notifications", [notification::class, 'index']);

    Route::get('/export-product-report', [ReportController::class, 'exportProductReport'])->name('product.report.export');

    Route::post('/sendNotif', [notification::class, 'notification']);

    Route::post('/deleteNotif', [notification::class, 'delete']);

    Route::get('/employeeView', [userController::class, 'employeeView']);

    Route::post('/employeeView', [userController::class, 'employeeView'])->name('employeeView');

    Route::post('/updateEmployee', [userController::class, 'updateEmployee']);

    Route::post('/banUser', [userController::class, 'banUser']);

    Route::post('/deleteUser', [userController::class, 'deleteUser']);

    Route::post('/dltItemReq', [itemRequestController::class, 'dltItemReq']);

    Route::post('/request/approve-all', [itemRequestController::class, 'approveAll'])->name('request.approveAll');

    Route::get('/coupons', [couponController::class, 'index']);

    Route::post('dltcoupon', [couponController::class, 'deltCoupon']);

    Route::post('/couponnew', [couponController::class, 'couponnew']);

    Route::get('restock', [productsController::class, 'restock']);

    Route::post('restock', [productsController::class, 'restock']);

    Route::get('/stock-report', [productsController::class, 'report']);

    Route::get('/supplier', [supplier::class, 'index']);

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

    Route::post('cashSubmit', action: [salsController::class, 'cashSubmit']);

    Route::post('processDebt', [orderController::class, 'debt']);

    Route::get('/details/{id}', [customerController::class, 'details']);

    Route::post('newAccount', [systemController::class, 'newAccount']);

    Route::get('logs', [logController::class, 'index']);

    Route::post('viewVendor', [vendorController::class, 'viewVendor']);

    Route::post('madeniPay', [productsController::class, 'madeni']);

    Route::post('deleteDebt', [orderController::class, 'deleteDebt']);

    Route::post('customerView', [customerController::class, 'customerView']);

    Route::get('/security', [systemController::class, 'security'])->name('security');

    Route::get('/getAccountProducts/{accountId}', [systemController::class, 'getAccountProducts']);

    Route::post('updateAccountProducts', [systemController::class, 'updateAccountProducts']);
});

/*#######################################################################
 user routes starts here
 ########################################################################
*/
 Route::prefix('user')->name('user.')->group(function () {

Route::get('/login', [validationController::class, 'index'])->name('login');

Route::post('/login', action: [validationController::class, 'login']);

Route::get('/home', fn () => view('home'))->name('home');
    
Route::get('/dashboard', [homeController::class, 'dashboard'])->name('dashboard');

Route::get('/signout', [validationController::class, 'logoutAndRedirect'])->name('signout');

Route::get('/products', [productsController::class, 'index'])->name('products');

Route::get('/newProducts', function() {
    return view('user/newProduct');
});

Route::post('/addProducts', [productsController::class, 'saveProduct']);

Route::post('/viewProduct', [productsController::class, 'viewProduct']);

Route::post('/dltProduct', [productsController::class, 'dltProduct']);

Route::get('/viewProduct', [productsController::class, 'viewProduct']);

Route::post('/updateProducts', action: [productsController::class, 'updateProducts']);

Route::get('/newOrder', [productsController::class, 'newOrder']);

Route::get('/searchProduct', [productsController::class, 'search']);

Route::post('/createOrder', [productsController::class, 'createOrder']);

Route::post('/updateOrder', [orderController::class, 'updateOrder']);

Route::get('/updateOrder', [productsController::class, 'newOrder']);

Route::post('/updQuant', [orderController::class, 'updQuant']);

Route::post('/updDisc', [orderController::class, 'updDisc']);

Route::get('/itemRequest', [itemRequestController::class, 'index']);

Route::get('/viewRequest', [itemRequestController::class, 'viewRequest']);

Route::post('/itemRequest', [itemRequestController::class, 'itemRequest']);

Route::post('/dltProdOrd', [orderController::class, 'dltProdOrd']);

Route::post('/saveInfo', [orderController::class, 'saveInfo']);

Route::post('/saveOrder', [orderController::class, 'saveOrder']);

Route::get('/customers', [customerController::class, 'index'])->name('customers');

Route::post('/newCustomer', [customerController::class, 'addCustomer']);

Route::post('/editCustomer', [customerController::class, 'editCustomer']);

Route::post('/dltCustomer', [customerController::class, 'dltCustomer']);

Route::post('/debt', [orderController::class, 'debt']);

Route::get('/ordersList', [orderController::class, 'index']);

Route::get('/deptors', [orderController::class, 'deptors']);

Route::get('/report', [salsController::class, 'index']);

Route::get('/viewOrder', [orderController::class, 'viewOrder']);

Route::post('/viewOrder', [orderController::class, 'viewOrder']);

Route::get('/viewOrder', [orderController::class, 'viewOrder']);


Route::post('/discount', [orderController::class, 'discount']);

Route::post('/coupon', [orderController::class, 'coupon']);

Route::post('/viewSales', [salsController::class, 'viewSales']);

Route::post('/viewInvoice', [orderController::class, 'viewInvoice']);

Route::get('/viewSales', [salsController::class, 'viewSales']);

Route::get('/expenses', [expensesController::class, 'index']);

Route::post('/expenseInsert', [expensesController::class, 'expenseInsert']);

Route::post('/expenseDate', [expensesController::class, 'index']);

Route::post('/saleDate', [salsController::class, 'index'])->name('saleDate');

Route::get('/settings', [systemController::class, 'index']);

Route::post('/personalData', [systemController::class, 'personalData']);
Route::post('/businessDetails', [systemController::class, 'businessDetails']);
Route::post('/newAccount', [systemController::class, 'newAccount']);
Route::post('/updateAccountProducts', [systemController::class, 'updateAccountProducts']);
Route::get('/getAccountProducts/{accountId}', [systemController::class, 'getAccountProducts']); // Fixed route
Route::get('/getAllProducts', [systemController::class, 'getAllProducts']);
Route::post('/deleteAccount', [systemController::class, 'deleteAccount']);
Route::post('/switch', [systemController::class, 'switchAccount']);

Route::get('/getAllAccounts', [systemController::class, 'getAllAccounts']);

Route::post('/duplicateProducts', [productsController::class, 'duplicateProducts'])->name('duplicateProducts');

Route::get('/employees', [userController::class, 'index']);

Route::post('/registerEmployee', [userController::class, 'registerEmployee']);

Route::get('/sales/export', [salsController::class, 'export'])->name('sales.export');

Route::get("/notifications", [notification::class, 'index']);

Route::get('/export-product-report', [ReportController::class, 'exportProductReport'])->name('product.report.export');

Route::post('/sendNotif', [notification::class, 'notification']);

Route::post('/deleteNotif', [notification::class, 'delete']);

Route::get('/employeeView', [userController::class, 'employeeView']);
Route::post('/employeeView', [userController::class, 'employeeView'])->name('employeeView');

Route::post('/updateEmployee', [userController::class, 'updateEmployee']);

Route::post('/banUser', [userController::class, 'banUser']);

Route::post('/deleteUser', [userController::class, 'deleteUser']);

Route::get('/coupons', [couponController::class, 'index']);

Route::post('dltcoupon', [couponController::class, 'deltCoupon']);

Route::post('/couponnew', [couponController::class, 'couponnew']);

Route::get('restock', [productsController::class, 'restock']);

Route::post('restock', [productsController::class, 'restock']);

Route::get('/stock.report', [productsController::class, 'report']);

Route::get('/suppliers', [supplier::class, 'index']);

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

Route::post('newAccount', [systemController::class, 'newAccount']);

Route::get('logs', [logController::class, 'index']);

Route::post('viewVendor', [vendorController::class, 'viewVendor']);

Route::post('madeniPay', [productsController::class, 'madeni']);

Route::post('deleteDebt', [orderController::class, 'deleteDebt']);

Route::post('customerView', [customerController::class, 'customerView']);

Route::get('/security', [systemController::class, 'security'])->name('security');

Route::get('/getAccountProducts/{accountId}', [systemController::class, 'getAccountProducts']);

Route::post('updateAccountProducts', [systemController::class, 'updateAccountProducts']);
    });
    });