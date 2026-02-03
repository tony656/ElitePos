<?php

namespace App\Http\Controllers;

use App\Models\customerModel;
use App\Models\logModal;
use App\Models\ordersModel;
use App\Models\productsModel;
use App\Models\recevingModel;
use App\Models\vendorModal;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Models\stock;
use Illuminate\Support\Facades\DB;
use App\Models\madeni;
use App\Models\accountModel;
use Illuminate\Support\Facades\Auth;


class productsController extends Controller
{
    public function index() {
        $vendor = null;
        $user = Auth::user();

        $products = productsModel::where('account', session('account'))->where('name01', '!=', null)->get();

        foreach($products as $product) {

        $vendor = vendorModal::where('account', session('account'))->where('id', '=', $product->supplier)->first();
        }
          $TProducts = DB::table('products')->where('account', session('account'))->count();

          $ofs = DB::table('products')->where('account', session('account'))->where('quantity', '<', 1)->count();

          $cureemtMonth = date("Y-m"); 
                                $CMofs = DB::table('products')
                                                ->where('account', session('account'))->where('expire', '<', $cureemtMonth)
                                                ->count();
        $getAllAccounts = accountModel::where('name', '!=', session('account'))->get();

        $data = compact(
        'products','getAllAccounts','vendor','TProducts','ofs','CMofs'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.products', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.products', $data);
    }
    }

      public function getAllAccounts()
    {
        try {
            $currentAccount = session('account');
            $accounts = accountModel::where('name', '!=', $currentAccount)->get();
            
            return response()->json([
                'success' => true,
                'accounts' => $accounts
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load accounts'
            ], 500);
        }
    }

    // Method to duplicate products to another account
    public function duplicateProducts(Request $request)
    {
        try {

            $productIds = $request->input('product_ids');
            $targetAccount = $request->input('target_account');
            $includeStock = $request->input('include_stock', false);
            $includePricing = $request->input('include_pricing', false);

            // Check if target account exists
            if (!accountModel::where('name', $targetAccount)->exists()) {
                return redirect()->back()->with('error', 'Target account does not exist');
            }
            
            $currentAccount = session('account');
            $duplicatedCount = 0;
            
            // Log for tracking
            $create = new logModal();
            $create->title = 'Product Duplication';
            $create->description = 'Starting product duplication from ' . $currentAccount . ' to ' . $targetAccount;
            $create->save();

            foreach ($productIds as $productId) {
                // Get the original product
                $originalProduct = productsModel::where('product_id', $productId)
                    ->where('account', $currentAccount)
                    ->first();

                if (!$originalProduct) {
                    continue; // Skip if product not found
                }
                    // Check if product already exists in target account
                    $existingProduct = productsModel::where('name01', $originalProduct->name01)
                        ->where('account', $targetAccount)
                        ->first();

                    if ($existingProduct) {
                        // Update existing product
                        $existingProduct->name02 = $originalProduct->name02;
                        $existingProduct->category = $originalProduct->category;
                        $existingProduct->unit = $originalProduct->unit;
                        
                        if ($includePricing) {
                            $existingProduct->bPrice = $originalProduct->bPrice;
                            $existingProduct->sPrice = $originalProduct->sPrice;
                            $existingProduct->wholesale = $originalProduct->wholesale;
                        }
                        
                        if ($includeStock) {
                            $existingProduct->quantity = ($existingProduct->quantity ?? 0) + ($originalProduct->quantity ?? 0);

                        }
                        
                        $existingProduct->save();
                    } else {
                        // Create new product in target account
                        $newProduct = new productsModel();
                        $newProduct->product_id = $productId;
                        $newProduct->name01 = $originalProduct->name01;
                        $newProduct->name02 = $originalProduct->name02;
                        $newProduct->category = $originalProduct->category;
                        $newProduct->unit = $originalProduct->unit;
                        $existingProduct->supplier = $originalProduct->supplier;
                        $existingProduct->location = ($targetAccount);
                        $existingProduct->expire = $originalProduct->expire;
                        $newProduct->description = $originalProduct->description;

                          if ($includePricing) {
                            $newProduct->bPrice = $originalProduct->bPrice;
                            $newProduct->sPrice = $originalProduct->sPrice;
                            $newProduct->wholesale = $originalProduct->wholesale;
                        }
                        
                        if ($includeStock) {
                            $newProduct->quantity += ($originalProduct->quantity ?? 0);
                        } else {
                            $newProduct->quantity = 0;
                        }

                        $newProduct->account = $targetAccount;
                        $newProduct->save();
                    }
                    
                    
            $uuid_short = 'Stock-'.date(format: 'YMd') . '-' . str_pad(stock::where('account', operator: $targetAccount)->whereDate('created_at', date('Y-m-d'))->count() + 1, 4, '0', STR_PAD_LEFT).'-'.$originalProduct->name01.'-'.$originalProduct->name02;

            $restock = new stock();
            $restock->name = $uuid_short;
            $restock->productId = $originalProduct->product_id;
            $restock->quantity = $originalProduct->quantity;
            $restock->tBprice = $originalProduct->quantity * $originalProduct->bPrice;
            $restock->bPrice = $originalProduct->bPrice;
            $restock->sPrice = $originalProduct->sPrice;
            $restock->account = $targetAccount;
            $restock->save();

            if($restock) {
                $create = new logModal();
            $create->title = 'Stock Created';
            $create->description = $uuid_short.'  Created successfully By '.session('username');
            $create->save();
            } else {
                 $create = new logModal();
            $create->title = 'Stock Creation Failed';
            $create->description = $uuid_short.'  Creation Failed By '.session('username');
            $create->save();
            }
                $duplicatedCount++;
                    // Log individual product duplication
                    $productLog = new logModal();
                    $productLog->title = 'Product Duplicated';
                    $productLog->description = 'Product "' . $originalProduct->name01 . '" duplicated to ' . $targetAccount;
                    $productLog->save();
                }
            

            // Log completion
            $completeLog = new logModal();
            $completeLog->title = 'Product Duplication Complete';
            $completeLog->description = 'Successfully duplicated ' . $duplicatedCount . ' products from ' . $currentAccount . ' to ' . $targetAccount . ' by ' . session('username');
            $completeLog->save();

            return redirect()->back()->with('success', 'Products duplicated successfully! Duplicated ' . $duplicatedCount . ' products.');
        } catch (\Exception $e) {
            \Log::error('Product duplication failed: ' . $e->getMessage());

            $errorLog = new logModal();
            $errorLog->title = 'Product Duplication Failed';
            $errorLog->description = 'Failed to duplicate products: ' . $e->getMessage();
            $errorLog->save();

            return redirect()->back()->with('error', 'Failed to duplicate products: ' . $e->getMessage());
        }
    }

    // Helper method to generate new product ID
    private function generateProductId($account)
    {
        $lastProduct = productsModel::where('account', $account)
            ->orderBy('id', 'desc')
            ->first();
            
        if ($lastProduct) {
            $lastId = intval(substr($lastProduct->product_id, 3));
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }
        
        return 'PID' . str_pad($newId, 4, '0', STR_PAD_LEFT);
    }
    public function report() {
        $user = Auth::user();

        $report = stock::where('account', session('account'))->orderBy('id', 'desc')->get();

        $create = new logModal();
            $create->title = 'Products Report';
            $create->description = 'Report Generated By '.session('username');
            $create->save();

            $data = compact(
        'report'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.stock', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.stock', $data);
    }

        
    }

      public function saveProduct(Request $req) {
          
        // Get the input values
        $name01 = $req->input('name01');
        $name02 = $req->input('name02');
        $category = $req->input('category');
        $description = $req->input('description');
        $unit = $req->input('unit');
        $quantity = $req->input('quantity');
        $bPrice = $req->input('bPrice');
        $sPrice = $req->input('sPrice');
        $wholesale = $req->input('wholesale');
        $discount = $req->input('discount');
        $location = $req->input('location');
        $supplier = $req->input('supplier');
        $expiry = $req->input('expiry');
    
        // Handle the image upload
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); // Create a unique filename
            $image->move(public_path('images'), $imageName); // Move the image to the public/images folder
        } else {
            $imageName = 'default.png';
        }

