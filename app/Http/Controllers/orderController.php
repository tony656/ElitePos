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
use App\Models\UserAccount;
use App\Models\accountModel;
use App\Models\BankingChip;
use Carbon\Carbon;
use function getCurrentShopId;
use function getuseraccount;

class orderController extends Controller
{
 public function index()
{
    $user = Auth::user();

    $orders = ordersModel::where('orderName', '!=', '')
    ->where('account', getCurrentShopId())
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
  ->where('account', getCurrentShopId())
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

       return view('ordersList', $data);

}
  
   public function saveInfo(Request $req)
{

    $orderId = $req->input('orderId');
    $served  = $req->input('served');

    $cname  = '';
    $cphone = '';

    $selected = $req->input('selectedCustomer');
    if (!empty($selected)) {
        list($cname, $cphone) = explode('|', $selected, 2);
    }

    if (empty($orderId)) {
         $OrdersIds = Uuid::uuid4();
         $OrdersNames =
     salsModel::distinct('sales_id')->count('sales_id')
   + ordersModel::where('status', 'Suspended')->distinct('order_id')->count('order_id')
   + 1;

         $new = new ordersModel();
         $new->order_id    = $OrdersIds;
         $new->orderName   = $OrdersNames;
         $new->cName       = $cname ?: 'Customer-' . strtoupper(Str::random(5));
         $new->status      = 'Pending';
         $new->cPhone      = $cphone;
         $new->account     = getCurrentShopId();
         $new->save();

         return redirect()->back()->with('success', 'Order Created Successfully');
    }

    $updated = ordersModel::where('account', getCurrentShopId())->where('order_id', $orderId)
                ->update([
                    'cName' => $cname,
                    'cPhone' => $cphone
                ]);
  

    if ($updated) {
        return redirect()->back()->with('success', 'Customer info saved');
        
    } else {
        return redirect()->back()->with('error', 'Failed');
    }
}

public function saveSeller(Request $req)
{
    $orderId = $req->input('orderId');
    $selected = $req->input('selectedSeller');
    
    if (empty($orderId)) {
        return redirect()->back()->with('error', 'No order specified');
    }
    
    $sellerName = null;
    if (!empty($selected)) {
        // The selected seller is just the name (not pipe-separated like customer)
        $sellerName = $selected;
    }
    
    if (empty($sellerName)) {
        return redirect()->back()->with('error', 'No seller selected');
    }
    
    // Update all orders with the same order_id
    $updated = ordersModel::where('account', getCurrentShopId())
                ->where('order_id', $orderId)
                ->update([
                    'served_by' => $sellerName
                ]);
    
    if ($updated) {
        return redirect()->back()->with('success', 'Seller saved successfully');
    } else {
        return redirect()->back()->with('error', 'Failed to save seller (maybe no records matched)');
    }
}

public function newOrder(Request $req)
{
if (!canUser('create_sales')) {
        abort(403, 'Unauthorized access');
    }
  $orderType = $req->input('orderType', '');
  $pId = $req->input('pId');
  $served = $req->input('served');
  $user = Auth::user();

  if (!empty($orderType)) {
      $req->session()->put('orderType', $orderType);
  }

if (empty($served)) {
    $seller = Auth::user()->name;
} else {
    $seller = $served;
}
    if (empty($pId)) {
        return redirect()->back()->with('success', 'Add a product to create an order');
    }
    
    // Get all accessible shops for the user
    
    $shops = getUserAccounts();
    $shopIds = array_column($shops, 'id');

    // Determine which shop/account to use for this order
    // Priority: 1. selected_shop_id from session, 2. account_id from session, 3. fallback to user's primary shop
    $selectedShopId = getCurrentShopId();
    
    // Validate user has access to selected shop
        $assignedAccountIds = $shopIds;


     // Check for existing active order - must match productsController::newOrder() statuses
     $activeOrder = ordersModel::where('account', $selectedShopId)
         ->whereIn('status', ['Sell', 'Pending'])
         ->orderBy('id', 'desc')
         ->first();

    if ($activeOrder) {
        $OrdersIds = $activeOrder->order_id;
        $OrdersNames = $activeOrder->orderName;
        $cName = $activeOrder->cName;
        $cPhone = $activeOrder->cPhone;

        // Ensure the active order uses the current seller (in case it was changed)
        $activeOrder->served_by = $seller;
        $activeOrder->save();
    } else {
        
    // Generate random customer name if empty
    $cName = empty($cName) ? 'Customer-' . strtoupper(Str::random(5)) : $cName;
    $cPhone = '';
        $OrdersIds = Uuid::uuid4();
        $OrdersNames =
    salsModel::distinct('sales_id')->count('sales_id')
  + ordersModel::where('status', 'Suspended')->distinct('order_id')->count('order_id')
  + 1;

    }

    $userName = Auth::user()->name;
    $userId = usersModel::where('account', getCurrentShopId())->where('name', $userName)->first();
    $check = productsModel::where('account', getCurrentShopId())->where('id', $pId)->first();

    if (!$check) {
        return redirect()->back()->with('error', 'Product not found in selected shop');
    }


    /*if (!$check || $check->quantity <= 0) {
        $this->handleLowStock($check->product_id ?? $pId);
        return redirect()->back()->with('error', 'Product is not available in stock');
    } */

    $quantity = 1;
    $totalAmount = (($check->sPrice ?? 0) * $quantity);

    // Get or create stock record for this product
    $stoc = stock::where('account', getCurrentShopId())
        ->where('productId', $check->product_id)
        ->first();

    // If no stock record exists, create one
    if (!$stoc) {
        $stoc = stock::create([
            'account' => $selectedShopId,
            'productId' => $check->product_id,
            'name' => $check->name01 ?? 'Unknown',
            'sQuantity' => 0,
            'amount' => 0,
            'profit' => 0,
            'bPrice' => $check->bPrice ?? 0,
            'sPrice' => $check->sPrice ?? 0,
        ]);
    }
     
    if($activeOrder) {
        $existingCartItem = ordersModel::where('account', $selectedShopId)
            ->where('order_id', $activeOrder->order_id)
            ->where('productId', $pId)
            ->where(function($q) {
                $q->where('offered_items', 0)
                  ->orWhereNull('offered_items');
            })
            ->first();

        if($existingCartItem) {
            $existingCartItem->pQuantity += 1;
            $existingCartItem->totalPrice = ($existingCartItem->pQuantity * $existingCartItem->productPrice);
            $existingCartItem->save();

            $this->updateStock($stoc, $check, 1, $check->sPrice ?? 0);

            $create = new logModal();
            $create->title = 'Order Logs';
            $create->description = $OrdersNames.'(OrderId) Order Updated By '.Auth::user()->name;
            $create->save();
            return redirect()->back()->with('success', 'Item quantity updated');
        }
    }
    $this->createOrderRecord(
        $OrdersIds,
        $OrdersNames,
        $stoc->name,
        $cName,
        $cPhone,
        $check->product_id,
        $quantity,
        $check->sPrice ?? 0,
        $totalAmount,
        $seller,
        $orderType,
        account: $selectedShopId
    );

    $this->updateStock($stoc, $check, $quantity, $totalAmount);

    
    $create = new logModal();
            $create->title = 'Order Logs';
            $create->description = $OrdersNames.'(OrderId) Order Created By '.Auth::user()->name;
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
            'account' => getCurrentShopId()
        ]);

        if (empty($orderId) || empty($pId)) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        if (!in_array($field, ['pQuantity', 'discount', 'discount_increase', 'adjustment', 'productPrice'])) {
            return response()->json(['error' => 'Invalid field'], 422);
        }

        // Check if session account is set
        if (!getCurrentShopId()) {
            \Log::error('Session account not set in updateCartItem');
            return response()->json(['error' => 'Session expired or not set'], 401);
        }

        $product = productsModel::where('account', getCurrentShopId())
            ->where('product_id', $pId)
            ->first();

    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }

    $cartItem = ordersModel::where('productId', $pId)
        ->where('order_id', $orderId)
        ->where('account', getCurrentShopId())
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
        $offer = \App\Models\Offer::where('account', getCurrentShopId())
            ->whereHas('requiredItems', function ($q) use ($pId) {
                $q->where('product_id', $pId);
            })
            ->where('is_active', true)
            ->with('requiredItems')
            ->first();

        if ($offer) {
            $requiredItems = $offer->requiredItems;
            $bundleCount = null;

            foreach ($requiredItems as $reqItem) {
                $cartQty = ordersModel::where('account', getCurrentShopId())
                    ->where('order_id', $orderId)
                    ->where('productId', $reqItem->product_id)
                    ->where('offered_items', '!=', 1)
                    ->sum('pQuantity');

                $times = (int) floor($cartQty / $reqItem->required_quantity);
                if ($times === 0) {
                    $bundleCount = 0;
                    break;
                }
                $bundleCount = $bundleCount === null ? $times : min($bundleCount, $times);
            }

            if ($bundleCount !== null && $bundleCount > 0) {
                $freeItemsCount = $bundleCount * $offer->offer_quantity;

                $existingFreeItem = ordersModel::where('account', getCurrentShopId())
                    ->where('order_id', $orderId)
                    ->where('productId', $offer->offer_product_id)
                    ->where('offered_items', 1)
                    ->first();

                if ($existingFreeItem) {
                    $existingFreeItem->pQuantity = $freeItemsCount;
                    $existingFreeItem->totalPrice = 0;
                    $existingFreeItem->save();
                } else {
                    $stoc = stock::where('account', getCurrentShopId())
                        ->where('productId', $offer->offer_product_id)
                        ->orderBy('id', 'asc')
                        ->first();

                    if ($stoc) {
                        ordersModel::create([
                            'order_id' => $orderId,
                            'stockId' => $stoc->name,
                            'orderName' => $cartItem->orderName,
                            'productId' => $offer->offer_product_id,
                            'pQuantity' => $freeItemsCount,
                            'productPrice' => $cartItem->productPrice ?? 0,
                            'totalPrice' => 0,
                            'served_by' => $cartItem->served_by,
                            'status' => 'Sell',
                            'account' => getCurrentShopId(),
                            'offered_items' => 1,
                            'offer_parent_products' => $pId,
                        ]);
                    } else {
                        ordersModel::create([
                            'order_id' => $orderId,
                            'stockId' => 'NO-STOCK-' . $offer->offer_product_id,
                            'orderName' => $cartItem->orderName,
                            'productId' => $offer->offer_product_id,
                            'pQuantity' => $freeItemsCount,
                            'productPrice' => $cartItem->productPrice ?? 0,
                            'totalPrice' => 0,
                            'served_by' => $cartItem->served_by,
                            'status' => 'Sell',
                            'account' => getCurrentShopId(),
                            'offered_items' => 1,
                            'offer_parent_products' => $pId,
                        ]);
                    }
                }
            } else {
                $existingFreeItem = ordersModel::where('account', getCurrentShopId())
                    ->where('order_id', $orderId)
                    ->where('productId', $offer->offer_product_id)
                    ->where('offered_items', 1)
                    ->first();

                if ($existingFreeItem) {
                    $existingFreeItem->delete();
                }
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
            ' (OrderId) Order Resumed By ' . Auth::user()->name,
    ]);

    return redirect()->back()->with('success', 'Order Resumed Successfully');
}


    public function setOrderType(Request $req)
    {
        session(['orderType' => $req->input('orderType', 'Sell')]);
        return response()->json(['success' => true]);
    }

    private function handleLowStock($productId)
    {
        $restock = productsModel::where('account', getCurrentShopId())->where('product_id', $productId)->first();
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
    $orderId, $orderName, $stockId, $cName, $cPhone,
    $productId, $pQuantity, $pPrice, $tPrice, $servedBy, $orderType, $account
)
 {
  ordersModel::create([
     'order_id' => $orderId,
     'stockId' => $stockId,
     'cName'    =>$cName,
     'cPhone'   => $cPhone,
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
            $create->description = $quantity.'(Qty) Stock Updated By '.Auth::user()->name;
            $create->save();
        }
    }

    private function reduceProductQuantity($productId, $quantity)
    {
        $reduce = productsModel::where('account', getCurrentShopId())->where('product_id', $productId)->first();
        if ($reduce) {
            $reduce->quantity -= $quantity;
            $reduce->save();

            $create = new logModal();
            $create->title = 'Product Log';
            $create->description = $productId .' (Product) deducted to '. $reduce->quantity .'  Successfully By '.Auth::user()->name;
            $create->save();
        }

        // Also decrement stock.quantity so that returnSaleToOrder can correctly restore it
        $stockRec = stock::where('account', getCurrentShopId())
            ->where('productId', $productId)
            ->where('quantity', '>', 0)
            ->orderBy('id', 'desc')
            ->first();
        if ($stockRec) {
            $stockRec->quantity = max(0, (int)$stockRec->quantity - (int)$quantity);
            $stockRec->save();
        }
    }

    public function updateOrder(Request $req)
    {
        $user = Auth::user();
        $OrderName = $req->input('OrderName');
        $orders = ordersModel::where('account', getCurrentShopId())->where('orderName', $OrderName)->first() ?? (object)[
            'order_id' => '',
            'orderName' => '',
            'cName' => '',
            'cPhone' => '',
            'served_by' => '',
            'status' => '',
        ];
       $allShops = getUserAccounts();
    $shopIds = array_column($allShops, 'id');
       
        $data = compact(
            'newOrder','orders', 'allShops'
        );

        return view('newOrder', $data);
 
     }

      public function updQuant(Request $req)
    {
        $OrdersIds = $req->input('OrdersIds');
        $prodId = $req->input('prodId');
        $prodQuantit = $req->input('prodQuantity');
        
        $look = ordersModel::where('account', getCurrentShopId())->where('order_id', $OrdersIds)
                               ->where('productId', $prodId)
                               ->first();
       $pr =  $look->productPrice;

       $look->pQuantity = $prodQuantit; 
       $look->totalPrice = ($prodQuantit * $pr);
       $look->save();


        $create = new logModal();
            $create->title = 'Product Logs';
            $create->description = $look->name01.' Product Updated By '.Auth::user()->name;
            $create->save();

        return redirect()->back()->with('success', 'Product Quantity Updated Successfully');
    }
      public function updDisc(Request $req)
    {
        $OrdersIds = $req->input('OrdersIds');
        $prodId = $req->input('prodId');
        $prodQuantit = $req->input('discAmount');
        
        $look = ordersModel::where('account', getCurrentShopId())->where('order_id', $OrdersIds)
                               ->where('productId', $prodId)
                               ->first();

       $look->discount = $prodQuantit;
       $look->save();

        $create = new logModal();
            $create->title = 'Product Logs';
            $create->description = $look->name01.' Product Updated By '.Auth::user()->name;
            $create->save();
            
        return redirect()->back()->with('success', 'Product Discount Updated Successfully');
    }

    public function removeOfferItem(Request $req)
    {
        $orderId = $req->input('orderId');
        $productId = $req->input('productId');
        $quantity = $req->input('quantity', 0);

        $offerItem = ordersModel::where('account', getCurrentShopId())
            ->where('order_id', $orderId)
            ->where('productId', $productId)
            ->where('offered_items', 1)
            ->first();

        if (!$offerItem) {
            return response()->json(['success' => false, 'message' => 'Offer item not found']);
        }

        $restoredQty = $q = (int) ($offerItem->pQuantity ?? 0);

        $this->restoreProductQuantity($productId, $restoredQty);

        $offerItem->delete();

        return response()->json(['success' => true, 'restoredQty' => $restoredQty]);
    }

    public function dltProdOrdcart(Request $req)
{
    $OrdersIds = $req->input('orderId');
    $prodId = $req->input('itemId');
    $prodQuantit = $req->input('prodQuantity');
    
    try {
        // Get the IDs first
        $productIds = ordersModel::where('account', getCurrentShopId())
                                ->where('order_id', $OrdersIds)
                                ->where('productId', $prodId)
                                ->pluck('id');
                
        if ($productIds->isEmpty()) {
            return redirect()->back()->with('error', 'Product Not Found');
        }
        
        // Try direct delete using query builder
        $deletedCount = ordersModel::whereIn('id', $productIds)->delete();
        
        
        if ($deletedCount > 0) {
            return redirect()->back()->with('success', $deletedCount . ' Product(s) Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete products');
        }
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function clearCart(Request $req)
    {
        $orderId = $req->input('orderId');
        if (empty($orderId)) {
            return redirect()->back()->with('error', 'Order not found');
        }

        try {
            ordersModel::where('account', getCurrentShopId())
                ->where('order_id', $orderId)
                ->where('offered_items', '!=', 1)
                ->delete();

            return redirect()->back()->with('success', 'Cart cleared successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function dltProdOrd(Request $req)
    {
        $OrdersIds = $req->input('OrdersIds');
        $prodId = $req->input('prodId');
        $prodQuantit = $req->input('prodQuantity');
        
        $deltProduct = ordersModel::where('account', getCurrentShopId())->where('order_id', $OrdersIds)
                               ->where('productId', $prodId)
                               ->first();

        if (!$deltProduct) {
            return redirect()->back()->with('error', 'Product Not Found');
        }

        if($deltProduct) {
            $products = productsModel::where('account', getCurrentShopId())->where('product_id', $prodId)->first();
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
            $create->description = $deltProduct->name01.' Product Deleted By '.Auth::user()->name;
            $create->save();
        return redirect()->back()->with('success', 'Product Deleted Successfully');
    }

    private function restoreProductQuantity($productId, $quantity)
    {
        $updt = productsModel::where('account', getCurrentShopId())->where('product_id', $productId)->first();
        if ($updt) {
            $updt->quantity += $quantity;

            $create = new logModal();
            $create->title = 'Product Logs';
            $create->description = $updt->name01.' Product Quantity Restored '. $quantity .' By '.Auth::user()->name;
            $create->save();
            $updt->save();
        }
    }

    private function reverseStockUpdate($order)
    {
        $prodId = $order->productId;
        $stoc = stock::where('account', getCurrentShopId())
                    ->where('productId', $prodId)
                   ->where('name', $order->stockId)
                   ->first();

        if ($stoc) {
            $stoc->profit -= (($stoc->sPrice * $stoc->sQuantity) - ($stoc->bPrice * $stoc->sQuantity));
            $stoc->sQuantity -= ($order->pQuantity ?? 0);
            $stoc->amount -= ($order->totalPrice ?? 0);

             $create = new logModal();
            $create->title = 'Stock Logs';
            $create->description = $stoc->name.' Stock Restored By '.Auth::user()->name;
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

  $userName = Auth::user()->name;
  $shopName = getCurrentShopId();

  // Handle chip payment validation and deduction
  if ($paymentMethod === 'chip') {
      if ($chipAmount <= 0) {
          return back()->with('error', 'Chip amount is required when payment method is chip');
      }
      if ($chipAmount > $amount) {
          return back()->with('error', 'Chip amount cannot exceed total payment amount');
      }
      
      // Get available chip from last chip entry (cumulative total)
      $shop = accountModel::where('id', $shopName)->first();
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

      $orders = ordersModel::where('account', getCurrentShopId())
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

  // Recalculate customer debt after payment

  return redirect('ordersList')
      ->with('success', 'Payment distributed successfully');
}
/**
 * Recalculate and update customer's total debt from their orders
 */




public function payout(Request $req)
{
   return DB::transaction(function () use ($req) {
       $OrdersIds    = $req->input('orderId');
   $orderType    = $req->input('orderType');
   $paymentMethod = $req->input('paymentMethod');
   $served = $req->input('served');
   $saleDate = $req->input('saleDate'); 
   $saletype = session('orderType');
   /* ============================
      FETCH ORDERS
   ============================ */

    $orders = ordersModel::where('account', getCurrentShopId())
        ->where('order_id', $OrdersIds)
        ->lockForUpdate()
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

     $existingSale = salsModel::where('sales_id', $OrdersIds)
         ->where('account', getCurrentShopId())
         ->where('status', '!=', 'Return')
         ->exists();

     if ($existingSale) {
         return back()->with('error', 'This order has already been processed');
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
            'description' => $OrdersIds . ' Order Suspended By ' . Auth::user()->name,
        ]);

        return back()->with('success', 'Order Suspended');
    }

    /* ============================
       RETURN LOGIC
    ============================ */
    
    if ($saletype === 'Return') {
        // For returns: amount goes to return column, not paid or credit
        $return_amount = $paid + $credit;
        $finalStatus = 'Return';

        foreach ($orders as $order) {
            $order->status = 'Return';
            $order->paid = 0;
            $order->credit = 0;
            $order->return_amount = $return_amount;
            $order->save();
            
            // Restore product quantity
            $products = productsModel::where('account', getCurrentShopId())->where('product_id', $order->productId)->first();
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
                'totalPrice'     => $order->totalPrice,
                'return_amount'  => $order->return_amount ?? 0,
                'paid'           => 0,
                'credit'         => 0,
                'transactionType' => $paymentMethod,
                'status'          => 'Return',
                'discount'        => 0,
                'discount_increase' => 0,
                'offered_items'     => $order->offered_items ?? 0,
                'offer_parent_products' => $order->offer_parent_products ?? null,
                'served_by'       => $served ?? Auth::user()->name,
                'account'         => $order->account,
                'created_at'      => $saleDate ?? Carbon::now(),
            ]);
        }

        logModal::create([
            'title' => 'Order Logs',
            'description' => $OrdersIds . ' Order Returned By ' . Auth::user()->name,
        ]);
         //forget this session
   session()->forget('orderType');
        return back()->with('success', 'Return processed successfully');
    }

    if($paymentMethod === 'Credit') {
        $paid = 0;
        $credit = $totalAmount;
    }
      /* ============================
       FINAL STATUS DERIVATION
    ============================ */
    if ($paid == $totalAmount && $totalAmount > 0) {  
        
        $finalStatus = 'Paid';
    } elseif ($credit == $totalAmount && $totalAmount > 0) {
        $finalStatus = 'Debt';
    } else {
        $finalStatus = 'Partial';
    }


    /* ============================
       DEBT LIMIT CHECK (CREDIT ONLY)
    ============================ */
    if($finalStatus === 'Debt' || $finalStatus === 'Partial') {
        $existingDebt = \DB::query()
        ->fromSub(
            ordersModel::where('account', getCurrentShopId())
                ->where('cName', $firstOrder->cName)
                ->where('cPhone', $firstOrder->cPhone)
                ->whereIn('status', ['Debt', 'Partial'])
                ->selectRaw('order_id, MAX(credit) as credit')
                ->groupBy('order_id'),
            'o'
        )
        ->sum('credit');

        $paidinv = debtsModel::where('account', getCurrentShopId())
            ->where('cId', $firstOrder->cPhone)
            ->sum('amount');

        if (!$customer) {
            return back()->with('error', 'Customer not found');
        }

        $limit = (float) ($customer->limits ?? 0);
        $newDebt = (float) $credit;

        if($limit < 1) {
           return back()->with('error', 'Customer has no credit limit set. Please set a credit limit to proceed with debt payment.');
        }
        if ((($existingDebt + $newDebt) - $paidinv) > $limit) {
            return back()->with(
                'error',
                'Credit ' . $newDebt . ' exceeds limit. Current debt ' .
                $existingDebt . ' / ' . $limit
            );
        }

    }

  

   
    /* ============================
       UPDATE ORDERS
    ============================ */
    $isFirstOrderRow = true;
    foreach ($orders as $order) {
        $order->status = $finalStatus;
        if ($isFirstOrderRow) {
            $order->paid = $paid;
            $order->credit = $credit;
            $isFirstOrderRow = false;
        } else {
            $order->paid = 0;
            $order->credit = 0;
        }
        $order->save();
    }



    /* ============================
       INSERT SALES FOR REGULAR ITEMS
    ============================ */

    \Log::info('Starting sales creation for order ' . $OrdersIds . ' with ' . $orders->count() . ' items');

    // First, process regular items (non-offered)
    $isFirstSaleRow = true;
    foreach ($orders as $order) {
        // Skip offered/free items in this loop - they will be processed separately
        $isOfferedItem = ($order->offered_items == 1 || $order->offered_items === true);
        if ($isOfferedItem) {
            \Log::info('Skipping offered item in regular loop: ' . $order->productId);
            continue;
        }
        
        
        // Deduct stock for regular items only
        $this->reduceProductQuantity($order->productId, $order->pQuantity);
        
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
            'paid'           => $isFirstSaleRow ? $paid : 0, 
            'credit'         => $isFirstSaleRow ? $credit : 0,
            'transactionType' => $paymentMethod,
            'status'          => $finalStatus,
            'discount'        => $order->discount,
            'discount_increase' => $order->discount_increase ?? 0,
            'served_by'       => $served ?? Auth::user()->name,
            'account'         => $order->account,
            'offered_items'   => 0, // Regular item
            'offer_parent_products' => null,
            'created_at'      => $saleDate ?? Carbon::now(),
        ]);

        $isFirstSaleRow = false;
    }

    /* ============================
       SAVE OFFERED ITEMS (FREE ITEMS) - FIXED
       Fetch from orders table where offered_items = 1
    ============================ */
    
    $offeredOrders = ordersModel::where('account', getCurrentShopId())
        ->where('order_id', $OrdersIds)
        ->where('offered_items', 1)
        ->get();
    
    \Log::info('Found ' . $offeredOrders->count() . ' offered items for order ' . $OrdersIds);
    
    if ($offeredOrders->count() > 0) {
        foreach ($offeredOrders as $offerOrder) {
            $offerProductId = $offerOrder->productId;
            $offerQuantity = $offerOrder->pQuantity;
            
            \Log::info('Processing offered item: Product ID ' . $offerProductId . ', Quantity: ' . $offerQuantity);
            
            // Get the product details
            $offerProduct = productsModel::where('account', getCurrentShopId())
                ->where('product_id', $offerProductId)
                ->first();
            
            if (!$offerProduct) {
                \Log::warning('Offered product not found: ' . $offerProductId);
                session()->flash('warning', 'Offered product "' . $offerProductId . '" was not found in inventory.');
                continue;
            }
            
            // Use the product's actual selling price for the sales record
            $offerProductPrice = $offerProduct->sPrice ?? 0;
            
            // Check if enough stock available for the free item
            if ($offerProduct->quantity < $offerQuantity) {
                \Log::warning('Insufficient stock for offered item: ' . $offerProduct->name01 . ' (needed: ' . $offerQuantity . ', available: ' . $offerProduct->quantity . ')');
                
                // Add a warning message to be shown to the user
                session()->flash('warning', 'Free item "' . ($offerProduct->name01 ?? $offerProductId) . '" was skipped due to insufficient stock (needed: ' . $offerQuantity . ', available: ' . $offerProduct->quantity . ').');
                
                // Skip this free item - do not add to sales or deduct stock
                continue;
            }
            
            // Reduce stock for offered items (deduct now that order is finalized)
            $this->reduceProductQuantity($offerProductId, $offerQuantity);
            
            // Create sales record (FREE item - totalPrice is 0 but productPrice stores original price)
            try {
                $salesRecord = salsModel::create([
                    'sales_id'       => $OrdersIds,
                    'salesName'      => $firstOrder->orderName ?? 'N/A',
                    'stockId'        => $offerOrder->stockId ?? 'OFFER',
                    'cName'          => $firstOrder->cName ?? 'N/A',
                    'cPhone'         => $firstOrder->cPhone ?? 'N/A',
                    'productId'      => $offerProductId,
                    'pQuantity'      => $offerQuantity,
                    'productPrice'   => $offerProductPrice,
                    'totalPrice'     => 0, // Free item - no charge
                    'return_amount'  => 0,
                    'paid'           => 0,
                    'credit'         => 0,
                    'transactionType' => $paymentMethod,
                    'status'          => $finalStatus,
                    'discount'        => 0,
                    'discount_increase' => 0,
                    'served_by'       => $served ?? Auth::user()->name,
                    'account'         => getCurrentShopId(),
                     'offered_items'   => 1, // Mark as offered item
                     'offer_parent_products' => $offerOrder->offer_parent_products ?? null,
                     'created_at'      => $saleDate ?? Carbon::now(),
                ]);
                
                \Log::info('Successfully created sales record for offered item: ' . $salesRecord->id);
                
                logModal::create([
                    'title' => 'Offer Logs',
                    'description' => 'Offered item: ' . ($offerProduct->name01 ?? $offerProductId) . ' x' . $offerQuantity . ' added to order ' . $OrdersIds . ' by ' . Auth::user()->name,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create sales record for offered item: ' . $e->getMessage());
                session()->flash('error', 'Failed to process offered item: ' . ($offerProduct->name01 ?? $offerProductId));
            }
        }
    }

    /* ============================
       DELETE ONLY FULL SALES
    ============================ */

    if ($finalStatus === 'Paid') {
        ordersModel::where('account', getCurrentShopId())
            ->where('order_id', $OrdersIds)
            ->delete();
    }

    /* ============================
        LOGGING
    ============================ */
 //forget this session
   session()->forget('orderType');

    logModal::create([
        'title' => 'Order Logs',
        'description' => $OrdersIds . ' Order Sold Successfully By ' . Auth::user()->name,
    ]);
    //play sound
     session()->flash('play_sound', true);
     
    return back()->with(
        'success',
        'Processed: Paid ' . $paid . ' | Credit ' . $credit
    );
    
   });
}

    public function viewOrder(Request $req)
    {
        $user = Auth::user();
        $paidSoFar = 0;
        $customerId = $req->input('customerId');
        
        // Get all order items for this customer (Debt or Partial status)
        $Orders = ordersModel::where('account', getCurrentShopId())
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
        $shop = accountModel::where('id', getCurrentShopId())->first();
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

            return view('viewOrder', $data);
   
    }

    public function coupon(Request $req)
    {
        $orderName = $req->input('orderName');
        $coupon = $req->input('coupon_code');
        $coupons = couponModel::where('account', getCurrentShopId())->where('couponCode', $coupon)->first();
    
        if (!$coupons) {
            return redirect()->back()->with('error', 'Coupon Code is incorrect');
        }

        if ($coupons->status == 'Used') {
            return redirect()->back()->with('error', 'Coupon Code is Used');
        }

        if ($coupons->expire < date("Y-m-d")) {
            return redirect()->back()->with('error', 'Coupon Code has expired');
        }

        $updt = ordersModel::where('account', getCurrentShopId())->where('orderName', $orderName)->first();
        if ($updt) {
            $updt->coupons = $coupon;
            $updt->save();
        }

        $coupons->status = "Used";
        $coupons->save();

         $create = new logModal();
            $create->title = 'Coupon Logs';
            $create->description = $updt->coupons.' Coupon Accepted to order '.$updt->orderName.'  By '.Auth::user()->name;
            $create->save();

        return redirect()->back()->with('success', 'Coupon is accepted');
    }

    public function discount(Request $req)
    {
        $orderName = $req->input('orderName');
        $discount = $req->input('discount');
        $updt = ordersModel::where('account', getCurrentShopId())->where('orderName', $orderName)->first();
    
        if ($updt) {
            $updt->discount = $discount;
            $updt->save();

             $create = new logModal();
            $create->title = 'Discount Logs';
            $create->description = $updt->orderName.' Discount added to order  By '.Auth::user()->name;
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
        
        $invoices = ordersModel::where('account', getCurrentShopId())->where('orderName', $invName)->get();

        $data = compact(
        'invoices'
    );

     return view('viewInvoice', $data);

    }

public function changeShop(Request $request)
{
    $shopId = $request->query('shop_id');
    $user = Auth::user();
    $selectedShopId = $shopId;
    
    
    // If shop is valid, set it in session
    if ($selectedShopId) {
        session(['selected_shop_id' => $selectedShopId]);
        $shop = accountModel::find($selectedShopId);
        
        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Shop changed to ' . ($shop->name ?? 'Unknown'),
                'shop_id' => $selectedShopId,
                'shop_name' => $shop->name ?? 'Unknown'
            ]);
        }
        
        return redirect()->back()->with('success', 'Shop changed to ' . ($shop->name ?? 'Unknown'));
    }
    
    // Invalid shop selection
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid shop selection or you do not have access to this shop'
        ], 403);
    }
    
    return redirect()->back()->with('error', 'Invalid shop selection');
}
    public function deleteOrder(Request $req)
    {
        $orderId = $req->input('orderId');
        
        if (empty($orderId)) {
            return redirect()->back()->with('error', 'No order specified');
        }
        
        // Get all order items with this order_id
        $orders = ordersModel::where('account', getCurrentShopId())
            ->where('order_id', $orderId)
            ->orWhere('orderName', $orderId)
            ->get();
            
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Order not found');
        }
        
        // Begin transaction to ensure data consistency
        DB::transaction(function () use ($orders, $orderId) {
            foreach ($orders as $order) {
                // Restore product quantity
                $product = productsModel::where('account', getCurrentShopId())
                    ->where('product_id', $order->productId)
                    ->first();
                if ($product) {
                    $product->quantity += ($order->pQuantity ?? 0);
                    $product->save();
                }
                
                // Reverse stock update
                $this->reverseStockUpdate($order);
            }
            
            // Delete all order items
            ordersModel::where('account', getCurrentShopId())
                ->where('order_id', $orderId)
                ->delete();
        });
        
        $create = new logModal();
        $create->title = 'Order Logs';
        $create->description = 'Order ' . $orderId . ' deleted by ' . Auth::user()->name;
        $create->save();
        
        return redirect()->back()->with('success', 'Order deleted successfully');
    }
}
