<?php

namespace App\Http\Controllers;

use App\Models\customerModel;
use App\Models\stock;
use App\Models\usersModel;
use App\Models\vendorModal;
use Illuminate\Http\Request;
use App\Models\ordersModel;
use App\Models\productsModel;
use App\Models\salsModel;
use App\Models\couponModel;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use App\Models\logModal;
use App\Models\madeni;
use Illuminate\Support\Facades\Auth;
use App\Models\debtsModel;

class orderController extends Controller
{
 public function index()
{
    $user = Auth::user();

    $orders = ordersModel::where('orderName', '!=', '')
    ->where('account', session('account'))
    ->whereIn('status', ['Debt', 'Partial'])
    ->selectRaw('
        cName,
        MAX(cPhone) as cPhone,
        MAX(status) as status,
        SUM(credit) as credit,
        MAX(created_at) as last_order_date
    ')
    ->groupBy('cName')
    ->orderByDesc('last_order_date')
    ->get();

  $paid = ordersModel::where('orderName', '!=', '')
    ->where('account', session('account'))
    ->whereIn('status', ['Paid'])
    ->selectRaw('
        cName,
        MAX(cPhone) as cPhone,
        MAX(status) as status,
        SUM(credit) as credit,
        MAX(created_at) as last_order_date
    ')
    ->groupBy('cName')
    ->orderByDesc('last_order_date')
    ->get();

               $data = compact(
        'orders','paid'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.ordersList', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.ordersList', $data);
    }
}
    
    public function deptors()
    {
        $user = Auth::user();

        $orders = madeni::where('account', session('account'))
                          ->get();
        $fetch = null;
        foreach( $orders as $order) {
            $fetch = vendorModal::where('id', $order->master)->first();
        }

                     $data = compact(
        'orders','fetch'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.deptors', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.deptors', $data);
    }
    }

   public function saveInfo(Request $req)
{
    $cname = $req->input('Cname');
    $cphone = $req->input('Cphone');
    $orderId = $req->input('orderId');

    if (empty($cname)) {
    $selected = $req->input('selectedCustomer'); // e.g., "John Doe|123456789"
    if (!empty($selected)) {
        list($cname, $cphone) = explode('|', $selected);
    }
}

    if (empty($orderId)) {
        return redirect()->back()->with('error', 'No order specified');
    }

    // Update all orders with the same order_id
    $updated = ordersModel::where('account', session('account'))->where('order_id', $orderId)
                ->update([
                    'cName' => $cname,
                    'cPhone' => $cphone
                ]);
  

    if ($updated) {
        return redirect()->back()->with('success', 'Customer info saved');
        
    } else {
        return redirect()->back()->with('error', 'Failed to save Customer info (maybe no records matched)');
    }
}