$uuid = Uuid::uuid4();

$randomNumbers = [];
while (count($randomNumbers) < 6) {
    $randomNumber = rand(100000, 999999);
    if (!in_array($randomNumber, $randomNumbers)) {
        $randomNumbers[] = $randomNumber;
    }
}

        // Create a new product model instance
        $productsModel = new productsModel();
        $productsModel ->product_id = $uuid;
        $productsModel->name01 = $name01;
        $productsModel->name02 = $name02;
        $productsModel->category = $category;
        $productsModel->description = $description;
        $productsModel->unit = $unit;
        $productsModel->img = $imageName; // Store the filename in the database
        $productsModel->quantity = $quantity;
        $productsModel->code = $randomNumbers[array_rand($randomNumbers)];        $productsModel->bPrice = $bPrice;
        $productsModel->sPrice = $sPrice;
        $productsModel->wholesale = $wholesale;
        $productsModel->discount = $discount;
        $productsModel->location = $location;
        $productsModel->supplier = $supplier;
        $productsModel->expire = $expiry;
        $productsModel->account = session('account');
        $productsModel->save();
    
        if($productsModel) {
    
                $create = new logModal();
            $create->title = 'Product Created';
            $create->description = 'Product Created successfully By '.session('username');
            $create->save();

        $uuid_short = 'Stock-'.date(format: 'YMd') . '-' . str_pad(stock::where('account', session('account'))->whereDate('created_at', date('Y-m-d'))->count() + 1, 4, '0', STR_PAD_LEFT).'-'.$name01.'-'.$name02;

            $Tbp = $quantity * $bPrice;

            $restock = new stock();
            $restock->name = $uuid_short;
            $restock->productId = $uuid;
            $restock->quantity = $quantity;
            $restock->tBprice = $Tbp;
            $restock->bPrice = $bPrice;
            $restock->sPrice = $sPrice;
            $restock->account = session('account');
            $restock->save();

            if($restock) {
                $create = new logModal();
            $create->title = 'Stock Created';
            $create->description = $uuid_short.'  Created successfully By '.session('username');
            $create->save();
            } else {
                 $create = new logModal();
            $create->title = 'Stock Creation Failed';
            $create->description = $uuid_short.'  Creation Failed By '.session('username');
            $create->save();
            }

             return redirect()->back()->with('success', 'Product saved successfully!');
        } else {
            $create = new logModal();
            $create->title = 'Product Creation Failed';
            $create->description = 'Product Creation Failed By '.session('username');
$create->save();
             return redirect()->back()->with('error', 'Product Failed to save');
        }
       
       
    }

    public function viewProduct(Request $req) {

        $user = Auth::user();

        if (!empty($req->input('product_id'))) {
            $product_id = $req->input('product_id');
            session(['productId' => $product_id]);

        }
    
        $product_id = session('productId');
        $products = productsModel::where('account', session('account'))->where('product_id', '=', value: $product_id)->first();

       


        $vendor = vendorModal::where('account', session('account'))->where('id', '=', $products->supplier)->first();
         if(!$vendor) {
            $vendor = vendorModal::where('account', session('account'))->where('name', '=', $products->supplier)->first();
        }
        $data = compact(
        'products','vendor'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.viewProduct', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.viewProduct', $data);
    }
    }

    public function dltProduct(Request $Req) {

          if (!empty($Req->input('product_id'))) {
            $product_id = $Req->input('product_id');
            session(['productId' => $product_id]);

        }

         $product_id = session('productId');
        $dlt = productsModel::where('account', session('account'))->where('product_id', '=', value: $product_id)->first();

        $dlt->delete();
        if($dlt) {
            $create = new logModal();
            $create->title = 'Product delete';
            $create->description =  $dlt->name01.' Product deleted successfully By '.session('username');
            $create->save();

        $dltStock = stock::where('account', session('account'))->where('productId', '=', value: $product_id)->delete();

        if($dltStock) {
            $create = new logModal();
            $create->title = 'Stock report deleted';
            $create->description =  $dlt->name01.' Stock deleted successfully By '.session('username');
            $create->save();
        }
                
            return redirect()->back()->with('success', 'Product deleted successfully');
        } else {
            $create = new logModal();
            $create->title = 'Product delete';
            $create->description =  $dlt->name01.' Product delete Failed By '.session('username');
$create->save();
         return redirect()->back()->with('success', 'Product deletion failed');
        }
    }

    public function updateProducts(Request $req) {
        // Get the input values
        if (!empty($req->input(key: 'product_id'))) {
            $product_id = $req->input('product_id');
            session(['productId' => $product_id]);

        }
        $imageName = '';
    
        $product_id = session('productId');
     $name01 = $req->input('name01');
        $name02 = $req->input('name02');
        $category = $req->input('category');
        $description = $req->input('description');
        $unit = $req->input('unit');
        $quantity = $req->input('quantity');
        $bPrice = $req->input('bPrice');
        $sPrice = $req->input('sPrice');
        $wholesale = $req->input('wholesale');
        $discount = $req->input('discount');
        $location = $req->input('location');
        $supplier = $req->input('supplier');
        $expiry = $req->input('expiry');
    
        // Handle the image upload
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); // Create a unique filename
            $image->move(public_path('images'), $imageName);
            
            $create = new logModal();
            $create->title = 'Image Upload';
            $create->description =  $imageName.' Image moved to images folder By '.session('username');
       $create->save();
        }
    
        // Fetch the product
        $productsModel = productsModel::where('account', session('account'))->where('product_id', '=', $product_id)->first();
    
        // Check if the product exists
        if ($productsModel) {
        $productsModel->name01 = $name01;
        $productsModel->name02 = $name02;
        $productsModel->category = $category;
        $productsModel->description = $description;
        $productsModel->unit = $unit;
        $productsModel->img = $imageName; // Store the filename in the database
        $productsModel->quantity = $quantity;
        $productsModel->bPrice = $bPrice;
        $productsModel->sPrice = $sPrice;
        $productsModel->wholesale = $wholesale;
        $productsModel->discount = $discount;
        $productsModel->location = $location;
        $productsModel->supplier = $supplier;
        $productsModel->expire = $expiry;
    
            if ($req->hasFile('image')) {
                $productsModel->img = $imageName; // Update the image if a new one is uploaded
            }
    
            $productsModel->save();

            if($productsModel) {
                  $create = new logModal();
            $create->title = 'Product Update';
            $create->description =  $name01.' Product Updated By '.session('username');
$create->save();
             return redirect()->back()->with('success', 'Product updated successfully!');
            } else{
                 $create = new logModal();
            $create->title = 'Product Update';
            $create->description =  $name01.' Product Update Failed By '.session('username');
$create->save();
             return redirect()->back()->with('error', 'Product update Failed!');
            }
           
        } else {
            // Handle the case where the product does not exist
            return redirect()->back()->with('error', 'Product not found.');
        }
    }

    public function newOrder(){
        $user = Auth::user();

         $orders = ordersModel::where('account', session('account'))
    ->where('served_by', session('username'))
    ->whereNotIn('status', ['Suspended'])
    ->orderBy('id', 'desc')
    ->first();

        $Suspended = ordersModel::where('account', session('account'))
    ->where('served_by', session('username'))
    ->where('status', 'Suspended')
    ->get();

        $cart = ordersModel::where('account', session('account'))->where('served_by', session('username'))
        ->where('order_id',  $orders->order_id ?? '' )
        ->orderBy('id', 'desc')
        ->get();
         $totalP = ordersModel::where('account', session('account'))->where('served_by', session('username'))
        ->where('orderName',  $orders->orderName ?? '' )
        ->orderBy('id', 'desc')
        ->sum('totalPrice');
        $totalD = ordersModel::where('account', session('account'))->where('served_by', session('username'))
        ->where('orderName',  $orders->orderName ?? '' )
        ->orderBy('id', 'desc')
        ->sum('discount');
        $customers = customerModel::where('account', session('account'))->get();

         session(['totalP' => $totalP]);
        $data = compact(
        'cart','totalP','totalD','customers','orders','Suspended'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.newOrder', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.newOrder', $data);
    }
    }
   public function search(Request $request)
{
    

   // Check if user is authenticated
   if (!session('account')) {
       return response()->json(['error' => 'Unauthorized', 'redirect' => '/login'], 401);
   }

   $query = trim($request->query('query'));

   if ($query === '') {
       return response()->json([]);
   }

   $products = productsModel::where('account', session('account'))
       ->where('name01', 'LIKE', "%{$query}%")
       ->limit(10)
       ->get([
           'id',
           'product_id',
           'name01',
           'sPrice',
           'quantity',
           'discount'
       ]);

   return response()->json($products);
}


    public function restock(Request $req)
{
    $user = Auth::user();
    // Get selected date, default to today
    $selectedDate = $req->input('date', date('Y-m-d'));

    // Fetch daily purchases from stock table for the selected date
    $purchases = stock::where('account', session('account'))
                      ->whereDate('created_at', $selectedDate)
                      ->orderBy('created_at', 'desc')
                      ->get();
$products = recevingModel::where('account', session('account'))
                        ->orderBy('id', 'desc')
                        ->get();

$productIds = $products->pluck('productId')->unique();

$productMap = productsModel::whereIn('product_id', $productIds)
    ->pluck('name01', 'product_id');

foreach ($products as $item) {
    $item->productName = $productMap[$item->productId] ?? 'Unknown';
}

    // Only handle POST submissions
    if ($req->isMethod('post') && !empty($req->input('product_id'))) {

        $supplier = $req->input('supplier');
        $product_ids = $req->input('product_id');
        $quantities = $req->input('quantity');
        $bPrices = $req->input('bPrice');
        $sPrices = $req->input('sPrice');
        $wholesales = $req->input('wholesale');
         $types = $req->input('type');
        $expiries = $req->input('expiry');
        $served = $req->input('served');

        if(empty($product_ids) || empty($quantities) || empty($bPrices) || empty($sPrices) || empty($wholesales) || empty($types) || empty($served)) {
            return redirect()->back()->with('error', 'Please fill in all required fields.');
        }
        foreach ($product_ids as $index => $product_id) {

            $quantity = (int)$quantities[$index];
            $bPrice = (float)$bPrices[$index];
            $sPrice = (float)$sPrices[$index];
            $wholesale = (float)$wholesales[$index];
            $expiry = $expiries[$index];
            $type = (string)$types[$index];
            if($type == 'Credit') {
                $transaction = 1;
            } else {
                $transaction = 0;
                }
            
            $product = recevingModel::where('account', session('account'))->where('status', '!=', 'Approved')->where('productId', $product_id)->first();

            if ($product) {
                return redirect()->back()->with('error', 'Product is available in stock please restock first');   

} else {
                $product = new recevingModel();
                $product->productId = $product_id;      
                $product->quantity += $quantity;
                $product->price = $bPrice;
                $product->sellingPrice = $sPrice;
                $product->wholesalePrice = $wholesale;
                $product->isDebt = $transaction;
                $product->expiry = $expiry;
                $product->supplier = $supplier;
                $product->account = session('account');
                $product->served_by = $served;

                $product->save();
}
                if($product) {
            $create = new logModal();
            $create->title = 'Stock Log';
            $create->description =  '  New Stock Addedd By '.session('username');
            $create->save();
                }

                // Generate stock name
          $productInfo = productsModel::where('product_id', $product_id)->first();

$uuid_short = 'Stock-' . date('Ymd') . '-' . str_pad(
    stock::where('account', session('account'))
        ->whereDate('created_at', date('Y-m-d'))
        ->count() + 1,
    4, '0', STR_PAD_LEFT
) . '-' . ($productInfo->name01 ?? 'Unknown');

                // Save restock record
                $restock = new stock();
                $restock->name = $uuid_short;
                $restock->productId = $product_id;
                $restock->quantity = $quantity;
                $restock->bPrice = $bPrice;
                $restock->sPrice = $sPrice;
                $restock->tBprice = $quantity * $bPrice;
                $restock->account = session('account');
                $restock->save();

                if($restock) {

                     $create = new logModal();
            $create->title = 'Stock Log';
            $create->description =  '  New Stock Addedd By '.session('username');
            $create->save();
                }
            
        }
    }

 $data = compact(
        'products','purchases','selectedDate'
    );

 if ($user->levelStatus === 'Admin') {
        return view('admin.restock', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.restock', $data);
    }
    
}


  public function restockProd(Request $req)
{
    $product_id = $req->input('product_id');

    // Lock the record to prevent race conditions
    $receivings = recevingModel::where('account', session('account'))
                ->where('productId', $product_id)
                ->lockForUpdate()  // Add this line
                ->first();

    // Check if already approved
    if (!$receivings || $receivings->status == 'Approved') {
        return redirect()->back()->with('error', 'This product has already been approved');
    }

    $product = productsModel::where('product_id', $product_id)
                ->where('account', session('account'))
                ->first();

    if (!$product) {
        return redirect()->back()->with('error', 'Product not found');
    }

    // Update main stock
    $product->quantity += $receivings->quantity;
    $product->supplier = $receivings->supplier;
    $product->save();

    // Update receiving status
    $receivings->status = 'Approved';
    $receivings->save();

    // Create log
    $create = new logModal();
    $create->title = 'Stock Log';
    $create->description = $product->name01.' Stock Added to be used By '.session('username');
    $create->save();

    return redirect()->back()->with('success', 'Product restocked successfully!');
}
    public function returnStock(Request $req) {

        $product_id = $req->input('product_id');
        $stockQ = $req->input('stockQ');

        $get = productsModel::where('account', session('account'))->where('product_id', '=', $product_id)->first();

        $stockQuant = $get->stock2;

        if($stockQ == $stockQuant) {

            $get->stock2 = NULL;
            $get->stock2_expiry = NULL;
            $get->stock2_supplier = NULL;
            $get->stock2_bprice = NULL;
            $get->stock2_sprice = NULL;
            $get->save();

            $create = new logModal();
            $create->title = 'Stock Log';
            $create->description =  $get->name01.' Stock Returned '. $stockQ .' By '.session('username');
$create->save();
             return redirect()->back()->with('success', 'Returned Successfully');

        } elseif($stockQ < $stockQuant) {
            $newSt = $stockQuant - $stockQ;
            $get->stock2 = $newSt;
            $get->save();

            $create = new logModal();
            $create->title = 'Stock Log';
            $create->description =  $get->name01.' Stock Returned '. $stockQ .' By '.session('username');
$create->save();
            return redirect()->back()->with('success', 'Returned Successfully');

        } elseif ($stockQ > $stockQuant) {

            return redirect()->back()->with('success', '😒 wewe '.$get->unit.' '.$stockQ.' umezitoa wapi');
        }
    }

    public function dltrestock(Request $req) {


          if (!empty($req->input('product_id'))) {
            $product_id = $req->input('product_id');
            session(['productId' => $product_id]);

        }

         $product_id = session('productId');
        $get = recevingModel::where('account', session('account'))->where('productId', '=', value: $product_id)->first();        

        
        if($get) {


            $create = new logModal();
            $create->title = 'Receivings Log';
            $create->description =  $get->name01.' Receivings Deleted  By '.session('username');
$create->save();

               $get->delete();

            return redirect()->back()->with('success', 'Receivings deleted successfully');

        } else {

             $create = new logModal();
            $create->title = 'Receivings Log';
            $create->description =  $get->name01.' Receivings Delete Failed  By '.session('username');
$create->save();
         return redirect()->back()->with('success', 'Receivings deletion failed');
        }

    }

    public function madeni(Request $req) {

        $paymentAmount = $req->input('paymentAmount');
        $debtId = $req->input('debtId');

        $upd = madeni::where('debt_id', $debtId)->first();

        $upd->paid += $paymentAmount;
        $upd->save();

        if($upd) {
              $create = new logModal();
            $create->title = 'Debt Log';
            $create->description =  $upd->debt_id.' (Debt_id) This amount '. $paymentAmount.' is added to the debt  By '.session('username');
            $create->save();

            if($upd->paid >= $upd->amount) {

                   $create = new logModal();
            $create->title = 'Debt Log';
            $create->description =  $upd->debt_id.' (Debt_id) Debt is completed By '.session('username');
            $create->save();

                $upd->status = 'Completed';
                $upd->save();

            }
            return redirect()->back()->with('success', 'Debt Updated');
        } else {
            return redirect()->back()->with('error', 'Failed to update ');
        }

    }

}
