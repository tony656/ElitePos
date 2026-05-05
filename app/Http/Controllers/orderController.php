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
use App\Models\accountModel;
use App\Models\BankingChip;
use Carbon\Carbon;
use function getSessionAccountId;

class orderController extends Controller
{
 public function index()
{
    $user = Auth::user();

    $orders = ordersModel::where('orderName', '!=', '')
    ->where('account', getSessionAccountId())
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
    ->where('account', getSessionAccountId())
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

 if (strtolower(trim($user->levelStatus)) === 'admin') {
       return view('admin.ordersList', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.ordersList', $data);
    }
}
  
   public function saveInfo(Request $req)
{

    $orderId = $req->input('orderId');
    $served = $req->input('served');

    $selected = $req->input('selectedCustomer'); // e.g., "John Doe|123456789"
    if (!empty($selected)) {
        list($cname, $cphone) = explode('|', $selected);
    
}

    if (empty($orderId)) {
        return redirect()->back()->with('error', 'No order specified');
    }

    // Update all orders with the same order_id
    $updated = ordersModel::where('account', getSessionAccountId())->where('order_id', $orderId)
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
$served = $req->input('served');

if (empty($served)) {
    $seller = Auth::user()->name;
}
    if (empty($pId)) {
        return redirect()->back()->with('success', 'Add a product to create an order');
    }


    // Check for existing active order
    $activeOrder = ordersModel::where('account', getSessionAccountId())->where('served_by', session('username'))
        ->whereNotIn('status', ['Debt', 'Partial', 'Suspended','Return'])
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
    $userId = usersModel::where('account', getSessionAccountId())->where('name', $userName)->first();
    $check = productsModel::where('account', getSessionAccountId())->where('id', $pId)->first();

    /*if (!$check || $check->quantity <= 0) {
        $this->handleLowStock($check->product_id ?? $pId);
        return redirect()->back()->with('error', 'Product is not available in stock');
    } */

    $quantity = 1;
    $totalAmount = ($check->sPrice * $quantity);

    // Get or create stock record for this product
    $stoc = stock::where('account', getSessionAccountId())
        ->where('productId', $check->product_id)
        ->first();

    // If no stock record exists, create one
    if (!$stoc) {
        $stoc = stock::create([
            'account' => getSessionAccountId(),
            'productId' => $check->product_id,
            'name' => $check->name01 ?? 'Unknown',
            'sQuantity' => 0,
            'amount' => 0,
            'profit' => 0,
            'bPrice' => $check->bPrice ?? 0,
            'sPrice' => $check->sPrice ?? 0,
        ]);
    }

    $this->createOrderRecord(
        $OrdersIds,
        $OrdersNames,
        $stoc->name,
        $check->product_id,
        $quantity,
        $check->sPrice,
        $totalAmount,
        $seller,
        $orderType,
        account: getSessionAccountId()
    );

    $this->updateStock($stoc, $check, $quantity, $totalAmount);

    $this->reduceProductQuantity($pId, $quantity);

    $create = new logModal();
            $create->title = 'Order Logs';
            $create->description = $OrdersNames.'(OrderId) Order Created By '.session('username');
            $create->save();
            

    return redirect()->back()->with('success', 'Order Placed Successfully');
}
public function updateCartItem(Request $req)
{
    try {
        $orderId = $req->input('orderId');
        $pId     = $req->input('pId');
        $field   = $req->input('field');
        $value   = (float) $req->input('value');

        // Debug logging
        \Log::info('updateCartItem called', [
            'orderId' => $orderId,
            'pId' => $pId,
            'field' => $field,
            'value' => $value,
            'account' => getSessionAccountId()
        ]);

        if (empty($orderId) || empty($pId)) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        if (!in_array($field, ['pQuantity', 'discount', 'discount_increase', 'adjustment'])) {
            return response()->json(['error' => 'Invalid field'], 422);
        }

        // Check if session account is set
        if (!getSessionAccountId()) {
            \Log::error('Session account not set in updateCartItem');
            return response()->json(['error' => 'Session expired or not set'], 401);
        }

        $product = productsModel::where('account', getSessionAccountId())
            ->where('product_id', $pId)
            ->first();

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    $cartItem = ordersModel::where('productId', $pId)
        ->where('order_id', $orderId)
        ->where('account', getSessionAccountId())
        ->where(function($q) {
            $q->where('offered_items', 0)
              ->orWhereNull('offered_items');
        }) // Ensure we are updating the main item, not a free offer
        ->first();

    if (!$cartItem) {
        return response()->json(['error' => 'Cart item not found'], 404);
    }

    if ($field === 'discount' && $value > $product->discount) {
        return response()->json(['error' => 'Discount exceeds limit'], 400);
    }

    // Handle quantity changes - for returns, we need to restore product quantity
    if ($field === 'pQuantity') {
        $oldQuantity = $cartItem->pQuantity;
        
        // If quantity is being reduced (or made negative for return)
        if ($value < $oldQuantity) {
            // Calculate how much quantity is being reduced/returned
            $quantityDiff = $oldQuantity - $value;
            
            // Add the difference back to product quantity (for returns)
            $product->quantity += $quantityDiff;
            $product->save();
        }
        
        // For negative quantities (returns), allow regardless of current stock
        // The stock has already been adjusted in the cart at order creation time
    }

    // Allow negative quantities for returns (stock is restored above)
    if ($field === 'pQuantity' && $value > 0 && $value > ($product->quantity + $cartItem->pQuantity)) {
        return response()->json(['error' => 'Insufficient stock'], 400);
    }

    // Handle adjustment field - merge discount and increase into one
    if ($field === 'adjustment') {
        if ($value > 0) {
            // Positive value = discount
            $cartItem->discount = $value;
            $cartItem->discount_increase = 0;
        } else {
            // Negative value = increase
            $cartItem->discount = 0;
            $cartItem->discount_increase = abs($value);
        }
    } else {
        $cartItem->$field = $value;
    }

    // Recalculate total price correctly
    $price           = $cartItem->productPrice ?? 0;
    $quantity        = $cartItem->pQuantity;
    $discount        = $cartItem->discount ?? 0;
    $discountIncrease = $cartItem->discount_increase ?? 0;

    // Calculate total with sign: (Price * Qty) - (Discount * abs(Qty)) + (DiscountIncrease * abs(Qty))
    // The sign of total is determined by quantity; discounts are per-unit so use abs(Qty)
    $total = ($price * $quantity) - ($discount * abs($quantity)) + ($discountIncrease * abs($quantity));

    if ($quantity < 0) {
        // For returns: totalPrice should be negative, return_amount stores absolute value
        $cartItem->totalPrice = $total;
        $cartItem->return_amount = abs($total);
        $cartItem->discount = 0;
        $cartItem->discount_increase = 0;
    } else {
        // Normal sale
        $cartItem->totalPrice = $total;
        $cartItem->return_amount = 0;
        // Convert per-unit discount to total for storage
        $cartItem->discount = ($discount * $quantity);
        $cartItem->discount_increase = ($discountIncrease * $quantity);
    }

    $cartItem->save();

    // ============================================================
    // AUTO-APPLY OFFERS: Add free items when quantity meets requirement
    // ============================================================
    if ($field === 'pQuantity' && $quantity > 0) {
        // Check if there's an offer for this product
        $offer = \App\Models\Offer::where('account', getSessionAccountId())
            ->where('product_id', $pId)
            ->where('is_active', true)
            ->first();

        if ($offer) {
            // Calculate how many times the offer applies
            $timesOfferApplies = floor($quantity / $offer->required_quantity);
            
            if ($timesOfferApplies > 0) {
                $freeItemsCount = $timesOfferApplies * $offer->offer_quantity;
                
                // Check if free items already exist in cart
                $existingFreeItem = ordersModel::where('account', getSessionAccountId())
                    ->where('order_id', $orderId)
                    ->where('productId', $offer->offer_product_id)
                    ->where('offered_items', 1)
                    ->first();

                if ($existingFreeItem) {
                    // Update the free items quantity
                    $existingFreeItem->pQuantity = $freeItemsCount;
                    $existingFreeItem->totalPrice = 0; // Free items have 0 price
                    $existingFreeItem->save();
                } else {
                    // Find a valid stock entry
                    $stoc = stock::where('account', getSessionAccountId())
                        ->where('productId', $offer->offer_product_id)
                        ->where('sQuantity', '>', 0)
                        ->orderBy('id', 'asc')
                        ->first();
                    
                    // Only create the free item order record if we have a stock entry
                    // Stock validation will happen at payout time
                    if ($stoc && $stoc->sQuantity > 0) {
                        ordersModel::create([
                            'order_id' => $orderId,
                            'stockId' => $stoc->name,
                            'orderName' => $cartItem->orderName,
                            'productId' => $offer->offer_product_id,
                            'pQuantity' => $freeItemsCount,
                            'productPrice' => $cartItem->productPrice ?? 0, // Free
                            'totalPrice' => 0, // Free
                            'served_by' => $cartItem->served_by,
                            'status' => 'Sell',
                            'account' => getSessionAccountId(),
                            'offered_items' => 1, // Mark as free item
                            'offer_parent_product' => $pId, // Link to parent product
                        ]);
                    } else {
                        // No stock entry found, but still add the free item to cart
                        // Stock validation will happen at payout time
                        ordersModel::create([
                            'order_id' => $orderId,
                            'stockId' => 'NO-STOCK-' . $offer->offer_product_id,
                            'orderName' => $cartItem->orderName,
                            'productId' => $offer->offer_product_id,
                            'pQuantity' => $freeItemsCount,
                            'productPrice' => $cartItem->productPrice ?? 0, // Free
                            'totalPrice' => 0, // Free
                            'served_by' => $cartItem->served_by,
                            'status' => 'Sell',
                            'account' => getSessionAccountId(),
                            'offered_items' => 1, // Mark as free item
                            'offer_parent_product' => $pId, // Link to parent product
                        ]);
                    }
                }

                // NOTE: Stock for free items is NOT deducted here anymore.
                // It will be deducted in payout() when the order is finalized.
                // This prevents stock validation errors when adjusting cart quantities.
            }
        }
    }

        return response()->json(['success' => true]);
        
    } catch (\Exception $e) {
        \Log::error('Error in updateCartItem: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
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
        $restock = productsModel::where('account', getSessionAccountId())->where('product_id', $productId)->first();
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
     'account' => $account,
     'offered_items' => 0
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
        $reduce = productsModel::where('account', getSessionAccountId())->where('product_id', $productId)->first();
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
        $orders = ordersModel::where('account', getSessionAccountId())->where('orderName', $OrderName)->first(); 
       
                     $data = compact(
        'newOrder','orders'
    );

 if (strtolower(trim($user->levelStatus)) === 'admin') {
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
        
        $look = ordersModel::where('account', getSessionAccountId())->where('order_id', $OrdersIds)
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
        
        $look = ordersModel::where('account', getSessionAccountId())->where('order_id', $OrdersIds)
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

    public function dltProdOrdcart(Request $req)
    {
        $OrdersIds = $req->input('orderId');
        $prodId = $req->input('itemId');
        $prodQuantit = $req->input('prodQuantity');
        
        $deltProduct = ordersModel::where('account', getSessionAccountId())->where('order_id', $OrdersIds)
                               ->where('productId', $prodId)
                               ->first();

        if (!$deltProduct) {
            return redirect()->back()->with('error', 'Product Not Found');
        }
        $this->reverseStockUpdate($deltProduct);

        $deltProduct->delete();

        

        $create = new logModal();
            $create->title = 'Product Logs';
            $create->description = $deltProduct->name01.' Product Deleted By '.session('username');
            $create->save();
        return redirect()->back()->with('success', 'Product Deleted Successfully');
    }

    public function dltProdOrd(Request $req)
    {
        $OrdersIds = $req->input('OrdersIds');
        $prodId = $req->input('prodId');
        $prodQuantit = $req->input('prodQuantity');
        
        $deltProduct = ordersModel::where('account', getSessionAccountId())->where('order_id', $OrdersIds)
                               ->where('productId', $prodId)
                               ->first();

        if (!$deltProduct) {
            return redirect()->back()->with('error', 'Product Not Found');
        }

        if($deltProduct) {
            $products = productsModel::where('account', getSessionAccountId())->where('product_id', $prodId)->first();
            if ($products) {
                $products->quantity += ($deltProduct->pQuantity ?? 0);
                $products->save();
            }
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
        $updt = productsModel::where('account', getSessionAccountId())->where('product_id', $productId)->first();
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
        $stoc = stock::where('account', getSessionAccountId())->where('productId', $order->productId)
                   ->where('name', $order->stockId)
                   ->first();

        if ($stoc) {
            $stoc->profit -= (($stoc->sPrice * $stoc->sQuantity) - ($stoc->bPrice * $stoc->sQuantity));
            $stoc->sQuantity -= ($order->pQuantity ?? 0);
            $stoc->amount -= ($order->totalPrice ?? 0);

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
  $paymentMethod = $req->input('payment_method', 'cash');
  $chipAmount = (float) $req->input('chip_amount', 0);

  if (!$customerId || $amount <= 0) {
      return back()->with('error', 'Invalid customer or amount');
  }

  $userName = session('username');
  $shopName = getSessionAccountDisplayName();

  // Handle chip payment validation and deduction
  if ($paymentMethod === 'chip') {
      if ($chipAmount <= 0) {
          return back()->with('error', 'Chip amount is required when payment method is chip');
      }
      if ($chipAmount > $amount) {
          return back()->with('error', 'Chip amount cannot exceed total payment amount');
      }
      
      // Get available chip from last chip entry (cumulative total)
      $shop = accountModel::where('name', $shopName)->first();
      if (!$shop) {
          return back()->with('error', 'Shop not found');
      }
      
      $lastChipEntry = BankingChip::where('shop_id', $shop->id)
          ->orderBy('id', 'desc')
          ->first();
      $availableChip = $lastChipEntry ? $lastChipEntry->available_chip : 0;
      
      if ($availableChip <= 0) {
          return back()->with('error', 'No chip balance available for this shop');
      }
      
      if ($chipAmount > $availableChip) {
          return back()->with('error', 'Insufficient chip balance. Available: ' . number_format($availableChip) . ' Tsh');
      }
      
      // Deduct chip from last transfers first (LIFO)
      $remainingChipToDeduct = $chipAmount;
      
      // Get all chip entries with available chip, newest first
      $chipEntries = BankingChip::where('shop_id', $shop->id)
          ->where('available_chip', '>', 0)
          ->orderBy('id', 'desc')
          ->lockForUpdate()
          ->get();
      
      // Collect IDs of chip entries that had chip deducted
      $affectedChipIds = [];
      
      foreach ($chipEntries as $chipEntry) {
          if ($remainingChipToDeduct <= 0) break;
          
          $deductFromThis = min($remainingChipToDeduct, $chipEntry->available_chip);
          if ($deductFromThis > 0) {
              $chipEntry->available_chip -= $deductFromThis;
              $chipEntry->save();
              $affectedChipIds[] = $chipEntry->id;
              $remainingChipToDeduct -= $deductFromThis;
          }
      }
      
      // Recalculate cumulative available_chip for all entries after the last affected one
      if (!empty($affectedChipIds)) {
          $lastAffectedId = max($affectedChipIds);
          $lastAffectedChip = BankingChip::find($lastAffectedId);
          if ($lastAffectedChip) {
              $lastAffectedChip->recalculateCumulativeChip();
          }
      }
  }

  DB::transaction(function () use ($customerId, &$amount, $userName, $paymentMethod, $chipAmount, $shopName) {

      $orders = ordersModel::where('account', getSessionAccountId())
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

          // Calculate chip portion for this order
          $chipForThisOrder = 0;
          if ($paymentMethod === 'chip' && $chipAmount > 0) {
              $chipForThisOrder = min($chipAmount, $payNow);
              $chipAmount -= $chipForThisOrder;
          }

          debtsModel::create([
              'cName'   => $order->cName,
              'debtId'  => $order->id,
              'cId'     => $order->cPhone,
              'orderId' => $order->order_id,
              'amount'  => $payNow,
              'account' => $order->account,
              'payment_method' => $paymentMethod,
              'chip_amount' => $paymentMethod === 'chip' ? $chipForThisOrder : null,
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
    $served = $req->input('served');
    $saleDate = $req->input('saleDate');
    
    /* ============================
       FETCH ORDERS
    ============================ */

    $orders = ordersModel::where('account', getSessionAccountId())
        ->where('order_id', $OrdersIds)
        ->get();
        
    if(!empty($saleDate)) {
    foreach($orders as $ordez) {
    $ordez->created_at = $saleDate;
    $ordez->save();
    }
    
    }

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

    // Calculate total: sum totalPrice directly (negative for returns, positive for sales)
    $totalAmount = 0;
    foreach ($orders as $order) {
        $totalAmount += ($order->totalPrice ?? 0);
    }

    /* ============================
       NORMALIZE PAYMENT INPUT
    ============================ */

    $paid   = floatval($req->input('paid', 0));
    $credit = floatval($req->input('credit', 0));

    // If total is 0 (all items returned), handle appropriately
    if ($totalAmount <= 0) {
        $paid = 0;
        $credit = 0;
    }
    // If both paid and credit are 0 but there's a positive total, default to full payment
    elseif ($paid == 0 && $credit == 0) {
        $paid = $totalAmount;
        $credit = 0;
    }

    // Auto-balance
    if ($paid > 0 && $credit == 0) {
        $credit = $totalAmount - $paid;
    }

    if ($credit > 0 && $paid == 0) {
        $paid = $totalAmount - $credit;
    }

    // Clamp values - handle when total is 0 or negative
    if ($totalAmount > 0) {
        $paid   = max(0, min($paid, $totalAmount));
        $credit = max(0, min($credit, $totalAmount));
    } else {
        $paid = 0;
        $credit = 0;
    }

    // Final guarantee with floating point tolerance - skip if total is 0 or negative
    if ($totalAmount > 0) {
        $tolerance = 0.01;
        if (abs(($paid + $credit) - $totalAmount) > $tolerance) {
            return back()->with('error', 'Payment mismatch detected. Paid: ' . $paid . ', Credit: ' . $credit . ', Total: ' . $totalAmount);
        }
    }
    
    // Ensure they add up exactly after tolerance check
    $paid = round($paid, 2);
    $credit = round($totalAmount - $paid, 2);

    /* ============================
       FETCH CUSTOMER
    ============================ */

    $customer = customerModel::where('id', $firstOrder->cPhone)
        ->where('name', $firstOrder->cName)
        ->where('account', getSessionAccountId())
        ->first();

    /* ============================
       SUSPEND LOGIC
    ============================ */

    if ($orderType === 'Suspended') {

        foreach ($orders as $order) {
            $order->status = 'Suspended';
            $order->save();
           
        }

        logModal::create([
            'title' => 'Order Logs',
            'description' => $OrdersIds . ' Order Suspended By ' . session('username'),
        ]);

        return back()->with('success', 'Order Suspended');
    }

    /* ============================
       RETURN LOGIC
    ============================ */
    
    if ($orderType === 'Return') {
        // For returns: amount goes to return column, not paid or credit
        $paid = 0;
        $credit = 0;
        $finalStatus = 'Return';

        foreach ($orders as $order) {
            $order->status = 'Return';
            $order->paid = 0;
            $order->credit = 0;
            // For returns, totalPrice is already negative from cart, return_amount is positive
            // No need to modify - just ensure they're set correctly
            $order->save();
            
            // Restore product quantity
            $products = productsModel::where('account', getSessionAccountId())->where('product_id', $order->productId)->first();
            if ($products) {
                $products->quantity += $order->pQuantity;
                $products->save();
            }
        }

        // Create sales record - preserve the negative totalPrice and positive return_amount from cart
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
                'totalPrice'     => $order->totalPrice, // Negative value for returns
                'return_amount'  => $order->return_amount ?? 0, // Positive return amount
                'paid'           => 0,
                'credit'         => 0,
                'transactionType' => $paymentMethod,
                'status'          => 'Return',
                'discount'        => 0, // Discounts are cleared for returns
                'discount_increase' => 0,
                'offered_items'     => $order->offered_items,
                'offer_parent_product' => $order->offer_parent_product,
                'served_by'       => $served ?? session('username'),
                'account'         => $order->account,
                'created_at'      => $saleDate ?? Carbon::now(),
            ]);
        }

        logModal::create([
            'title' => 'Order Logs',
            'description' => $OrdersIds . ' Order Returned By ' . session('username'),
        ]);

        return back()->with('success', 'Return processed successfully');
    }

    /* ============================
       DEBT LIMIT CHECK (CREDIT ONLY)
    ============================ */
if($orderType === 'Debt') {
    $existingDebt = \DB::query()
    ->fromSub(
        ordersModel::where('account', getSessionAccountId())
            ->where('cName', $firstOrder->cName)
            ->where('cPhone', $firstOrder->cPhone)
            ->whereIn('status', ['Debt', 'Partial'])
            ->selectRaw('order_id, MAX(credit) as credit')
            ->groupBy('order_id'),
        'o'
    )
    ->sum('credit');


  if (!$customer) {
    return back()->with('error', 'Customer not found');
}

$limit = (float) ($customer->limits ?? 0);
$newDebt = (float) $credit;

if (($existingDebt + $newDebt) > $limit) {
    return back()->with(
        'error',
        'Credit ' . $newDebt . ' exceeds limit. Current debt ' .
        $existingDebt . ' / ' . $limit
    );
}

    /* ============================
       FINAL STATUS DERIVATION
    ============================ */
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
$firstOrderId = $orders->first()->id;

  foreach ($orders as $order) {
    $order->status = $finalStatus;

    // only first row stores totals
    if ($order->id == $firstOrderId) {
        $order->paid   = $paid;
        $order->credit = $credit ?? 0;
    } else {
        // optional: keep others zero to avoid confusion
        $order->paid   = 0;
        $order->credit = 0;
    }

    $order->save();
}

    /* ============================
        INSERT SALES
    ============================ */

    \Log::info('Starting sales creation for order ' . $OrdersIds . ' with ' . $orders->count() . ' items');

    foreach ($orders as $order) {

     $products = productsModel::where('account', getSessionAccountId())->where('product_id', $order->productId)->first();
            if ($products) {
                $products->quantity -= $order->pQuantity;
                $products->save();
            }
            
        // Skip free/offered items - they are handled separately below
        $isOfferedItem = $order->offered_items ?? false;
        if ($isOfferedItem && !empty($order->offer_parent_product)) {
            continue; // Skip free items in this loop
        }
        
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
            'return_amount'  => $order->return_amount ?? 0,
            'paid'           => ($order->id == $firstOrderId) ? $paid : 0,
            'credit'         => ($order->id == $firstOrderId) ? $credit : 0,
            'transactionType' => $paymentMethod,
            'status'          => $finalStatus,
            'discount'        => $order->discount,
            'discount_increase' => $order->discount_increase ?? 0,
            'served_by'       => $served ?? session('username'),
            'account'         => $order->account,
            'offered_items'   => $order->offered_items ?? 0,
            'offer_parent_product' => $order->offer_parent_product ?? null,
            'created_at'      => $saleDate ?? Carbon::now(),
        ]);
    
        $products = productsModel::where('account', getSessionAccountId())->where('product_id', $order->productId)->first();
        if ($products) {
            $products->quantity -= $order->pQuantity;
            $products->save();
        }
    }

    /* ============================
       SAVE OFFERED ITEMS (FREE ITEMS)
       Fetch from orders table where offered_items = 1
    ============================ */
    
    $offeredOrders = ordersModel::where('account', getSessionAccountId())
        ->where('order_id', $OrdersIds)
        ->where('offered_items', 1)
        ->get();
    
    foreach ($offeredOrders as $offerOrder) {
        $offerProductId = $offerOrder->productId;
        $offerQuantity = $offerOrder->pQuantity;
        
        // Get the product details
        $offerProduct = productsModel::where('account', getSessionAccountId())
            ->where('product_id', $offerProductId)
            ->first();
        
        if ($offerProduct) {
            // Use the product's actual selling price for the sales record
            $offerProductPrice = $offerProduct->sPrice ?? 0;
            
            // Check if enough stock available for the free item
            if ($offerProduct->quantity < $offerQuantity) {
                // Insufficient stock - skip this free item but continue with the order
                \Log::warning('Insufficient stock for offered item - skipped', [
                    'product' => $offerProduct->name01 ?? $offerProductId,
                    'requested' => $offerQuantity,
                    'available' => $offerProduct->quantity,
                    'order_id' => $OrdersIds,
                    'user' => session('username')
                ]);
                
                // Add a warning message to be shown to the user
                session()->flash('warning', 'Free item "' . ($offerProduct->name01 ?? $offerProductId) . '" was skipped due to insufficient stock (needed: ' . $offerQuantity . ', available: ' . $offerProduct->quantity . ').');
                
                // Skip this free item - do not add to sales or deduct stock
                continue;
            }
            
            // Reduce stock for offered items (deduct now that order is finalized)
            $offerProduct->quantity -= $offerQuantity;
            $offerProduct->save();
            
            // Create sales record (FREE item - totalPrice is 0 but productPrice stores original price)
            salsModel::create([
                'sales_id'       => $OrdersIds . '_OFFER',
                'salesName'      => $firstOrder->orderName ?? 'N/A',
                'stockId'        => 'OFFER',
                'cName'          => $firstOrder->cName ?? 'N/A',
                'cPhone'         => $firstOrder->cPhone ?? 'N/A',
                'productId'      => $offerProductId,
                'pQuantity'      => $offerQuantity,
                'productPrice'   => $offerProductPrice, // Store original product price
                'totalPrice'     => 0, // Free item - no charge
                'return_amount'  => 0,
                'paid'           => 0,
                'credit'         => 0,
                'transactionType' => $paymentMethod,
                'status'          => $finalStatus,
                'discount'        => 0,
                'discount_increase' => 0,
                'served_by'       => $served ?? session('username'),
                'account'         => getSessionAccountId(),
                'offered_items'   => 1, // Mark as offered item
                'offer_parent_product' => $offerOrder->offer_parent_product ?? null,
                'created_at'      => $saleDate ?? Carbon::now(),
            ]);
            
            logModal::create([
                'title' => 'Offer Logs',
                'description' => 'Offered item: ' . $offerProduct->name01 . ' x' . $offerQuantity . ' added to order ' . $OrdersIds . ' by ' . session('username'),
            ]);
        }
    }

    /* ============================
       DELETE ONLY FULL SALES
    ============================ */

    if ($finalStatus === 'Paid') {
        ordersModel::where('account', getSessionAccountId())
            ->where('order_id', $OrdersIds)
            ->delete();
    } else if ($finalStatus === 'Debt') {
        $paid = 0;
        $credit = $totalAmount;
    }

    /* ============================
        LOGGING
    ============================ */

    \Log::info('Order processing completed for ' . $OrdersIds . '. Final status: ' . $finalStatus);

    logModal::create([
        'title' => 'Order Logs',
        'description' => $OrdersIds . ' Order Sold Successfully By ' . session('username'),
    ]);

    return back()->with(
        'success',
        'Processed: Paid ' . $paid . ' | Credit ' . $credit
    );
}
    public function viewOrder(Request $req)
    {
        $user = Auth::user();
        $paidSoFar = 0;
        $customerId = $req->input('customerId');
        
        // Get all order items for this customer (Debt or Partial status)
        $Orders = ordersModel::where('account', getSessionAccountId())
            ->where('cPhone', $customerId)
            ->whereIn('status', ['Debt', 'Partial'])
            ->orderBy('created_at', 'desc')
            ->orderBy('order_id', 'desc')
            ->get();
            
        // Group orders by order_id for summary
        $groupedOrders = $Orders->groupBy('order_id');
        
        // Get first order for customer info
        $firstOrder = $Orders->first();
        
        // Calculate total credit across all orders
        $totalCredit = $Orders->sum('credit');
        
        // Calculate paid so far per order
        $orderPayments = [];
        foreach($groupedOrders as $orderId => $orderItems) {
            $orderPayments[$orderId] = debtsModel::where('orderId', $orderId)->sum('amount');
        }
        
        // Total paid across all orders
        $paidSoFar = array_sum($orderPayments);
        
        // Customer total debt (sum of all order credits)
        $cDebt = $totalCredit;

        // Get available chip from last chip entry (cumulative total)
        $shop = accountModel::where('name', getSessionAccountDisplayName())->first();
        $availableChip = 0;
        if ($shop) {
            $lastChip = BankingChip::where('shop_id', $shop->id)
                ->orderBy('id', 'desc')
                ->first();
            if ($lastChip) {
                $availableChip = $lastChip->available_chip;
            }
        }

        $data = compact(
            'Orders', 'groupedOrders', 'orderPayments',
            'paidSoFar', 'totalCredit', 'cDebt', 'firstOrder', 'availableChip'
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
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
        $coupons = couponModel::where('account', getSessionAccountId())->where('couponCode', $coupon)->first();
    
        if (!$coupons) {
            return redirect()->back()->with('error', 'Coupon Code is incorrect');
        }

        if ($coupons->status == 'Used') {
            return redirect()->back()->with('error', 'Coupon Code is Used');
        }

        if ($coupons->expire < date("Y-m-d")) {
            return redirect()->back()->with('error', 'Coupon Code has expired');
        }

        $updt = ordersModel::where('account', getSessionAccountId())->where('orderName', $orderName)->first();
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
        $updt = ordersModel::where('account', getSessionAccountId())->where('orderName', $orderName)->first();
    
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
        
        $invoices = ordersModel::where('account', getSessionAccountId())->where('orderName', $invName)->get();

        $data = compact(
        'invoices'
    );

 if (strtolower(trim($user->levelStatus)) === 'admin') {
       return view('admin.viewInvoice', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.viewInvoice', $data);
    }
    }
}