   public function newOrder(Request $req)
{
  $orderType = $req->input('orderType', '');
$pId = $req->input('pId');

    if (empty($pId)) {
        return redirect()->back()->with('success', 'Add a product to create an order');
    }


    // Check for existing active order
    $activeOrder = ordersModel::where('account', session('account'))->where('served_by', session('username'))
        ->whereNotIn('status', ['Debt', 'Partial', 'Suspended'])
        ->orderBy('id', 'desc')
        ->first();

    if ($activeOrder) {
        $OrdersIds = $activeOrder->order_id;
        $OrdersNames = $activeOrder->orderName;
    } else {
        
    // Generate random customer name if empty
    $cName = empty($cName) ? 'Customer-' . strtoupper(Str::random(5)) : $cName;

        $OrdersIds = Uuid::uuid4();
        $OrdersNames =
    salsModel::distinct('sales_id')->count('sales_id')
  + ordersModel::where('status', 'Suspended')->distinct('order_id')->count('order_id')
  + 1;

    }

    $userName = session('username');
    $userId = usersModel::where('account', session('account'))->where('name', $userName)->first();
    $check = productsModel::where('account', session('account'))->where('id', $pId)->first();

    if (!$check || $check->quantity <= 0) {
        $this->handleLowStock($check->product_id);
        return redirect()->back()->with('error', 'Product is not available in stock');
    }

    $stoc = stock::where('account', session('account'))->
    where('productId', $check->product_id)
               ->where('quantity', '>', 'sQuantity')
               ->where('bPrice', $check->bPrice)
               ->where('sPrice', $check->sPrice)
               ->orderBy('id', 'asc')
               ->first();

               if(!$stoc) {
                return redirect()->back()->with('error', 'Stock Not found');
               }
        $quantity = 1;
        $totalAmount = ($check->sPrice * $quantity);

    $this->createOrderRecord(
        $OrdersIds,
        $OrdersNames,
        $stoc->name,
        $check->product_id,
        $quantity,
        $check->sPrice,
        $totalAmount,
        Auth::user()->name ?? 'Unknown',
        $orderType,
        account: session('account')
    );

    if ($stoc) {
        $this->updateStock($stoc, $check, $quantity, $totalAmount);
    }

    $this->reduceProductQuantity($pId, $quantity);

    $create = new logModal();
            $create->title = 'Order Logs';
            $create->description = $OrdersNames.'(OrderId) Order Created By '.session('username');
            $create->save();
            

    return redirect()->back()->with('success', 'Order Placed Successfully');
}
public function updateCartItem(Request $req)
{
    $orderId = $req->input('orderId');
    $pId     = $req->input('pId');
    $field   = $req->input('field');
    $value   = (float) $req->input('value');

    if (!in_array($field, ['pQuantity', 'discount'])) {
        return response()->json(['error' => 'Invalid field'], 422);
    }

    $product = productsModel::where('account', session('account'))
        ->where('product_id', $pId)
        ->first();

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    $cartItem = ordersModel::where('productId', $pId)
        ->where('order_id', $orderId)
        ->first();

    if (!$cartItem) {
        return response()->json(['error' => 'Cart item not found'], 404);
    }

    if ($field === 'discount' && $value > $product->discount) {
        return response()->json(['error' => 'Discount exceeds limit'], 400);
    }

    if ($field === 'pQuantity' && $value > $product->quantity) {
        return response()->json(['error' => 'Insufficient stock'], 400);
    }

    $cartItem->$field = $value;

    // Recalculate total price correctly
    $price    = $cartItem->productPrice ?? 1;
    $quantity = $cartItem->pQuantity;
    $discount = $cartItem->discount ?? 0;

    $cartItem->totalPrice = ($price * $quantity) - $discount;

    $cartItem->save();

    return response()->json(['success' => true]);
}


public function resumeOrder(Request $req)
{
    $orderId = $req->input('orderId');

    $orders = ordersModel::where('order_id', $orderId)
        ->where('status', 'Suspended')
        ->get();

    if ($orders->isEmpty()) {
        return redirect()->back()->with('error', 'Order Not Found or Not Suspended');
    }

    foreach ($orders as $order) {
        $order->status = 'Sell';
        $order->save();
    }

    logModal::create([
        'title' => 'Order Logs',
        'description' => $orders->first()->orderName .
            ' (OrderId) Order Resumed By ' . session('username'),
    ]);

    return redirect()->back()->with('success', 'Order Resumed Successfully');
}


    private function handleLowStock($productId)
    {
        $restock = productsModel::where('account', session('account'))->where('product_id', $productId)->first();
        if ($restock && $restock->stock2 > 0) {
            $restock->quantity += $restock->stock2;
            $restock->bPrice += $restock->stock2_bprice;
            $restock->sPrice += $restock->stock2_sprice;
            $restock->stock2 = 0;
            $restock->stock2_bprice = 0;
            $restock->stock2_sprice = 0;
            $restock->save();
        }
    }

    private function createOrderRecord(
    $orderId, $orderName, $stockId, 
    $productId, $pQuantity, $pPrice, $tPrice, $servedBy, $orderType, $account
)
 {
 ordersModel::create([ 
    'order_id' => $orderId,
    'stockId' => $stockId,
    'orderName' => $orderName,
    'productId' => $productId,
    'pQuantity' => $pQuantity,
    'productPrice' => $pPrice ?? 0,
    'totalPrice' => $tPrice,
    'served_by' => $servedBy,
    'status' => $orderType,
    'account' => $account
]);

    }

    private function updateStock($stock, $product, $quantity, $price)
    {
        $getbp = $product->bPrice * $quantity;
        $getsp = $price * $quantity;
        $profit = $getsp - $getbp;

        $stock->sQuantity += $quantity;
        $stock->amount += $getsp;
        $stock->profit += $profit;
        $stock->save();

        if($stock) {

             $create = new logModal();
            $create->title = 'Stock Logs';
            $create->description = $quantity.'(Qty) Stock Updated By '.session('username');
            $create->save();
        }
    }

    private function reduceProductQuantity($productId, $quantity)
    {
        $reduce = productsModel::where('account', session('account'))->where('product_id', $productId)->first();
        if ($reduce) {
            $reduce->quantity -= $quantity;
            $reduce->save();

            $create = new logModal();
            $create->title = 'Product Log';
            $create->description = $productId .' (Product) deducted tp '. $reduce->quantity .'  Successfully By '.session('username');
            $create->save();
        }
    }

    public function updateOrder(Request $req)
    {
        $user = Auth::user();
        $OrderName = $req->input('OrderName');
        $orders = ordersModel::where('account', session('account'))->where('orderName', $OrderName)->first(); 
       
                     $data = compact(
        'newOrder','orders'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.newOrder', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.newOrder', $data);
    }
    }

      public function updQuant(Request $req)
    {
        $OrdersIds = $req->input('OrdersIds');
        $prodId = $req->input('prodId');
        $prodQuantit = $req->input('prodQuantity');
        
        $look = ordersModel::where('account', session('account'))->where('order_id', $OrdersIds)
                               ->where('productId', $prodId)
                               ->first();
       $pr =  $look->productPrice;

       $look->pQuantity = $prodQuantit; 
       $look->totalPrice = ($prodQuantit * $pr);
       $look->save();


        $create = new logModal();
            $create->title = 'Product Logs';
            $create->description = $look->name01.' Product Updated By '.session('username');
            $create->save();

        return redirect()->back()->with('success', 'Product Quantity Updated Successfully');
    }
      public function updDisc(Request $req)
    {
        $OrdersIds = $req->input('OrdersIds');
        $prodId = $req->input('prodId');
        $prodQuantit = $req->input('discAmount');
        
        $look = ordersModel::where('account', session('account'))->where('order_id', $OrdersIds)
                               ->where('productId', $prodId)
                               ->first();

       $look->discount = $prodQuantit;
       $look->save();

        $create = new logModal();
            $create->title = 'Product Logs';
            $create->description = $look->name01.' Product Updated By '.session('username');
            $create->save();
            
        return redirect()->back()->with('success', 'Product Discount Updated Successfully');
    }

    public function dltProdOrd(Request $req)
    {
        $OrdersIds = $req->input('orderId');
        $prodId = $req->input('itemId');
        $prodQuantit = $req->input('prodQuantity');
        
        $deltProduct = ordersModel::where('account', session('account'))->where('order_id', $OrdersIds)
                               ->where('productId', $prodId)
                               ->first();

        if (!$deltProduct) {
            return redirect()->back()->with('error', 'Product Not Found');
        }

        if($deltProduct) {

        $products = productsModel::where('account', session('account'))->where('product_id', $prodId)->first();
$products->quantity += $deltProduct->pQuantity;
$products->save();
        }
        $this->restoreProductQuantity($prodId, $prodQuantit);
        $this->reverseStockUpdate($deltProduct);

        $deltProduct->delete();

        

        $create = new logModal();
            $create->title = 'Product Logs';
            $create->description = $deltProduct->name01.' Product Deleted By '.session('username');
            $create->save();
        return redirect()->back()->with('success', 'Product Deleted Successfully');
    }

    private function restoreProductQuantity($productId, $quantity)
    {
        $updt = productsModel::where('account', session('account'))->where('product_id', $productId)->first();
        if ($updt) {
            $updt->quantity += $quantity;

            $create = new logModal();
            $create->title = 'Product Logs';
            $create->description = $updt->name01.' Product Quantity Restored '. $quantity .' By '.session('username');
            $create->save();
            $updt->save();
        }
    }

    private function reverseStockUpdate($order)
    {
        $stoc = stock::where('account', session('account'))->where('productId', $order->productId)
                   ->where('name', $order->stockId)
                   ->first();

        if ($stoc) {
            $stoc->profit -= (($stoc->sPrice * $stoc->sQuantity) - ($stoc->bPrice * $stoc->sQuantity));
            $stoc->sQuantity -= $order->pQuantity;
            $stoc->amount -= $order->totalPrice;

             $create = new logModal();
            $create->title = 'Stock Logs';
            $create->description = $stoc->name.' Stock Restored By '.session('username');
            $create->save();
            $stoc->save();
        }
    }

 public function debt(Request $req)
{
    $customerId = $req->input('customerId');
    $amount     = (float) $req->input('paymentAmount');

    if (!$customerId || $amount <= 0) {
        return back()->with('error', 'Invalid customer or amount');
    }

    $userName = session('username');

    DB::transaction(function () use ($customerId, &$amount, $userName) {

        $orders = ordersModel::where('account', session('account'))
            ->where('cPhone', $customerId)
            ->whereIn('status', ['Debt', 'Partial'])
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($orders as $order) {

            if ($amount <= 0) break;

            $paidSoFar = debtsModel::where('orderId', $order->order_id)
                ->where('debtId', $order->id)
                ->sum('amount');

            $remainingDebt = $order->credit - $paidSoFar;

            if ($remainingDebt <= 0) continue;

            $payNow = min($amount, $remainingDebt);

            debtsModel::create([
                'cName'   => $order->cName,
                'debtId'  => $order->id,
                'cId'     => $order->cPhone,
                'orderId' => $order->order_id,
                'amount'  => $payNow,
            ]);

            $amount -= $payNow;

            // Recalculate remaining after payment
            $remainingAfter = $remainingDebt - $payNow;

            if ($remainingAfter <= 0) {

                ordersModel::where('id', $order->id)
                    ->update(['status' => 'Paid']);

                salsModel::where('sales_id', $order->order_id)
                    ->where('productId', $order->productId)
                    ->update(['status' => 'Paid']);

                logModal::create([
                    'title' => 'Debt Logs',
                    'description' => 'Order '.$order->orderName.' fully paid by '.$userName
                ]);

            } else {

                ordersModel::where('id', $order->id)
                    ->update(['status' => 'Partial']);
            }
        }
    });

    return redirect('admin/ordersList')
        ->with('success', 'Payment distributed successfully');
}



   public function payout(Request $req)
{
    $OrdersIds    = $req->input('orderId');
    $orderType    = $req->input('orderType');
    $paymentMethod = $req->input('paymentMethod');

    /* ============================
       FETCH ORDERS
    ============================ */

    $orders = ordersModel::where('account', session('account'))
        ->where('order_id', $OrdersIds)
        ->get();


    if ($orders->isEmpty()) {
        return back()->with('error', 'Order not found');
    }

    $firstOrder = $orders->first();

    if (empty($firstOrder->cName)) {
        return back()->with('error', 'Select customer first');
    }

    /* ============================
       TOTAL AMOUNT
    ============================ */

    $totalAmount = $orders->sum('totalPrice');

    /* ============================
       NORMALIZE PAYMENT INPUT
    ============================ */

    $paid   = floatval($req->input('paid', 0));
    $credit = floatval($req->input('credit', 0));

    // Auto-balance
    if ($paid > 0 && $credit == 0) {
        $credit = $totalAmount - $paid;
    }

    if ($credit > 0 && $paid == 0) {
        $paid = $totalAmount - $credit;
    }

    // Clamp values
    $paid   = max(0, min($paid, $totalAmount));
    $credit = max(0, min($credit, $totalAmount));

    // Final guarantee
    if (($paid + $credit) != $totalAmount) {
        return back()->with('error', 'Payment mismatch detected');
    }

    /* ============================
       FETCH CUSTOMER
    ============================ */

    $customer = customerModel::where('id', $firstOrder->cPhone)
        ->where('name', $firstOrder->cName)
        ->first();

    /* ============================
       SUSPEND LOGIC
    ============================ */

    if ($orderType === 'Suspended') {

        foreach ($orders as $order) {
            $order->status = 'Suspended';
            $order->save();
            $products = productsModel::where('account', session('account'))->where('product_id', $order->productId)->first();
$products->quantity -= $order->pQuantity;
$products->save();
        }

        logModal::create([
            'title' => 'Order Logs',
            'description' => $OrdersIds . ' Order Suspended By ' . session('username'),
        ]);

        return back()->with('success', 'Order Suspended');
    }

    /* ============================
       DEBT LIMIT CHECK (CREDIT ONLY)
    ============================ */

    $existingDebt = ordersModel::where('cName', $firstOrder->cName)
    ->where('cPhone', $firstOrder->cPhone)
    ->whereIn('status', ['Debt', 'Partial'])
    ->sum('credit');


    $newDebt = $credit;

    if ($customer && (($existingDebt ?? 0) + $newDebt) > $customer->limits) {
        return back()->with(
            'error',
            'Credit ' . $newDebt . ' exceeds limit. Current debt ' .
            $existingDebt . ' / ' . $customer->limits
        );
    }

    /* ============================
       FINAL STATUS DERIVATION
    ============================ */
    if($orderType === 'Debt') {
       $paid = 0;
        $credit = $totalAmount;
    }

    if ($paid == $totalAmount) {
        $finalStatus = 'Paid';
    } elseif ($credit == $totalAmount) {
        $finalStatus = 'Debt';
    } else {
        $finalStatus = 'Partial';
    }

    /* ============================
       UPDATE ORDERS
    ============================ */

    foreach ($orders as $order) {
        $order->status = $finalStatus;
        $order->paid   = $paid;
        $order->credit = $credit ?? 0;
        $order->save();
    }

    /* ============================
       INSERT SALES
    ============================ */

    foreach ($orders as $order) {
        salsModel::create([
            'sales_id'       => $order->order_id,
            'salesName'      => $order->orderName,
            'stockId'        => $order->stockId,
            'cName'          => $order->cName,
            'cPhone'         => $order->cPhone,
            'productId'      => $order->productId,
            'pQuantity'      => $order->pQuantity,
            'productPrice'   => $order->productPrice,
            'totalPrice'     => $order->totalPrice,
            'paid'            => $order->totalPrice,
            'credit'          => $order->credit,
            'transactionType' => $paymentMethod,
            'status'          => $finalStatus,
            'discount'        => $order->discount,
            'served_by'       => session('username'),
            'account'         => $order->account,
        ]);

$products = productsModel::where('account', session('account'))->where('product_id', $order->productId)->first();
$products->quantity -= $order->pQuantity;
$products->save();
    }

    /* ============================
       DELETE ONLY FULL SALES
    ============================ */

    if ($finalStatus === 'Paid') {
        ordersModel::where('account', session('account'))
            ->where('order_id', $OrdersIds)
            ->delete();
    } else if ($finalStatus === 'Debt') {
        $paid = 0;
        $credit = $totalAmount;
    }

    /* ============================
       LOGGING
    ============================ */

    logModal::create([
        'title' => 'Order Logs',
        'description' => $OrdersIds . ' Order processed By ' . session('username'),
    ]);

    return back()->with(
        'success',
        'Processed: Paid ' . $paid . ' | Credit ' . $credit
    );
}
    public function viewOrder(Request $req)
    {
        $user = Auth::user();

        $customerId = $req->input('customerId');
        
        $orders = ordersModel::where('account', session('account'))->where('cPhone', $customerId)->first();

        $Orders = ordersModel::where('account', session('account'))->where('cPhone', $customerId ?? '')->get();
        
        $Orders2 = ordersModel::where('account', session('account'))
    ->where('cPhone', $customerId ?? '')
    ->selectRaw('
        orderName,
        MAX(pQuantity) as pQuantity,
        MAX(order_id) as order_id,
        MAX(status) as status,
        MAX(credit) as credit,
        MAX(created_at) as created_at
    ')
    ->groupBy('orderName')
    ->get();

        foreach( $Orders as $order) {

            $paidSoFar = debtsModel::where('orderId', $order->order_id)->sum('amount');
        }

        $debt = debtsModel::where('orderId', $orders->order_id ?? '')->sum('amount');

        $cDebt = ordersModel::where('cName', $orders->cName ?? '')->where('cPhone', $orders->cPhone ?? '')->sum('credit');

        $data = compact(
        'orders','Orders','debt','cDebt','Orders2','paidSoFar'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.viewOrder', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.viewOrder', $data);
    }
    }

    public function coupon(Request $req)
    {
        $orderName = $req->input('orderName');
        $coupon = $req->input('coupon_code');
        $coupons = couponModel::where('account', session('account'))->where('couponCode', $coupon)->first();
    
        if (!$coupons) {
            return redirect()->back()->with('error', 'Coupon Code is incorrect');
        }

        if ($coupons->status == 'Used') {
            return redirect()->back()->with('error', 'Coupon Code is Used');
        }

        if ($coupons->expire < date("Y-m-d")) {
            return redirect()->back()->with('error', 'Coupon Code has expired');
        }

        $updt = ordersModel::where('account', session('account'))->where('orderName', $orderName)->first();
        if ($updt) {
            $updt->coupons = $coupon;
            $updt->save();
        }

        $coupons->status = "Used";
        $coupons->save();

         $create = new logModal();
            $create->title = 'Coupon Logs';
            $create->description = $updt->coupons.' Coupon Accepted to order '.$updt->orderName.'  By '.session('username');
            $create->save();

        return redirect()->back()->with('success', 'Coupon is accepted');
    }

    public function discount(Request $req)
    {
        $orderName = $req->input('orderName');
        $discount = $req->input('discount');
        $updt = ordersModel::where('account', session('account'))->where('orderName', $orderName)->first();
    
        if ($updt) {
            $updt->discount = $discount;
            $updt->save();

             $create = new logModal();
            $create->title = 'Discount Logs';
            $create->description = $updt->orderName.' Discount added to order  By '.session('username');
            $create->save();

            return redirect()->back()->with('success', 'Discount is Added');
        }
        
        return redirect()->back()->with('error', 'Order not found');
    }

    public function viewInvoice(Request $req)
    {
        $user = Auth::user();
        $invName = $req->input('invoice') ?? session('invoiceId');
        session(['invoiceId' => $invName]);
        
        $invoices = ordersModel::where('account', session('account'))->where('orderName', $invName)->first();

        $data = compact(
        'invoices'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.viewInvoice', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.viewInvoice', $data);
    }
    }

    public function deleteDebt(Request $req)
    {
        $orderId = $req->input('debtId');

        $deleted = madeni::where('account', session('account'))->where('debt_id', $orderId)->delete();
        if ($deleted) {
            $create = new logModal();
            $create->title = 'Debt Logs';
            $create->description = $orderId.' Debt Deleted By '.session('username');
            $create->save();

            return redirect()->back()->with('success', 'Debt deleted successfully');
        } else {
            $create = new logModal();
            $create->title = 'Debt Logs';
            $create->description = $orderId.' Failed to delete Debt By '.session('username');
            $create->save();

            return redirect()->back()->with('error', 'Failed to delete debt');
        }
    }
}