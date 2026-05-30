<?php

namespace App\Http\Controllers;

use App\Models\customerModel;
use App\Models\logModal;
use App\Models\Offer;
use App\Models\ordersModel;
use App\Models\productsModel;
use App\Models\recevingModel;
use App\Models\usersModel;
use App\Models\vendorModal;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use App\Models\stock;
use Illuminate\Support\Facades\DB;
use App\Models\madeni;
use App\Models\UserAccount;
use App\Models\accountModel;
use App\Models\itemRequestModel;
use App\Models\salsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function getCurrentShopId;

class productsController extends Controller
{
    public function index() {
        $vendor = null;
        $user = Auth::user();
        $perPage = request('per_page', 10000);
        $search = request('search', '');
        $sort = request('sort', 'name_asc');
        $shopFilter = request('shop', getCurrentShopId()); // Default to session account
        
        session(
            ['selected_shop_id' => $shopFilter] 
        );
        // Get user's assigned accounts (for both admin and user, but used differently)
        $userAccounts = $user->accounts()->pluck('account')->toArray();

        // Build base query based on user role
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin can see all products, optionally filtered by selected shop
            $baseQuery = productsModel::where('name01', '!=', null);
            
            // If a specific shop is selected, filter by that shop
            if (!empty($shopFilter)) {
                $baseQuery->where('account', $shopFilter);
            }
        } else {
            // Regular users can only see products from their assigned shops
            if (empty($userAccounts)) {
                // If user has no assigned shops, show no products
                $baseQuery = productsModel::where('id', '=', 0); // Empty result
            } else {
                $baseQuery = productsModel::whereIn('account', $userAccounts)
                    ->where('name01', '!=', null);
                
                // If a specific shop is selected and user has access to it, filter further
                if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                    $baseQuery->where('account', $shopFilter);
                }
            }
        }

        // When searching, don't apply pagination or sorting - show all results
        if ($search) {
            $productsQuery = $baseQuery->where(function($q) use ($search) {
                $q->where('name01', 'LIKE', "%{$search}%")
                  ->orWhere('name02', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhere('product_id', 'LIKE', "%{$search}%");
                });
            
            // Get all matching products without pagination
            $products = $productsQuery->get();
            
            // Convert to a LengthAwarePaginator-like object for compatibility
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                $products,
                $products->count(),
                max($products->count(), 1),
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            // Normal view — show ALL products (no pagination) but wrap in paginator for view compatibility
            $productsQuery = $this->applySorting($baseQuery, $sort);
            $allProducts = $productsQuery->get();
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                $allProducts,
                $allProducts->count(),
                max($allProducts->count(), 1),
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        foreach($products as $product) {

        $vendor = vendorModal::where('account', getCurrentShopId())->where('id', '=', $product->supplier)->first();
        }
          // Calculate stats based on filtered products
          $accountIds = strtolower(trim($user->levelStatus)) === 'admin'
              ? ($shopFilter ? [$shopFilter] : accountModel::where('id', '!=', getCurrentShopId())->pluck('id')->toArray())
              : ($shopFilter ? [$shopFilter] : $user->accounts()->pluck('account')->toArray());
          
          $TProducts = DB::table('products')
              ->whereIn('account', $accountIds)
              ->where('name01', '!=', null)
              ->count();

        // Calculate Inventory Worth based on Cost Price (bPrice)
        $totalCostWorth = DB::table('products')
            ->whereIn('account', $accountIds)
            ->where('name01', '!=', null)
            ->selectRaw('SUM(quantity * bPrice) as total')
            ->value('total') ?? 0;

        // Calculate Inventory Worth based on Selling Price (sPrice)
        $totalSellingWorth = DB::table('products')
            ->whereIn('account', $accountIds)
            ->where('name01', '!=', null)
            ->selectRaw('SUM(quantity * sPrice) as total')
            ->value('total') ?? 0;

        // Calculate Out of Stock products (quantity <= 0)
        $ofs = DB::table('products')
            ->whereIn('account', $accountIds)
            ->where('name01', '!=', null)
            ->where('quantity', '<=', 0)
            ->count();

        // Calculate Expired products
        $CMofs = DB::table('products')
            ->whereIn('account', $accountIds)
            ->where('name01', '!=', null)
            ->where('expire', '<', date('Y-m'))
            ->count();

        // Get all shops for admin filter dropdown
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $getAllAccounts = accountModel::all();
        } else {
            // For regular users, only show their assigned shops in the filter
            $getAllAccounts = accountModel::whereIn('id', $userAccounts ?? [])->get();
        }

        // Get all active offers for the current account (to show badge on products)
        $offers = Offer::where('account', getCurrentShopId())
            ->where('is_active', true)
            ->pluck('product_id')
            ->toArray();

        $data = compact(
        'products','getAllAccounts','vendor','TProducts','totalCostWorth','totalSellingWorth','ofs','CMofs','offers'
    );

 if (strtolower(trim($user->levelStatus)) === 'admin') {
       return view('admin.products', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.products', $data);
    }
    }

      public function getAllAccounts()
    {
        try {
            $currentAccount = getCurrentShopId();
            $accounts = accountModel::all();
            
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
            if (!accountModel::where('id', $targetAccount)->exists()) {
                return redirect()->back()->with('error', 'Target account does not exist');
            }
            
            $currentAccount = 7;
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
                    $existingProduct = productsModel::where('product_id', $originalProduct->product_id)
                        ->where('account', $targetAccount)
                        ->first();

                    if ($existingProduct) {
                        // Update existing product
                        $existingProduct->name01 = $originalProduct->name01;
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
                        $newProduct->supplier = $originalProduct->supplier;
                        $newProduct->location = ($targetAccount);
                        $newProduct->expire = $originalProduct->expire;
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
    /**
     * Apply sorting to products query based on sort parameter
     */
    private function applySorting($query, $sort) {
        switch ($sort) {
            case 'name_asc':
                return $query->orderBy('name01', 'asc');
            case 'name_desc':
                return $query->orderBy('name01', 'desc');
            case 'price_asc':
                return $query->orderBy('sPrice', 'asc');
            case 'price_desc':
                return $query->orderBy('sPrice', 'desc');
            case 'cost_asc':
                return $query->orderBy('bPrice', 'asc');
            case 'cost_desc':
                return $query->orderBy('bPrice', 'desc');
            case 'stock_asc':
                return $query->orderBy('quantity', 'asc');
            case 'stock_desc':
                return $query->orderBy('quantity', 'desc');
            case 'category_asc':
                return $query->orderBy('category', 'asc');
            case 'category_desc':
                return $query->orderBy('category', 'desc');
            case 'newest':
                return $query->orderBy('created_at', 'desc');
            case 'oldest':
                return $query->orderBy('created_at', 'asc');
            default:
                return $query->orderBy('name01', 'asc');
        }
    }

    public function report() {
        $user = Auth::user();

        $report = stock::where('account', getCurrentShopId())->orderBy('id', 'desc')->get();

        $create = new logModal();
            $create->title = 'Products Report';
            $create->description = 'Report Generated By '.session('username');
            $create->save();

            $data = compact(
        'report'
    );

 if (strtolower(trim($user->levelStatus)) === 'admin') {
       return view('admin.stock', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.stock', $data);
    }

        
    }

     public function saveProduct(Request $req) {
          \Log::info("saveProduct ENTRY: upload_type=" . $req->input('upload_type') . " | has file=" . ($req->hasFile('excel_file') ? 'yes' : 'no'));
          \Log::info("Request data: " . json_encode($req->all()));
          \Log::info("Files: " . json_encode($req->files->all()));
          
           $create = new logModal();
                 $create->title = 'Products Report';
                 $create->description = 'save product initiated '.$req->input('upload_type');
                 $create->save();
     
          $uploadType = $req->input('upload_type', 'manual');
       
          if ($uploadType === 'excel') {
              $create = new logModal();
              $create->title = 'Products Report';
              $create->description = 'Sending to excel';
              $create->save();
               return $this->handleExcelUpload($req);
          } else {
               return $this->handleManualProduct($req);
          }
      }

/**
 * Handle single product manual entry
 */
private function handleManualProduct(Request $req) {
    $req->validate([
        'name01' => 'required|string',
        'name02' => 'required|string',
        'category' => 'required|string',
        'unit' => 'required|string',
        'bPrice' => 'required|numeric',
        'sPrice' => 'required|numeric',
        'supplier' => 'required',
        'expiry' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
    ]);

    // Get the input values
    $name01 = $req->input('name01');
    $name02 = $req->input('name02');
    $category = $req->input('category');
    $description = $req->input('description', '');
    $unit = $req->input('unit');
    $quantity = $req->input('quantity', 0);
    $bPrice = $req->input('bPrice');
    $sPrice = $req->input('sPrice');
    $wholesale = $req->input('wholesale', 0);
    $discount = $req->input('discount', 0);
    $location = $req->input('location', '');
    $supplier = $req->input('supplier');
    $expiry = $req->input('expiry');

    // Handle the image upload
    if ($req->hasFile('image')) {
        $image = $req->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
    } else {
        $imageName = 'default.png';
    }

    $uuid = Uuid::uuid4();
    $productCode = $this->generateUniqueCode();

    // Create product
    $productsModel = new productsModel();
    $productsModel->product_id = $uuid;
    $productsModel->name01 = $name01;
    $productsModel->name02 = $name02;
    $productsModel->category = $category;
    $productsModel->description = $description;
    $productsModel->unit = $unit;
    $productsModel->img = $imageName;
    $productsModel->quantity = $quantity;
    $productsModel->code = $productCode;
    $productsModel->bPrice = $bPrice;
    $productsModel->sPrice = $sPrice;
    $productsModel->wholesale = $wholesale;
    $productsModel->discount = $discount;
    $productsModel->location = $location;
    $productsModel->supplier = $supplier;
    $productsModel->expire = $expiry;
    $productsModel->account = getCurrentShopId();
    $productsModel->save();

    if ($productsModel) {
        // Log product creation
        $create = new logModal();
        $create->title = 'Product Created';
        $create->description = 'Product('. $name01 .') Created successfully By ' . session('username');
        $create->save();

        // Create stock entry if quantity > 0
        if ($quantity > 0) { 
            $uuid_short = 'Stock-' . date('YMd') . '-' . str_pad(
                stock::where('account', getCurrentShopId())
                    ->whereDate('created_at', date('Y-m-d'))
                    ->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            ) . '-' . $name01 . '-' . $name02;

            $Tbp = $quantity * $bPrice;

            $restock = new stock();
            $restock->name = $uuid_short;
            $restock->productId = $uuid;
            $restock->quantity = $quantity;
            $restock->tBprice = $Tbp;
            $restock->bPrice = $bPrice;
            $restock->sPrice = $sPrice;
            $restock->account = getCurrentShopId();
            $restock->save();

            if ($restock) {
                $create = new logModal();
                $create->title = 'Stock Created';
                $create->description = $uuid_short . ' Created successfully By ' . session('username');
                $create->save();
            } else {
                $create = new logModal();
                $create->title = 'Stock Creation Failed';
                $create->description = $uuid_short . ' Creation Failed By ' . session('username');
                $create->save();
            }
        }

        return redirect()->back()->with('success', 'Product saved successfully!');
    } else {
        $create = new logModal();
        $create->title = 'Product Creation Failed';
        $create->description = 'Product Creation Failed By ' . session('username');
        $create->save();
        return redirect()->back()->with('error', 'Product Failed to save');
    }
}

/**
 * Handle Excel/CSV bulk upload with FULL auto-fill
 */
/**
 * Handle CSV import with STORE format (Id, Item Name, Category, Cost Price, Selling Price, Quantity)
 */
private function handleStoreFormatCsv($file)
{
    $successCount = 0;
    $failedCount = 0;
    $errorMessages = [];
    
    $handle = fopen($file->getPathname(), 'r');
    if (!$handle) {
        throw new \Exception('Could not open CSV file');
    }
    
    // Read header row
    $headers = fgetcsv($handle);
    if (!$headers) {
        fclose($handle);
        throw new \Exception('Empty CSV file');
    }
    
    // Clean up headers
    $headers = array_map(function($h) {
        return strtolower(trim($h, "\"\r\n "));
    }, $headers);
    
    \Log::info("Detected headers: " . json_encode($headers));
    
    // Map expected columns
    $columnMap = [
        'id' => array_search('id', $headers),
        'item_name' => array_search('item name', $headers),
        'category' => array_search('category', $headers),
        'cost_price' => array_search('cost price', $headers),
        'selling_price' => array_search('selling price', $headers),
        'quantity' => array_search('quantity', $headers),
    ];
    
    // Validate required columns
    if ($columnMap['item_name'] === false) {
        fclose($handle);
        throw new \Exception('CSV must have "Item Name" column');
    }
    
    $rowNumber = 1;
    $chunkData = [];
    
    while (($row = fgetcsv($handle)) !== false) {
        $rowNumber++;
        
        // Skip empty rows
        if (empty(array_filter($row))) {
            continue;
        }
        
        // Extract data using column mapping
        $itemName = trim($row[$columnMap['item_name']] ?? '');
        $itemName = trim($itemName, '"');
        
        if (empty($itemName)) {
            $failedCount++;
            $errorMessages[] = "Row {$rowNumber}: Missing item name";
            continue;
        }
        
        // Parse quantity - remove quotes and commas
        $quantityRaw = $row[$columnMap['quantity']] ?? '0';
        $quantityRaw = trim($quantityRaw, '"');
        $quantity = (float) str_replace(',', '', $quantityRaw);
        
        // Parse cost price - remove "Tshs " prefix, quotes, and commas
        $costPriceRaw = $row[$columnMap['cost_price']] ?? '0';
        $costPriceRaw = trim($costPriceRaw, '"');
        $costPriceRaw = preg_replace('/^Tshs\s*/i', '', $costPriceRaw);
        $costPrice = (float) str_replace(',', '', $costPriceRaw);
        
        // Parse selling price
        $sellingPriceRaw = $row[$columnMap['selling_price']] ?? '0';
        $sellingPriceRaw = trim($sellingPriceRaw, '"');
        $sellingPriceRaw = preg_replace('/^Tshs\s*/i', '', $sellingPriceRaw);
        $sellingPrice = (float) str_replace(',', '', $sellingPriceRaw);
        
        // Get category
        $category = '';
        if ($columnMap['category'] !== false) {
            $category = trim($row[$columnMap['category']] ?? '');
            $category = trim($category, '"');
        }
        
        // Auto-detect category if empty
        if (empty($category)) {
            $category = $this->detectCategory($itemName);
        }
        
        // Extract brand from Item Name (first word if all caps)
        $words = explode(' ', $itemName);
        $brand = 'Bulk Import';
        if (count($words) > 0 && strtoupper($words[0]) === $words[0]) {
            $brand = $words[0];
        }
        
        // Try to find existing product by name
        $existingProduct = productsModel::where('name01', $itemName)
            ->where('account', getCurrentShopId() ?? getCurrentShopId())
            ->first();

        $rowResult = 'updated'; // default: assume update unless we create new

        if ($existingProduct) {
            // Update existing product - add to quantity
            $existingProduct->quantity += $quantity;
            $existingProduct->save();
            \Log::info("UPDATED product: {$itemName} | Added {$quantity} | New qty: {$existingProduct->quantity}");
            $rowResult = 'updated';
        } else {
            // Create new product
            $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();
            
            $product = new productsModel();
            $product->product_id = $uuid;
            $product->name01 = substr($itemName, 0, 255);
            $product->name02 = substr($brand, 0, 255);
            $product->category = $category;
            $product->unit = $this->detectUnit($itemName);
            $product->description = 'Imported from CSV';
            $product->img = 'default.png';
            $product->quantity = $quantity;
            $product->code = $this->generateUniqueCode();
            $product->bPrice = $costPrice;
            $product->sPrice = $sellingPrice;
            $product->wholesale = $sellingPrice;
            $product->discount = 0;
            $product->location = '';
            $product->supplier = 'Bulk Import';
            $product->expire = date('Y-m', strtotime('+1 year'));
            $product->account = getCurrentShopId() ?? getCurrentShopId();
            $product->save();

            \Log::info("CREATED product: {$itemName} | Qty: {$quantity}");
            $rowResult = 'created';
        }

        // Create stock entry if quantity > 0
        if ($quantity > 0) {
            $this->createStockEntryFast($uuid ?? $existingProduct->product_id, $itemName, $brand, $quantity, $costPrice, $sellingPrice);
        }

        if ($rowResult === 'created') {
            $successCount++;
        }
    }
    
    fclose($handle);
    
    return [
        'success' => $successCount,
        'failed' => $failedCount,
        'errors' => $errorMessages
    ];
}

/**
 * Detect category from product name
 */
private function detectCategory($productName)
{
    $nameUpper = strtoupper($productName);
    
    $categories = [
        'Biscuits' => ['BISCUT', 'BISKUT', 'CREAM RICH', 'ALKAMAL'],
        'Beverages' => ['BERRY', 'JUICE', 'MAJI', 'WATER', 'SODA', 'TIKITI'],
        'Soaps' => ['SOAP', 'SABUNI', 'WHITEWASH', 'NEET', 'NICE ONE'],
        'Cooking Oils' => ['OIL', 'COOKING'],
        'Foods' => ['SUGAR', 'SUKARI', 'FLOUR', 'NGANO', 'RICE', 'MCHELE'],
        'Household' => ['WIPES', 'PAD', 'BOOM', 'SPRAY', 'COIL'],
        'Personal Care' => ['TOOTHPASTE', 'MSWAKI', 'COLGET', 'BODYSPREY'],
    ];
    
    foreach ($categories as $category => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($nameUpper, $keyword) !== false) {
                return $category;
            }
        }
    }
    
    return 'Others';
}

/**
 * Detect unit from product name
 */
private function detectUnit($productName)
{
    $nameLower = strtolower($productName);
    
    if (strpos($nameLower, 'ml') !== false || strpos($nameLower, 'mls') !== false) {
        return 'ml';
    }
    if (strpos($nameLower, 'kg') !== false || strpos($nameLower, 'g') !== false) {
        return 'kg';
    }
    if (strpos($nameLower, 'lt') !== false || strpos($nameLower, 'liter') !== false) {
        return 'liter';
    }
    if (strpos($nameLower, 'carton') !== false || strpos($nameLower, 'ctn') !== false) {
        return 'carton';
    }
    
    return 'pieces';
}
/**
 * Handle Excel/CSV bulk upload with optimized chunked processing
 */
private function handleExcelUpload(Request $req) {
    // Log attempt BEFORE validation to track entry
    $create = new logModal();
    $create->title = 'Products Report';
    $create->description = 'import attempt - validation starting';
    $create->save();
    
    // Custom validation to handle CSV MIME type issues
    if (!$req->hasFile('excel_file')) {
        $error = 'No file uploaded';
        \Log::error('Excel import validation failed: ' . $error);
        $create = new logModal();
        $create->title = 'Products Report';
        $create->description = 'VALIDATION FAILED: ' . $error;
        $create->save();
        return redirect()->back()->with('error', 'Please select a file to upload.');
    }
    
    $file = $req->file('excel_file');
    $extension = strtolower($file->getClientOriginalExtension());
    $mime = strtolower($file->getMimeType());
    $size = $file->getSize();
    
    \Log::info("File upload details - Extension: {$extension}, MIME: {$mime}, Size: " . ($size / 1024) . " KB");
    
    // Check file extension (more reliable than MIME for CSV)
    $allowedExtensions = ['xlsx', 'xls', 'csv'];
    $allowedMimes = [
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        'xls' => ['application/vnd.ms-excel', 'application/xls', 'application/x-msexcel'],
        'csv' => ['text/csv', 'application/csv', 'text/plain', 'application/vnd.ms-excel']
    ];
    
    if (!in_array($extension, $allowedExtensions)) {
        $error = "File extension '{$extension}' is not allowed. Allowed: " . implode(', ', $allowedExtensions);
        \Log::error('Excel import validation failed: ' . $error);
        $create = new logModal();
        $create->title = 'Products Report';
        $create->description = 'VALIDATION FAILED: ' . $error;
        $create->save();
        return redirect()->back()->with('error', $error);
    }
    
    // Check MIME type (but be lenient for CSV)
    $mimeOk = false;
    if (isset($allowedMimes[$extension])) {
        // For CSV, accept any MIME that contains 'csv' or 'text/plain'
        if ($extension === 'csv') {
            if (str_contains($mime, 'csv') || $mime === 'text/plain' || str_contains($mime, 'excel')) {
                $mimeOk = true;
            }
        } else {
            $mimeOk = in_array($mime, $allowedMimes[$extension]);
        }
    }
    
    if (!$mimeOk) {
        \Log::warning("MIME type mismatch for {$extension} file: expected one of " . json_encode($allowedMimes[$extension]) . " but got '{$mime}'. Proceeding anyway (lenient check).");
        // Don't fail - just log warning, as MIME detection can be unreliable
    }
    
    // Check file size (20MB max)
    $maxSize = 20480 * 1024; // 20MB in bytes
    if ($size > $maxSize) {
        $error = 'File size exceeds 20MB limit';
        \Log::error('Excel import validation failed: ' . $error . " (actual: " . ($size / 1024 / 1024) . " MB)");
        $create = new logModal();
        $create->title = 'Products Report';
        $create->description = 'VALIDATION FAILED: ' . $error;
        $create->save();
        return redirect()->back()->with('error', $error);
    }
    
    $create = new logModal();
    $create->title = 'Products Report';
    $create->description = 'importing room reached - validation passed (ext: ' . $extension . ', mime: ' . $mime . ')';
    $create->save();
    // Increase execution time for large files
    set_time_limit(300); // 5 minutes
    ini_set('memory_limit', '512M');
    
    try {
        $file = $req->file('excel_file');
        
        // Start timing
        $startTime = microtime(true);
        
        // Get file info
        $fileSize = $file->getSize();
        $fileExt = $file->getClientOriginalExtension();
        $sessionAccount = getCurrentShopId() ?? getCurrentShopId();
        $sessionUsername = session('username');
        
        $create = new logModal();
            $create->title = 'Products Report';
            $create->description = '=== STARTING BULK IMPORT === '.round($fileSize / 1024, 2) . " KB";
            $create->save();
        \Log::info("=== STARTING BULK IMPORT ===");
        \Log::info("File extension: {$fileExt}");
        \Log::info("File size: " . round($fileSize / 1024, 2) . " KB");
        \Log::info("File path: " . $file->getPathname());
        \Log::info("Session account ID: " . ($sessionAccount ?? 'NULL'));
        \Log::info("Session username: " . ($sessionUsername ?? 'NULL'));
        \Log::info("User ID: " . (Auth::id() ?? 'NULL'));
        
        // Verify session account exists
        if (empty($sessionAccount)) {
            
            \Log::error("No session account found - import cannot proceed");
            return redirect()->back()->with('error', 'No shop/account selected. Please select a shop first.');
        }
        
        // Verify user is authenticated
        if (!Auth::check()) {
            $create = new logModal();
            $create->title = 'Products Report';
            $create->description = 'User not authenticated - import blocked';
            $create->save();
            \Log::error("User not authenticated - import blocked");
            return redirect()->back()->with('error', 'You must be logged in to import products.');
        }
        
        // Process in chunks based on file type
        if ($fileExt === 'csv') {
            \Log::info("Using CSV processor");
            $result = $this->processCsvInChunks($file);
        } else {
            \Log::info("Using Excel processor");
            $result = $this->processExcelInChunks($file);
        }
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        $create = new logModal();
            $create->title = 'Products Report';
            $create->description = '=== IMPORT COMPLETE ===';
            $create->save();
        \Log::info("=== IMPORT COMPLETE ===");
        \Log::info("Success count: {$result['success']}");
        \Log::info("Failed count: {$result['failed']}");
        \Log::info("Duration: {$duration} seconds");
        
        if (!empty($result['errors'])) {
            \Log::info("Errors encountered: " . implode(' | ', array_slice($result['errors'], 0, 10)));
        }
        
        // Only log to database if there was some activity
        if ($result['success'] > 0 || $result['failed'] > 0) {
            $logEntry = new logModal();
            $logEntry->title = 'Bulk Product Import';
            $logEntry->description = "Imported {$result['success']} products successfully. Failed: {$result['failed']}. Duration: {$duration}s By " . ($sessionUsername ?? 'System');
            $logEntry->save();
        }
        
        $message = "Successfully imported {$result['success']} products";
        if ($result['failed'] > 0) {
            $message .= " ({$result['failed']} failed)";
            if (!empty($result['errors'])) {
                $message .= ". First errors: " . implode(' | ', array_slice($result['errors'], 0, 3));
            }
        }
        
        if ($result['success'] == 0 && $result['failed'] > 0) {
            return redirect()->back()->with('error', $message);
        }
        
        return redirect()->back()->with('success', $message . " (took {$duration} seconds)");
        
    } catch (\Exception $e) {
        \Log::error('Excel import error: ' . $e->getMessage());
        \Log::error('Import exception trace: ' . $e->getTraceAsString());
        return redirect()->back()->with('error', 'Error processing file: ' . $e->getMessage());
    }
}

/**
 * Process CSV file in chunks to avoid memory issues
 */
private function processCsvInChunks($file, $chunkSize = 100) {
    $successCount = 0;
    $failedCount = 0;
    $errorMessages = [];
    $rowNumber = 0;
    
    \Log::info("=== Starting CSV processing ===");
    
    $handle = fopen($file->getPathname(), 'r');
    if (!$handle) {
        \Log::error("Could not open CSV file: " . $file->getPathname());
        throw new \Exception('Could not open file');
    }
    
    // Read first 500 chars to see raw file content and detect delimiter
    $firstChars = fread($handle, 500);
    fseek($handle, 0); // Reset to beginning
    \Log::info("CSV file first 500 chars: " . json_encode($firstChars));
    
    // Detect delimiter: count commas vs tabs in first line
    $firstLine = strtok($firstChars, "\r\n");
    $commaCount = substr_count($firstLine, ',');
    $tabCount = substr_count($firstLine, "\t");
    $delimiter = $tabCount > $commaCount ? "\t" : ",";
    \Log::info("Delimiter detection: commas={$commaCount}, tabs={$tabCount}, using delimiter: " . json_encode($delimiter));
    
    // Find header row
    $headers = null;
    $headerRowNumber = 0;
    
    // Known header names
    $knownHeaders = [
        'product code', 'code', 'id',
        'name', 'item name', '# item name', 'product name',
        'quantity', 'qty',
        'price', 'cost price', 'selling price',
        'stock',
        'expire', 'expiry',
    ];
    
    \Log::info("Searching for CSV header row in first 20 rows...");
    while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
        $rowNumber++;
        $rowLower = array_map('strtolower', array_map('trim', $row));
        
        // Log ALL rows for debugging (first 30 rows)
        if ($rowNumber <= 30) {
            \Log::info("CSV raw row {$rowNumber}: " . json_encode($row));
            \Log::info("CSV row {$rowNumber} lowercased: " . json_encode($rowLower));
        }
        
        // Check if this is header row
        $hasKnownHeader = false;
        $matchedHeaders = [];
        foreach ($knownHeaders as $kh) {
            if (in_array($kh, $rowLower)) {
                $hasKnownHeader = true;
                $matchedHeaders[] = $kh;
            }
        }
        
        if ($hasKnownHeader) {
            $headers = array_map('strtolower', array_map('trim', $row));
            $headerRowNumber = $rowNumber;
            \Log::info("Found CSV header row at line {$headerRowNumber}: " . json_encode($headers));
            \Log::info("Matched header keywords: " . json_encode($matchedHeaders));
            break;
        }
        
        // After 30 rows without finding headers, use current row as headers
        if ($rowNumber >= 30 && $headers === null) {
            $headers = array_map('strtolower', array_map('trim', $row));
            $headerRowNumber = $rowNumber;
            \Log::info("Using fallback CSV header row at line {$headerRowNumber}: " . json_encode($headers));
            break;
        }
    }
    
    if ($headers === null) {
        \Log::error("Could not find header row in CSV file after {$rowNumber} rows");
        fclose($handle);
        throw new \Exception('Could not find header row in CSV file');
    }
    
    // Process data in chunks
    $chunkData = [];
    $chunkCounter = 0;
    $totalRowsProcessed = 0;
    $skippedEmpty = 0;
    $skippedNoName = 0;
    
    \Log::info("Starting to process CSV data rows after header row {$headerRowNumber}");
    
    while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
        // Log raw row for first 20 data rows
        if ($totalRowsProcessed < 20) {
            \Log::info("CSV raw data row " . ($totalRowsProcessed + 1) . ": " . json_encode($row));
        }
        
        // Skip empty rows
        if (empty(array_filter($row))) {
            $skippedEmpty++;
            if ($skippedEmpty <= 5) {
                \Log::info("CSV row SKIPPED: empty row");
            }
            continue;
        }
        
        // Map row to associative array
        $item = [];
        foreach ($headers as $index => $header) {
            $item[$header] = isset($row[$index]) ? trim($row[$index]) : '';
        }
        
        // Log mapped item for first 20 rows
        if ($totalRowsProcessed < 20) {
            \Log::info("CSV mapped row " . ($totalRowsProcessed + 1) . ": " . json_encode($item));
        }
        
        // Skip if no name
        $hasName = !empty($item['name']) || !empty($item['item name']) || !empty($item['product name']);
        if (!$hasName) {
            $skippedNoName++;
            if ($skippedNoName <= 5) {
                \Log::info("CSV row SKIPPED: no name found. Available keys: " . json_encode(array_keys($item)) . " | name=" . ($item['name'] ?? '') . ", item name=" . ($item['item name'] ?? '') . ", product name=" . ($item['product name'] ?? ''));
            }
            continue;
        }
        
        $chunkData[] = $item;
        $chunkCounter++;
        $totalRowsProcessed++;
        
        // Log first few rows
        if ($totalRowsProcessed <= 5) {
            \Log::info("CSV data row {$totalRowsProcessed} ADDED to chunk: " . json_encode($item));
        }
        
        // Process chunk when it reaches chunk size
        if ($chunkCounter >= 100) {
            \Log::info("Processing CSV chunk of " . count($chunkData) . " rows. Total processed so far: {$totalRowsProcessed}");
            $result = $this->processDataChunk($chunkData);
            $successCount += $result['success'];
            $failedCount += $result['failed'];
            $errorMessages = array_merge($errorMessages, $result['errors']);
            
            // Clear chunk to free memory
            $chunkData = [];
            $chunkCounter = 0;
            
            // Allow garbage collection
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
            
            \Log::info("CSV chunk processed. Totals so far: {$successCount} successful, {$failedCount} failed");
        }
    }
    
    \Log::info("Total CSV rows processed: {$totalRowsProcessed}");
    \Log::info("Skipped empty rows: {$skippedEmpty}");
    \Log::info("Skipped no-name rows: {$skippedNoName}");
    \Log::info("Final chunk size: " . count($chunkData));
    
    // Process remaining data
    if (!empty($chunkData)) {
        \Log::info("Processing final CSV chunk of " . count($chunkData) . " rows");
        $result = $this->processDataChunk($chunkData);
        $successCount += $result['success'];
        $failedCount += $result['failed'];
        $errorMessages = array_merge($errorMessages, $result['errors']);
    } else {
        \Log::info("No final chunk to process (chunkData is empty)");
    }
    
    fclose($handle);
    
    \Log::info("=== CSV PROCESSING COMPLETE ===");
    \Log::info("Total rows read: {$totalRowsProcessed}");
    \Log::info("Total products created/updated: {$successCount}");
    \Log::info("Total failed: {$failedCount}");
    
    return [
        'success' => $successCount,
        'failed' => $failedCount,
        'errors' => $errorMessages
    ];
}

/**
 * Process Excel file in chunks using spreadsheet reader
 */
private function processExcelInChunks($file) {
    $successCount = 0;
    $failedCount = 0;
    $errorMessages = [];
    
    try {
        \Log::info("=== Starting Excel processing ===");
        \Log::info("File path: " . $file->getPathname());
        
        // Use spreadsheet reader with memory optimization
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly(null);
        
        // Load only the active sheet
        $spreadsheet = $reader->load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        
        // Get highest row and column
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        
        \Log::info("Excel file has {$highestRow} rows and {$highestColumnIndex} columns");
        \Log::info("First 5 rows raw data for debugging:");
        for ($row = 1; $row <= min(5, $highestRow); $row++) {
            $rowData = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $rowData[] = $cellValue !== null ? (string)$cellValue : '';
            }
            \Log::info("Excel raw row {$row}: " . json_encode($rowData));
        }
        
        // Find header row
        $headerRowIndex = null;
        $headers = null;
        
        $knownHeaders = [
            'product code', 'code', 'id',
            'name', 'item name', '# item name', 'product name',
            'quantity', 'qty',
            'price', 'cost price', 'selling price',
            'stock',
            'expire', 'expiry',
        ];
        
        for ($row = 1; $row <= min(20, $highestRow); $row++) {
            $rowData = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $rowData[] = $cellValue !== null ? trim((string)$cellValue) : '';
            }
            
            $rowLower = array_map('strtolower', $rowData);
            
            $hasKnownHeader = false;
            foreach ($knownHeaders as $kh) {
                if (in_array($kh, $rowLower)) {
                    $hasKnownHeader = true;
                    break;
                }
            }
            
            if ($hasKnownHeader) {
                $headerRowIndex = $row;
                $headers = $rowData;
                \Log::info("Found Excel header row at {$headerRowIndex}");
                break;
            }
        }
        
        if ($headers === null && $highestRow > 0) {
            // Use first row as headers
            $headerRowIndex = 1;
            $headers = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                $headers[] = $cellValue !== null ? strtolower(trim((string)$cellValue)) : '';
            }
            \Log::info("Using first row as headers");
        }
        
        if ($headers === null) {
            throw new \Exception('Could not find header row in Excel file');
        }
        
        // Normalize headers
        $headers = array_map(function($h) {
            return strtolower(trim((string)$h));
        }, $headers);
        
        \Log::info("Normalized headers: " . json_encode($headers));
        \Log::info("Starting to process data rows from row " . ($headerRowIndex + 1) . " to row {$highestRow}");
        
        // Process data in chunks using iterator
        $chunkData = [];
        $chunkSize = 50; // Smaller chunk size for Excel
        $processedRows = 0;
        $totalEligibleRows = 0;
        
        for ($row = $headerRowIndex + 1; $row <= $highestRow; $row++) {
            $rowData = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $rowData[$headers[$col - 1]] = $cellValue !== null ? trim((string)$cellValue) : '';
            }
            
            // Log raw row data for first few rows to debug
            if ($processedRows < 5) {
                \Log::info("Excel row " . ($row + 1) . " raw data: " . json_encode($rowData));
            }
            
            // Skip empty rows
            if (empty(array_filter($rowData))) {
                if ($processedRows < 5) {
                    \Log::info("Excel row " . ($row + 1) . " SKIPPED: empty row");
                }
                continue;
            }
            
            // Skip if no name
            $hasName = !empty($rowData['name']) || !empty($rowData['item name']) || !empty($rowData['product name']);
            if (!$hasName) {
                if ($processedRows < 5) {
                    \Log::info("Excel row " . ($row + 1) . " SKIPPED: no name. Keys: " . json_encode(array_keys($rowData)) . " | Values sample: name=" . ($rowData['name'] ?? '') . ", item name=" . ($rowData['item name'] ?? '') . ", product name=" . ($rowData['product name'] ?? ''));
                }
                continue;
            }
            
            $totalEligibleRows++;
            $chunkData[] = $rowData;
            $processedRows++;
            
            if ($processedRows <= 5) {
                \Log::info("Excel row " . ($row + 1) . " ADDED to chunk. Item name: " . ($rowData['name'] ?? $rowData['item name'] ?? $rowData['product name'] ?? 'UNKNOWN'));
            }
            
            // Process chunk
            if (count($chunkData) >= $chunkSize) {
                \Log::info("Processing chunk of " . count($chunkData) . " rows. Total processed so far: {$processedRows}");
                $result = $this->processDataChunk($chunkData);
                $successCount += $result['success'];
                $failedCount += $result['failed'];
                $errorMessages = array_merge($errorMessages, $result['errors']);
                
                $chunkData = [];
                
                // Allow garbage collection
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
                
                \Log::info("Chunk processed. Totals so far: {$successCount} successful, {$failedCount} failed");
            }
        }
        
        // Process remaining data
        if (!empty($chunkData)) {
            $result = $this->processDataChunk($chunkData);
            $successCount += $result['success'];
            $failedCount += $result['failed'];
            $errorMessages = array_merge($errorMessages, $result['errors']);
        }
        
        // Clear spreadsheet from memory
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        
    } catch (\Exception $e) {
        \Log::error('Error reading Excel file: ' . $e->getMessage());
        throw new \Exception('Could not read Excel file: ' . $e->getMessage());
    }
    
    return [
        'success' => $successCount,
        'failed' => $failedCount,
        'errors' => $errorMessages
    ];
}

/**
 * Process a chunk of data rows
 */
private function processDataChunk($chunkData) {
    $successCount = 0;
    $failedCount = 0;
    $errorMessages = [];
    
    \Log::info("processDataChunk called with " . count($chunkData) . " rows");
    if (count($chunkData) > 0) {
        \Log::info("First row in chunk: " . json_encode($chunkData[0]));
    }
    
    DB::beginTransaction();
    
    try {
        foreach ($chunkData as $index => $row) {
            try {
                \Log::info("Processing row " . ($index + 1) . " in chunk");
                if ($index < 3) {
                    \Log::info("Row " . ($index + 1) . " full data: " . json_encode($row));
                }
                $result = $this->createProductFromExcelRow($row);
                if ($result === 'created') {
                    $successCount++;
                    \Log::info("Row " . ($index + 1) . " succeeded (new product created)");
                } elseif ($result === 'updated') {
                    // Update is not a new import — don't inflate successCount
                    \Log::info("Row " . ($index + 1) . " succeeded (existing product updated)");
                } else {
                    $failedCount++;
                    $errorMessages[] = "Row " . ($index + 1) . ": Failed to create product (null return)";
                    \Log::warning("Row " . ($index + 1) . " returned false from createProductFromExcelRow");
                }
            } catch (\Exception $e) {
                $failedCount++;
                $errorMsg = "Row " . ($index + 1) . ": " . $e->getMessage();
                $errorMessages[] = $errorMsg;
                \Log::error('Row processing failed: ' . $e->getMessage());
                \Log::error('Row data: ' . json_encode($row));
            }
        }
        
        DB::commit();
        \Log::info("Chunk committed. Success: {$successCount}, Failed: {$failedCount}");
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Chunk processing failed: ' . $e->getMessage());
        \Log::error('Chunk exception trace: ' . $e->getTraceAsString());
        $failedCount += count($chunkData);
        $errorMessages[] = "Chunk processing failed: " . $e->getMessage();
    }
    
    return [
        'success' => $successCount,
        'failed' => $failedCount,
        'errors' => $errorMessages
    ];
}

/**
 * Create a product from one Excel/CSV row - Optimized for large imports
 */
private function createProductFromExcelRow($rowData) {
    try {
        \Log::info("createProductFromExcelRow: starting. Row keys: " . json_encode(array_keys($rowData)));
        
        // Normalise all keys to lowercase
        $row = array_change_key_case($rowData);
        
        // ── Item Name ──────────────────────────────────────────
        $itemName = trim(
            $row['name'] ??
            $row['item name'] ??
            $row['# item name'] ??
            $row['product name'] ??
            ''
        );
        $itemName = trim(preg_replace('/^\s*#\s*/', '', $itemName));
        
        \Log::info("createProductFromExcelRow: itemName=" . json_encode($itemName));
        
        if (empty($itemName)) {
            throw new \Exception('Missing item name');
        }
        
        // Limit name length to avoid database issues
        $itemName = substr($itemName, 0, 255);
        
        // ── Product Code ───────────────────────────────────────
        $productCode = trim($row['product code'] ?? $row['code'] ?? $row['id'] ?? '');
        \Log::info("createProductFromExcelRow: productCode=" . json_encode($productCode));
        
        // ── Quantity ───────────────────────────────────────────
        $quantity = (int) $this->parseNumber(
            $row['quantity'] ??
            $row['qty'] ??
            0
        );
        \Log::info("createProductFromExcelRow: quantity={$quantity}");
        
        // ── Price (Selling Price) ─────────────────────────────
        // Handle split price columns (e.g., ""Tshs 40"  "000.00"")
        \Log::info("createProductFromExcelRow: extracting selling price");
        $sPriceRaw = $this->extractPriceFromRow($row, 'selling price', 'sprice', 'price');
        $sPrice = $this->parsePrice($sPriceRaw);
        \Log::info("createProductFromExcelRow: sPriceRaw=" . json_encode($sPriceRaw) . " | sPrice={$sPrice}");
        
        // Cost price (same handling)
        \Log::info("createProductFromExcelRow: extracting cost price");
        $bPriceRaw = $this->extractPriceFromRow($row, 'cost price', 'buying price', 'bprice');
        \Log::info("createProductFromExcelRow: bPriceRaw=" . json_encode($bPriceRaw));
        $bPrice = $this->parsePrice($bPriceRaw ?: $sPriceRaw ?: $sPrice);
        \Log::info("createProductFromExcelRow: bPrice={$bPrice}");
        
        // ── Expiry ────────────────────────────────────────────
        $expiry = trim($row['expiry'] ?? $row['expire'] ?? now()->format('Y-m'));
        
        // ── Brand (from Name column if it contains brand info) ──
        $name02 = trim($row['brand'] ?? $row['manufacturer'] ?? '');
        if (empty($name02)) {
            // Try to extract brand from name (first word if all caps)
            $words = explode(' ', $itemName);
            if (count($words) > 0 && strtoupper($words[0]) === $words[0]) {
                $name02 = $words[0];
            } else {
                $name02 = 'Bulk Import';
            }
        }
        $name02 = substr($name02, 0, 255);
        
        // ── Category ───────────────────────────────────────────
        $category = trim($row['category'] ?? '');
        if (empty($category)) {
            $itemNameUpper = strtoupper($itemName);
            if (strpos($itemNameUpper, 'BERRY') !== false || strpos($itemNameUpper, 'JUICE') !== false) {
                $category = 'Beverages';
            } elseif (strpos($itemNameUpper, 'BISCUT') !== false || strpos($itemNameUpper, 'BISKUT') !== false) {
                $category = 'Biscuits';
            } elseif (strpos($itemNameUpper, 'SOAP') !== false) {
                $category = 'Soaps';
            } elseif (strpos($itemNameUpper, 'OIL') !== false) {
                $category = 'Cooking Oils';
            } elseif (strpos($itemNameUpper, 'MAJI') !== false || strpos($itemNameUpper, 'WATER') !== false) {
                $category = 'Beverages';
            } elseif (strpos($itemNameUpper, 'SUGAR') !== false || strpos($itemNameUpper, 'SUKARI') !== false) {
                $category = 'Foods';
            } elseif (strpos($itemNameUpper, 'FLOUR') !== false || strpos($itemNameUpper, 'NGANO') !== false) {
                $category = 'Foods';
            } else {
                $category = 'Others';
            }
        }
        
        // ── Unit ───────────────────────────────────────────────
        $unit = trim($row['unit'] ?? 'pieces');
        $itemNameLower = strtolower($itemName);
        if (strpos($itemNameLower, 'mls') !== false || strpos($itemNameLower, 'ml') !== false) {
            $unit = 'ml';
        } elseif (strpos($itemNameLower, 'kg') !== false || strpos($itemNameLower, 'g') !== false) {
            $unit = 'kg';
        } elseif (strpos($itemNameLower, 'lt') !== false || strpos($itemNameLower, 'liter') !== false) {
            $unit = 'liter';
        } elseif (strpos($itemNameLower, 'carton') !== false || strpos($itemNameLower, 'ctn') !== false) {
            $unit = 'carton';
        }
        
        // ── Supplier ───────────────────────────────────────────
        $firstVendor = vendorModal::where('account', getCurrentShopId() ?? getCurrentShopId())->first();
        $supplier = trim($row['supplier'] ?? $row['vendor'] ?? '');
        if (empty($supplier) && $firstVendor) {
            $supplier = $firstVendor->name;
        } elseif (empty($supplier)) {
            $supplier = 'Bulk Import';
        }
        
        // ── Generate UUID for product ID ──────────────────────
        // If productCode is numeric (database ID), we still need to generate a UUID for product_id
        // The productCode (numeric ID) is only used for lookup, not for storing
        if (!empty($productCode) && is_numeric($productCode)) {
            // For existing product lookup, we'll use the numeric ID to find it
            // But we still need product_id for the database field
            $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();
            \Log::info("Numeric ID provided for lookup: {$productCode}, will generate new UUID for product_id if creating new");
        } else if (!empty($productCode)) {
            // Use provided productCode as product_id (could be custom code or UUID)
            $uuid = $productCode;
        } else {
            // Generate new UUID
            $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();
        }
        
        \Log::info("createProductFromExcelRow: productCode from Excel: " . var_export($productCode, true) . " | UUID to use: {$uuid}");
        \Log::info("createProductFromExcelRow: prices - bPrice={$bPrice}, sPrice={$sPrice}, quantity={$quantity}");
        
        // Check if product already exists - check BOTH numeric id and product_id
        $existingProduct = null;
        $lookupMethod = 'none';
        
        if (!empty($productCode) && is_numeric($productCode)) {
            // If productCode is numeric, try to find by numeric id first
            \Log::info("createProductFromExcelRow: attempting numeric ID lookup: id=" . (int)$productCode . " in account=" . (getCurrentShopId() ?? getCurrentShopId()));
            $existingProduct = productsModel::where('id', (int)$productCode)
                ->where('account', getCurrentShopId() ?? getCurrentShopId())
                ->first();
            
            if ($existingProduct) {
                $lookupMethod = 'numeric_id';
                \Log::info("createProductFromExcelRow: FOUND existing product by numeric ID (id={$existingProduct->id}): {$existingProduct->name01}");
            } else {
                \Log::info("createProductFromExcelRow: NO product found with numeric ID " . (int)$productCode);
            }
        }
        
        // If not found by numeric id, try by product_id (UUID or custom code)
        if (!$existingProduct) {
            \Log::info("createProductFromExcelRow: attempting product_id lookup: product_id={$uuid} in account=" . (getCurrentShopId() ?? getCurrentShopId()));
            $existingProduct = productsModel::where('product_id', $uuid)
                ->where('account', getCurrentShopId() ?? getCurrentShopId())
                ->first();
            
            if ($existingProduct) {
                $lookupMethod = 'product_id';
                \Log::info("createProductFromExcelRow: FOUND existing product by product_id: {$existingProduct->name01}");
            } else {
                \Log::info("createProductFromExcelRow: NO existing product found with product_id {$uuid}");
            }
        }
        
        if ($existingProduct) {
            // Update existing product - only update quantity
            $oldQty = $existingProduct->quantity;
            $existingProduct->quantity += $quantity;
            $existingProduct->save();

            // Ensure $uuid points to the existing product's product_id for stock entry
            $uuid = $existingProduct->product_id;

            \Log::info("createProductFromExcelRow: UPDATED product via {$lookupMethod}: {$itemName} | Old qty: {$oldQty} | Added: {$quantity} | New qty: {$existingProduct->quantity}");
        } else {
            // Create new product
            \Log::info("createProductFromExcelRow: CREATING new product");
            $product = new productsModel();
            $product->product_id = $uuid;
            $product->name01 = $itemName;
            $product->name02 = $name02;
            $product->category = $category;
            $product->unit = $unit;
            $product->description = 'Imported via bulk upload';
            $product->img = 'default.png';
            $product->quantity = $quantity;
            $product->code = $this->generateUniqueCode();
            $product->bPrice = $bPrice;
            $product->sPrice = $sPrice;
            $product->wholesale = $sPrice;
            $product->discount = 0;
            $product->location = '';
            $product->supplier = $supplier;
            $product->expire = $this->formatExpiryDate($expiry);
            $product->account = getCurrentShopId() ?? getCurrentShopId();
            $product->save();
            
            \Log::info("createProductFromExcelRow: CREATED new product: {$itemName} | ID: {$product->id} | product_id: {$uuid} | qty: {$quantity}");
        }
        
        // Create stock entry for new stock
        if ($quantity > 0) {
            \Log::info("createProductFromExcelRow: creating stock entry for qty={$quantity}, product_id=" . ($uuid ?? 'NULL'));
            $this->createStockEntryFast($uuid, $itemName, $name02, $quantity, $bPrice, $sPrice);
        } else {
            \Log::info("createProductFromExcelRow: skipping stock entry (quantity=0)");
        }

        return $existingProduct ? 'updated' : 'created';
        
    } catch (\Exception $e) {
        \Log::error('Product creation failed: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Fast stock entry creation - optimized for bulk imports
 */
private function createStockEntryFast($productId, $itemName, $brand, $quantity, $bPrice, $sPrice) {
    try {
        $seq = stock::where('account', getCurrentShopId() ?? getCurrentShopId())
                    ->whereDate('created_at', date('Y-m-d'))
                    ->count() + 1;
        
        $uuid_short = 'Stock-' . date('Ymd') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT)
                    . '-' . substr($itemName, 0, 30) . '-' . substr($brand, 0, 20);
        
        $restock = new stock();
        $restock->name = $uuid_short;
        $restock->productId = $productId;
        $restock->quantity = $quantity;
        $restock->tBprice = $quantity * $bPrice;
        $restock->bPrice = $bPrice;
        $restock->sPrice = $sPrice;
        $restock->account = getCurrentShopId() ?? getCurrentShopId();
        $restock->save();
        
    } catch (\Exception $e) {
        \Log::error('Stock entry creation failed: ' . $e->getMessage());
        // Don't throw - stock entry is not critical for import success
    }
}
// ============================================================
 
/**
 * Read CSV file — detects header row by known column names
 */
private function readCsvFile($file) {
    $data    = [];
    $handle  = fopen($file->getPathname(), 'r');
 
    // Known header names (all lowercase for comparison)
    $knownHeaders = [
        'item name', '# item name', 'name', 'product name',
        'product code', 'code', 'id',
        'quantity', 'qty',
        'price', 'cost price', 'selling price',
        'expire', 'expiry',
    ];
 
    if ($handle) {
        $headers  = null;
        $rowCount = 0;
 
        while (($row = fgetcsv($handle)) !== false) {
            $rowCount++;
            $rowLower = array_map('strtolower', array_map('trim', $row));
 
            // Check if this row contains any known header
            $hasKnownHeader = false;
            foreach ($knownHeaders as $kh) {
                if (in_array($kh, $rowLower)) {
                    $hasKnownHeader = true;
                    break;
                }
            }
 
            if ($hasKnownHeader) {
                $headers = array_map(function ($h) {
                    return strtolower(trim($h));
                }, $row);
                break;
            }
 
            // After 10 rows without finding headers, use current row
            if ($rowCount >= 10) {
                $headers = array_map(function ($h) {
                    return strtolower(trim($h));
                }, $row);
                break;
            }
        }
 
        if ($headers === null) {
            fclose($handle);
            return $data;
        }
 
        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }
 
            $item = [];
            foreach ($headers as $index => $header) {
                $item[$header] = $row[$index] ?? '';
            }
 
            if (!empty(array_filter($item))) {
                $data[] = $item;
            }
        }
 
        fclose($handle);
    }
 
    return $data;
}
 
// ============================================================
 
/**
 * Read Excel file — detects header row by known column names
 */
/**
 * Read Excel file — detects header row by known column names
 */
private function readExcelFile($file) {
    $data = [];
    
    // Known header names (all lowercase for comparison)
    $knownHeaders = [
        'product code', 'code', 'id',
        'name', 'item name', '# item name', 'product name',
        'quantity', 'qty',
        'price', 'cost price', 'selling price',
        'stock',
        'expire', 'expiry',
    ];
    
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        if (empty($rows)) {
            return $data;
        }
        
        // Try to find header row by checking for known columns
        $headerRowIndex = null;
        $headers = null;
        
        // Check first 20 rows for header
        for ($i = 0; $i < min(20, count($rows)); $i++) {
            $row = $rows[$i];
            if (empty($row)) {
                continue;
            }
            
            $rowLower = array_map(function($cell) {
                return strtolower(trim((string)$cell));
            }, $row);
            
            // Check if this row contains any known header
            $hasKnownHeader = false;
            foreach ($knownHeaders as $kh) {
                if (in_array($kh, $rowLower)) {
                    $hasKnownHeader = true;
                    break;
                }
            }
            
            if ($hasKnownHeader) {
                $headerRowIndex = $i;
                $headers = array_map(function($h) {
                    return strtolower(trim((string)$h));
                }, $row);
                \Log::info("Found header row at index {$i}: " . json_encode($headers));
                break;
            }
        }
        
        // Fallback: use first non-empty row as headers
        if ($headers === null && !empty($rows)) {
            for ($i = 0; $i < min(10, count($rows)); $i++) {
                if (!empty(array_filter($rows[$i]))) {
                    $headerRowIndex = $i;
                    $headers = array_map(function($h) {
                        return strtolower(trim((string)$h));
                    }, $rows[$i]);
                    \Log::info("Using fallback header row at index {$i}: " . json_encode($headers));
                    break;
                }
            }
        }
        
        if ($headers === null) {
            \Log::error('Could not find header row in Excel file');
            return $data;
        }
        
        // Process data rows
        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Skip empty rows
            if (empty(array_filter(array_map('strval', $row)))) {
                continue;
            }
            
            $item = [];
            foreach ($headers as $j => $header) {
                $item[$header] = isset($row[$j]) ? strval($row[$j]) : '';
            }
            
            // Only add if there's at least a name
            $hasName = !empty($item['name']) || !empty($item['item name']) || !empty($item['product name']);
            if ($hasName && !empty(array_filter($item))) {
                $data[] = $item;
            }
        }
        
        \Log::info("Parsed " . count($data) . " rows from Excel file");
        
    } catch (\Exception $e) {
        \Log::error('Error reading Excel file: ' . $e->getMessage());
        throw new \Exception('Could not read Excel file. Please ensure it is a valid Excel file.');
    }
    
    return $data;
}
// ============================================================
/**
 * Create a product from one Excel/CSV row
 * Updated to handle your specific file format with columns:
 * Product Code, Name, Quantity, Price, Stock, expire
 */

/**
 * Parse price — strips currency symbols, spaces, and thousand-separator commas
 * Handles values like "4,900" or "Tsh 1,200,000"
 */
private function parsePrice($value) {
    \Log::info("parsePrice: input=" . json_encode($value) . " (type: " . gettype($value) . ")");
    
    if ($value === null || $value === '') {
        \Log::info("parsePrice: null or empty, returning 0");
        return 0;
    }
    
    $strVal = strval($value);
    \Log::info("parsePrice: as string=" . json_encode($strVal));
    
    // Remove currency labels and thousand-separator commas
    // Include both 'tshs' and 'tsh' to handle both "Tshs" and "Tsh" prefixes
    $cleaned = str_ireplace(['tshs', 'tsh', 'tzs', '$', '€', '£', ',', ' '], '', $strVal);
    $cleaned = trim($cleaned);
    
    \Log::info("parsePrice: after cleaning=" . json_encode($cleaned) . " | is_numeric=" . (is_numeric($cleaned) ? 'yes' : 'no'));
    
    $result = is_numeric($cleaned) ? (float) $cleaned : 0;
    \Log::info("parsePrice: final result={$result}");
    
    return $result;
}
 
// ============================================================
 
/**
 * Parse a plain number — strips commas used as thousand separators
 * Use this for Quantity to avoid (int)"1,000" = 1
 */
private function parseNumber($value) {
    if ($value === null || $value === '') {
        return 0;
    }
    $value = str_replace([',', ' '], '', strval($value));
    return is_numeric($value) ? (float) $value : 0;
}
// ============================================================
  
/**
 * Extract price from row, handling split columns
 * Some Excel exports split "Tshs 40,000.00" into ""Tshs 40"  "000.00""
 * across two adjacent cells. This method merges them back.
 */
private function extractPriceFromRow($row, $primaryKey, $altKey1 = null, $altKey2 = null) {
    // Try primary key first
    $value = $row[$primaryKey] ?? '';
    
    // If empty, try alternatives
    if (empty(trim($value)) && $altKey1) {
        $value = $row[$altKey1] ?? '';
    }
    if (empty(trim($value)) && $altKey2) {
        $value = $row[$altKey2] ?? '';
    }
    
    $value = trim($value);
    
    \Log::info("extractPriceFromRow: key={$primaryKey}, raw value=" . json_encode($value));
    
    // If value is empty, return it
    if (empty($value)) {
        \Log::info("extractPriceFromRow: value is empty, returning empty");
        return $value;
    }
    
    // Check if this looks like a split price:
    // A split price cell is an INCOMPLETE part of a price that needs merging.
    // Characteristics:
    // 1. Contains letters (currency like "Tshs", "Tsh") - indicates incomplete
    // 2. Has quotes (Excel artifact) - indicates incomplete
    // 3. Is a small integer (< 1000) without decimal point - could be "40" from "40,000"
    //    BUT exclude if it already has a decimal point (like "0.00" is complete)
    
    $hasLetters = preg_match('/[a-zA-Z]/', $value);
    $hasQuotes = str_contains($value, '"') || str_contains($value, "'");
    $hasDecimal = str_contains($value, '.');
    
    // Strip currency and spaces to get pure number
    $stripped = str_replace(['Tshs', 'Tsh', ' ', ','], '', $value);
    $stripped = trim($stripped, '"\'');
    $isNumeric = is_numeric($stripped);
    $isSmallNumeric = $isNumeric && $stripped < 1000 && !$hasDecimal;
    
    $endsWithQuote = str_ends_with($value, '"') || str_ends_with($value, "'");
    
    \Log::info("extractPriceFromRow: hasLetters={$hasLetters}, hasQuotes={$hasQuotes}, hasDecimal={$hasDecimal}, isSmallNumeric={$isSmallNumeric}, endsWithQuote={$endsWithQuote}");
    
    // If any split indicator is present, attempt merge
    if ($hasLetters || $hasQuotes || $isSmallNumeric || $endsWithQuote) {
        \Log::info("extractPriceFromRow: split detected, attempting merge");
        $merged = $this->mergeSplitPrice($row, $primaryKey);
        \Log::info("extractPriceFromRow: merged result=" . json_encode($merged));
        // Only use merged result if it's valid (non-empty and different from original)
        if ($merged !== '' && $merged !== $value) {
            return $merged;
        }
        // If merge didn't produce a valid result, return original
        \Log::info("extractPriceFromRow: merge didn't produce valid result, returning original");
        return $value;
    }
    
    \Log::info("extractPriceFromRow: no split detected, returning original value");
    return $value;
}

/**
 * Merge split price cells from adjacent columns
 */
private function mergeSplitPrice($row, $currentKey) {
    $keys = array_keys($row);
    $currentIndex = array_search($currentKey, $keys);
    
    \Log::info("mergeSplitPrice: currentKey={$currentKey}, currentIndex={$currentIndex}, totalKeys=" . count($keys));
    
    if ($currentIndex === false || $currentIndex >= count($keys) - 1) {
        \Log::info("mergeSplitPrice: no next column available, returning original");
        return $row[$currentKey] ?? '';
    }
    
    // Get current and next cell
    $current = trim($row[$currentKey] ?? '');
    $nextKey = $keys[$currentIndex + 1];
    $next = trim($row[$nextKey] ?? '');
    
    \Log::info("mergeSplitPrice: current=" . json_encode($current) . ", nextKey=" . json_encode($nextKey) . ", next=" . json_encode($next));
    
    // If next cell is empty, nothing to merge
    if (empty($next)) {
        \Log::info("mergeSplitPrice: next cell is empty, returning current");
        return $current;
    }
    
    // Merge: concatenate current and next, then clean up quotes/spaces
    $merged = $current . $next;
    $merged = str_replace(['""', '"', "'"], '', $merged);
    $merged = trim($merged);
    
    \Log::info("mergeSplitPrice: merged string=" . json_encode($merged));
    
    // Validate the merged result is a plausible price:
    // After removing currency text, should be a valid number
    $numericPart = str_ireplace(['tshs', 'tsh', 'tzs', '$', '€', '£', ' ', ','], '', $merged);
    $numericPart = trim($numericPart);
    
    \Log::info("mergeSplitPrice: numericPart=" . json_encode($numericPart));
    
    // Check if it's a valid number
    if (is_numeric($numericPart) && $numericPart !== '') {
        // Also sanity check: merged price should be >= 1 (prices less than 1 are unlikely)
        // and should not be extremely large (more than 10 billion)
        $num = (float)$numericPart;
        if ($num >= 1 && $num <= 10000000000) {
            \Log::info("mergeSplitPrice: valid merged price returned: {$merged}");
            return $merged;
        }
        \Log::info("mergeSplitPrice: numeric but out of range: {$num}");
    }
    
    // If merged result doesn't look like a valid price, return original
    \Log::info("mergeSplitPrice: invalid merged result, returning original current value");
    return $current;
}

// ============================================================

/**
 * Format expiry date to Y-m
 */
private function formatExpiryDate($expiry) {
    if (empty($expiry)) {
        return now()->format('Y-m');
    }

    // Already correct format
    if (preg_match('/^\d{4}-\d{2}$/', $expiry)) {
        return $expiry;
    }

    $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d', 'Y-m', 'd-m-Y', 'n/j/Y'];

    foreach ($formats as $format) {
        try {
            $date = \Carbon\Carbon::createFromFormat($format, $expiry);
            if ($date) {
                return $date->format('Y-m');
            }
        } catch (\Exception $e) {
            // try next format
        }
    }

    \Log::warning("Could not parse expiry date: {$expiry} — defaulting to current month");
    return now()->format('Y-m');
}

// ============================================================

/**
 * Generate unique 6-digit product code
 */
private function generateUniqueCode() {
    do {
        $code = rand(100000, 999999);
    } while (productsModel::where('code', $code)->where('account', getCurrentShopId())->exists());

    return $code;
}

// ============================================================

/**
 * Create a stock entry record
 */
private function createStockEntry($productId, $itemName, $brand, $quantity, $bPrice, $sPrice) {
    try {
        $seq = stock::where('account', getCurrentShopId())
                    ->whereDate('created_at', date('Y-m-d'))
                    ->count() + 1;

        $uuid_short = 'Stock-' . date('YMd') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT)
                    . '-' . $itemName . '-' . $brand;

        $restock           = new stock();
        $restock->name     = $uuid_short;
        $restock->productId = $productId;
        $restock->quantity = $quantity;
        $restock->tBprice  = $quantity * $bPrice;
        $restock->bPrice   = $bPrice;
        $restock->sPrice   = $sPrice;
        $restock->account  = getCurrentShopId();
        $restock->save();

        \Log::info("Stock entry created: {$uuid_short}");

        $log              = new logModal();
        $log->title       = 'Stock Created (Bulk Import)';
        $log->description = $uuid_short . ' created from bulk import by ' . session('username');
        $log->save();

    } catch (\Exception $e) {
        \Log::error('createStockEntry failed: ' . $e->getMessage());
    }
}

/**
 * Read CSV file and normalize headers
 * Handles files with extra header rows (like OSPOS exports)
 */

/**
 * Parse price - handle various formats
 */

/**
 * Format expiry date to Y-m format
 */

/**
 * Create stock entry with calculated total buying price
 */

/**
 * Download Excel template
 */
public function downloadTemplate() {
    $filePath = storage_path('app/templates/product_template.xlsx');
    
    // If template doesn't exist, create it
    if (!file_exists($filePath)) {
        $this->createExcelTemplate($filePath);
    }
    
    return response()->download($filePath, 'Product_Import_Template.xlsx');
}

/**
 * Create comprehensive Excel template file
 */
private function createExcelTemplate($filePath) {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->setTitle('Products');

    // Define headers
    $headers = [
        'ID',
        'Item Name',
        'Quantity',
        'Brand',
        'Category',
        'Unit',
        'Cost Price',
        'Selling Price',
        'Wholesale Price',
        'Discount',
        'Location',
        'Supplier',
        'Expiry',
        'Description'
    ];

    // Set headers
    foreach ($headers as $col => $header) {
        $worksheet->setCellValueByColumnAndRow($col + 1, 1, $header);
    }

    // Style headers
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4361EE']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
        'borders' => ['allBorders' => ['borderStyle' => 'thin']],
    ];

    for ($i = 1; $i <= count($headers); $i++) {
        $worksheet->getCellByColumnAndRow($i, 1)->setStyle($headerStyle);
    }

    // Add sample data row
    $sampleData = [
        'SKU-2024-001',      // ID (optional)
        'Laptop Computer',   // Item Name (required)
        '50',                // Quantity (required)
        'Dell',              // Brand
        'Electronic Devices',// Category
        'pieces',            // Unit
        '1200000',           // Cost Price
        '1500000',           // Selling Price
        '1100000',           // Wholesale Price
        '5',                 // Discount (%)
        'Shelf A1',          // Location
        'Vendor Name',       // Supplier
        '2025-12',           // Expiry (Y-m format)
        'High performance laptop'  // Description
    ];

    foreach ($sampleData as $col => $value) {
        $worksheet->setCellValueByColumnAndRow($col + 1, 2, $value);
    }

    // Set column widths
    $worksheet->getColumnDimension('A')->setWidth(15);
    $worksheet->getColumnDimension('B')->setWidth(20);
    $worksheet->getColumnDimension('C')->setWidth(12);
    $worksheet->getColumnDimension('D')->setWidth(15);
    $worksheet->getColumnDimension('E')->setWidth(18);
    $worksheet->getColumnDimension('F')->setWidth(12);
    $worksheet->getColumnDimension('G')->setWidth(15);
    $worksheet->getColumnDimension('H')->setWidth(15);
    $worksheet->getColumnDimension('I')->setWidth(18);
    $worksheet->getColumnDimension('J')->setWidth(12);
    $worksheet->getColumnDimension('K')->setWidth(15);
    $worksheet->getColumnDimension('L')->setWidth(15);
    $worksheet->getColumnDimension('M')->setWidth(12);
    $worksheet->getColumnDimension('N')->setWidth(25);

    // Freeze header row
    $worksheet->freezePane('A2');

    // Create instructions sheet
    $instructionSheet = $spreadsheet->createSheet('Instructions');
    $instructionSheet->setCellValue('A1', 'Product Import Instructions');
    $instructionSheet->getCellByColumnAndRow(1, 1)->getFont()->setBold(true)->setSize(14);

    $instructions = [
        '',
        'REQUIRED COLUMNS:',
        '• Item Name - Product name (must not be empty)',
        '• Quantity - Number of items in stock (must be 0 or greater)',
        '',
        'OPTIONAL COLUMNS:',
        '• ID - Database numeric ID (enter existing ID to update product, leave blank to create new)',
        '• Brand - Manufacturer or brand name',
        '• Category - Product category (Foods, Drinks, Furniture, Electronic Devices, Farming, Others)',
        '• Unit - Unit measurement (pieces, carton, box, set, meter, Kg, liter)',
        '• Cost Price - Buying price per unit (in Tsh)',
        '• Selling Price - Retail price per unit (in Tsh)',
        '• Wholesale Price - Bulk price per unit (in Tsh)',
        '• Discount - Discount limit percentage',
        '• Location - Storage location/warehouse',
        '• Supplier - Vendor/supplier name',
        '• Expiry - Expiry date (format: YYYY-MM or MM/DD/YYYY)',
        '• Description - Product description or remarks',
        '',
        'TIPS:',
        '1. Download the template and fill in your data',
        '2. Save file as .xlsx (Excel format) or .csv',
        '3. Do not modify header row',
        '4. Leave optional columns blank if you don\'t have data',
        '5. Use consistent formatting (dates, currency)',
        '6. Maximum file size: 10MB',
        '',
        'EXAMPLE:',
        'If you only have Item Name and Quantity, that\'s fine!',
        'ID and Brand will be auto-filled with defaults.',
    ];

    foreach ($instructions as $row => $instruction) {
        $instructionSheet->setCellValue('A' . ($row + 2), $instruction);
    }

    $instructionSheet->getColumnDimension('A')->setWidth(80);

    // Create directory if it doesn't exist
    if (!file_exists(dirname($filePath))) {
        mkdir(dirname($filePath), 0755, true);
    }

    // Save the file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($filePath);
}



    public function viewProduct(Request $req) {

        $user = Auth::user();

        if (!empty($req->input('product_id'))) {
            $product_id = $req->input('product_id');
            session(['productId' => $product_id]);

        }
    
        $product_id = session('productId');
        $products = productsModel::where('account', getCurrentShopId())->where('product_id', '=', value: $product_id)->first();

       


        $vendor = vendorModal::where('account', getCurrentShopId())->where('id', '=', $products->supplier ?? '')->first();
         if(!$vendor) {
            $vendor = vendorModal::where('account', getCurrentShopId())->where('name', '=', $products->supplier ?? '')->first();
        }
        $data = compact(
        'products','vendor'
    );

 if (strtolower(trim($user->levelStatus)) === 'admin') {
       return view('admin.viewProduct', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.viewProduct', $data);
    }
    }

    public function dltProduct(Request $Req) {
        // Handle both single and bulk delete
        $product_ids = $Req->input('product_ids', []);

        if (!empty($Req->input('product_id'))) {
            // Single delete
            $product_ids = [$Req->input('product_id')];
        }

        if (empty($product_ids)) {
            return redirect()->back()->with('error', 'No products selected for deletion');
        }

        $deletedCount = 0;
        $failedCount = 0;

        foreach ($product_ids as $product_id) {
            $dlt = productsModel::where('account', getCurrentShopId())->where('product_id', $product_id)->first();

            if ($dlt) {
                $productName = $dlt->name01;
                $dlt->delete();

                // Delete associated stock records
                $dltStock = stock::where('account', getCurrentShopId())->where('productId', $product_id)->delete();

                // Log successful deletion
                $create = new logModal();
                $create->title = 'Product delete';
                $create->description = $productName . ' Product deleted successfully By ' . session('username');
                $create->save();

                if ($dltStock) {
                    $create = new logModal();
                    $create->title = 'Stock report deleted';
                    $create->description = $productName . ' Stock deleted successfully By ' . session('username');
                    $create->save();
                }

                $deletedCount++;
            } else {
                $failedCount++;
            }
        }

        if ($deletedCount > 0) {
            $message = $deletedCount . ' product(s) deleted successfully';
            if ($failedCount > 0) {
                $message .= ', ' . $failedCount . ' failed';
            }
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Product deletion failed');
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
        $productsModel = productsModel::where('product_id', $product_id)->where('account', getCurrentShopId())->get();
    
        // Check if the product exists
        if ($productsModel) {
            foreach ($productsModel as $product) {
        $product->name01 = $name01;
        $product->name02 = $name02;
        $product->category = $category;
        $product->description = $description;
        $product->unit = $unit;
        $product->img = $imageName; // Store the filename in the database
        $product->quantity = $quantity;
        $product->bPrice = $bPrice;
        $product->sPrice = $sPrice;
        $product->wholesale = $wholesale;
        $product->discount = $discount ?? 0;
        $product->location = $location;
        $product->supplier = $supplier;
        $product->expire = $expiry;
    
            if ($req->hasFile('image')) {
                $product->img = $imageName; // Update the image if a new one is uploaded
            }
    
            $product->save();

            if($product) {
                  $create = new logModal();
            $create->title = 'Product Update';
            $create->description =  $name01.' Product Updated By '.session('username');
            $create->save();

                // If updating from main store, propagate all non-quantity field changes to all accounts
                if (getSessionAccountDisplayName() === 'Main Store') {
                    $allAccounts = accountModel::where('name', '!=', 'Main Store')->get();

                    foreach ($allAccounts as $account) {
                        $otherProduct = productsModel::where('name01', $name01)
                            ->where('account', $account->id)
                            ->first();

                        if ($otherProduct) {
                            // Product exists in this account — update all non-quantity fields only
                            $otherProduct->name02       = $name02;
                            $otherProduct->category     = $category;
                            $otherProduct->description  = $description;
                            $otherProduct->unit         = $unit;
                            $otherProduct->bPrice       = $bPrice;
                            $otherProduct->sPrice       = $sPrice;
                            $otherProduct->wholesale    = $wholesale;
                            $otherProduct->discount     = $discount ?? 0;
                            $otherProduct->location     = $location;
                            $otherProduct->supplier     = $supplier;
                            $otherProduct->expire       = $expiry;
                            // quantity is intentionally NOT updated — stays account-specific
                            $otherProduct->save();

                            // Log the propagation
                            $propagateLog = new logModal();
                            $propagateLog->title       = 'Product Sync';
                            $propagateLog->description = 'Product "' . $name01 . '" synced in account "' . $account->name . '" from Main Store by ' . session('username');
                            $propagateLog->save();
                        } else {
                            // Product does NOT exist in this account — create it with default quantity 0
                            $newProduct = new productsModel();
                            $newProduct->product_id  = $product->product_id;
                            $newProduct->name01      = $name01;
                            $newProduct->name02      = $name02;
                            $newProduct->category    = $category;
                            $newProduct->description = $description;
                            $newProduct->unit        = $unit;
                            $newProduct->img         = $imageName ?: $product->img;
                            $newProduct->quantity    = 0; // default quantity — account-specific, not synced
                            $newProduct->bPrice      = $bPrice;
                            $newProduct->sPrice      = $sPrice;
                            $newProduct->wholesale   = $wholesale;
                            $newProduct->discount    = $discount ?? 0;
                            $newProduct->location    = $location;
                            $newProduct->supplier    = $supplier;
                            $newProduct->expire      = $expiry;
                            $newProduct->account     = $account->name;
                            $newProduct->save();

                            // Log the creation
                            $createLog = new logModal();
                            $createLog->title       = 'Product Synced (Created)';
                            $createLog->description = 'Product "' . $name01 . '" created in account "' . $account->name . '" from Main Store by ' . session('username');
                            $createLog->save();
                        }
                    }
                }

             return redirect()->back()->with('success', 'Product updated successfully!');
            } else{
                 $create = new logModal();
            $create->title = 'Product Update';
            $create->description =  $name01.' Product Update Failed By '.session('username');
$create->save();
             return redirect()->back()->with('error', 'Product update Failed!');
            }
            }
        } else {
            // Handle the case where the product does not exist
            return redirect()->back()->with('error', 'Product not found.');
        }
    }

    public function newOrder(Request $request){
        $user = Auth::user();
        
        // Get selected shop from request (from shop selector dropdown)
        $requestedShopId = $request->query('shop_id');
        
        // Get selected shop from session (for both admin and regular users)
        $selectedShopId = session('selected_shop_id');
        
        // If shop is provided in request, use it (user changed the shop selector)
        if ($requestedShopId) {
            // Verify user has access to the requested shop
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                // Admin can access any shop - verify it exists
                $shopExists = accountModel::where('id', $requestedShopId)->exists();
                if ($shopExists) {
                    $selectedShopId = $requestedShopId;
                    session(['selected_shop_id' => $selectedShopId]);
                }
            } else {
                // Regular users: check if they have access to the requested shop
                $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
                if (in_array($requestedShopId, $assignedAccountIds)) {
                    $selectedShopId = $requestedShopId;
                    session(['selected_shop_id' => $selectedShopId]);
                }
            }
        }
        
        // If no shop selected, determine default shop
        if (!$selectedShopId) {
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                // Admin: use first available account or session account
                $selectedShopId = getCurrentShopId() ?? accountModel::select('id')->first()?->id;
            } else {
                // Regular user: use primary or first assigned account
                $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
                if ($primaryAccount) {
                    $selectedShopId = $primaryAccount->account;
                } else {
                    $firstAccount = UserAccount::where('user_id', $user->id)->first();
                    $selectedShopId = $firstAccount ? $firstAccount->account : getCurrentShopId();
                }
            }
            if ($selectedShopId) {
                session(['selected_shop_id' => $selectedShopId]);
            }
        }
        
        // Verify user has access to the selected shop
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($selectedShopId, $assignedAccountIds)) {
                // Fallback to primary or first assigned account
                $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
                if ($primaryAccount) {
                    $selectedShopId = $primaryAccount->account;
                } else {
                    $firstAccount = UserAccount::where('user_id', $user->id)->first();
                    $selectedShopId = $firstAccount ? $firstAccount->account : getCurrentShopId();
                }
                session(['selected_shop_id' => $selectedShopId]);
            }
        }
        
        // Get all accessible shops for the user (for the shop selector dropdown)
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $allShops = accountModel::select('id', 'name', 'location')->orderBy('name')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $allShops = collect();
            } else {
                $allShops = accountModel::whereIn('id', $assignedAccountIds)->select('id', 'name', 'location')->get();
            }
        }

         // Get active order - show both user's own orders and returned orders (any seller)
         $orders = ordersModel::where('account', $selectedShopId)
        ->whereIn('status', ['Sell', 'Pending'])
        ->orderBy('id', 'desc')
        ->first() ?? (object)[
            'order_id' => '',
            'orderName' => '',
            'cName' => '',
            'cPhone' => '',
            'served_by' => '',
            'status' => '',
        ];

         $Suspended = ordersModel::where('account', $selectedShopId)
        ->where('served_by', session('username'))
        ->where('status', 'Suspended')
        ->select('cName',DB::raw('MAX(order_id) as order_id'), DB::raw('SUM(totalPrice) as total_price'))
        ->groupBy('cName')
        ->get();

            // Get cart items - show both own orders and returned orders (Pending status)
            $cart = ordersModel::where('account', $selectedShopId)
            ->where('order_id',  $orders->order_id ?? '' )
            ->orderBy('id', 'desc')
            ->get();
            
            // Calculate totals based on the current cart items, not filtered by served_by
            // This allows editing past sales returned to orders
            $totalP = $cart->sum('totalPrice');
            $totalD = $cart->sum('discount');
            $totalDI = $cart->sum('discount_increase');
            $customers = customerModel::where('account', $selectedShopId)->get();

            // Get all active offers for the current account
            $offers = Offer::where('account', $selectedShopId)
                ->where('is_active', true)
                ->get();

            session(['totalP' => $totalP]);
            $data = compact(
            'cart','totalP','totalD','totalDI','customers','orders','Suspended','offers','allShops','selectedShopId'
        );

     if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.newOrder', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.newOrder', $data);
        }
    }
   public function search(Request $request)
{   

  // Check if user is authenticated
  if (!getCurrentShopId()) {
      return response()->json(['error' => 'Unauthorized', 'redirect' => '/login'], 401);
  }

  $query = trim($request->query('query'));

  if ($query === '') {
      return response()->json([]);
  }

  
  // Search from the current shop account
  $currentAccount = getCurrentShopId();

  $products = productsModel::where('account', $currentAccount)
      ->where(function($q) use ($query) {
          $q->where('name01', 'LIKE', "%{$query}%")
            ->orWhere('name02', 'LIKE', "%{$query}%");
      })
      ->limit(10)
      ->get([
          'id',
          'product_id',
          'name01',
          'bPrice',
          DB::raw('COALESCE(sPrice, 0) as sPrice'),
          'quantity',
          'discount'
      ]);

  return response()->json($products);
}

   /**
    * Search products for offer modal - returns all products without pagination
    */
   public function searchProductsForOffer(Request $request)
   {
       // Check if user is authenticated
       if (!getCurrentShopId()) {
           return response()->json(['error' => 'Unauthorized', 'redirect' => '/login'], 401);
       }

       $query = trim($request->query('q'));

       $productsQuery = productsModel::where('account', getCurrentShopId())
           ->where('name01', '!=', null);

       // If search query provided, filter by name or product_id
       if ($query !== '') {
           $productsQuery->where(function($q) use ($query) {
               $q->where('name01', 'LIKE', "%{$query}%")
                 ->orWhere('name02', 'LIKE', "%{$query}%")
                 ->orWhere('product_id', 'LIKE', "%{$query}%");
           });
       }

       // Get all matching products (no pagination)
       $products = $productsQuery->orderBy('name01', 'asc')
           ->get(['product_id', 'name01', 'name02', 'quantity']);

       // Format for select2 dropdown
       $formatted = $products->map(function($product) {
           return [
               'id' => $product->product_id,
               'text' => $product->name01 . ' (' . $product->name02 . ') - ' . number_format($product->quantity) . ' in stock',
               'name' => $product->name01,
               'stock' => $product->quantity
           ];
       });

       return response()->json(['results' => $formatted]);
   }


    public function restock(Request $req)
{
    $user = Auth::user();
    // Get selected date, default to today
    $selectedDate = $req->input('date', date('Y-m-d'));

    // Fetch daily purchases from stock table for the selected date
    $purchases = stock::where('account', getCurrentShopId())
                      ->whereDate('created_at', $selectedDate)
                      ->orderBy('created_at', 'desc')
                      ->get();
    
    // Fetch receivings for the selected date only
    $products = recevingModel::where('account', getCurrentShopId())
                        ->whereDate('created_at', $selectedDate)
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
        $quantities = $req->input('quantity') ?? [];
        $bPrices = $req->input('bPrice') ?? [];
        $sPrices = $req->input('sPrice') ?? [];
        $wholesales = $req->input('wholesale') ?? [];
        $types = $req->input('transactionType') ?? [];
        $expiries = $req->input('expiry') ?? [];
        $receivingDate = $req->input('receivingDate');
        $served = $req->input('served') ?? $user->name;
        $operationType = $req->input('operationType', 'Receiving');

        // Ensure all inputs are arrays
        if (!is_array($product_ids)) {
            $product_ids = !empty($product_ids) ? [$product_ids] : [];
        }
        if (!is_array($quantities)) {
            $quantities = !empty($quantities) ? [$quantities] : [];
        }
        if (!is_array($bPrices)) {
            $bPrices = !empty($bPrices) ? [$bPrices] : [];
        }
        if (!is_array($sPrices)) {
            $sPrices = !empty($sPrices) ? [$sPrices] : [];
        }
        if (!is_array($wholesales)) {
            $wholesales = !empty($wholesales) ? [$wholesales] : [];
        }
        if (!is_array($types)) {
            $types = !empty($types) ? [$types] : [];
        }
        if (!is_array($expiries)) {
            $expiries = !empty($expiries) ? [$expiries] : [];
        }

        if(empty($product_ids) || empty($served)) {
            \Log::warning('Restock validation failed: missing required fields', [
                'product_ids' => $product_ids,
                'quantities' => $quantities,
                'bPrices' => $bPrices,
                'sPrices' => $sPrices,
                'wholesales' => $wholesales,
                'types' => $types,
                'served' => $served
            ]);

            // Return JSON response for AJAX requests
            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Please fill in all required fields.'], 422);
            }
            return redirect()->back()->with('error', 'Please fill in all required fields.');
        }

        // Additional validation for array lengths - skip if arrays are empty
        $count = is_array($product_ids) ? count($product_ids) : 0;
        if ($count > 0 && (count($quantities) !== $count || count($bPrices) !== $count || count($sPrices) !== $count ||
            count($wholesales) !== $count || count($types) !== $count || count($expiries) !== $count)) {
            $error = 'Array length mismatch. Please ensure all fields are properly filled.';
            \Log::error('Restock validation failed: array length mismatch', [
                'product_ids_count' => count($product_ids),
                'quantities_count' => count($quantities),
                'bPrices_count' => count($bPrices),
                'sPrices_count' => count($sPrices),
                'wholesales_count' => count($wholesales),
                'types_count' => count($types),
                'expiries_count' => count($expiries)
            ]);

            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => $error], 422);
            }
            return redirect()->back()->with('error', $error);
        }

        $OrdersIds = 'Rec_'.Uuid::uuid4();
        $allSaved = true;
        $errorMessage = '';

        foreach ($product_ids as $index => $product_id) {

            $quantity = (int)$quantities[$index];
            $bPrice = (float)$bPrices[$index];
            $sPrice = (float)$sPrices[$index];
            $wholesale = (float)$wholesales[$index];
            $expiry = $expiries[$index];
            $type = (string)$types[$index];
            
            if(strtolower($type) == 'credit') {
                $isDebt = 1;
                $isPaid = 0;

            } elseif(strtolower($type) == 'cash') {
                $isDebt = 0;
                $isPaid = 1;
            }else {
                $isDebt = 0;
                $isPaid = 0;
            }

            if ($operationType === 'Return') {
                // Return flow from "Add Receiving" tab:
                // allows returning even when there is no pending receiving record.
                $mainProduct = productsModel::where('account', getCurrentShopId())
                    ->where('product_id', $product_id)
                    ->first();

                if (!$mainProduct) {
                    $allSaved = false;
                    $errorMessage = 'Product not found for return.';
                    break;
                }

                $newStockQty = max(0, ((int) $mainProduct->quantity) + $quantity);
                $mainProduct->quantity = $newStockQty;
                $mainProduct->save();

                $returnRec = new recevingModel();
                $returnRec->receivingId = $OrdersIds;
                $returnRec->productId = $product_id;
                $returnRec->quantity = $quantity;
                $returnRec->price = $bPrice;
                $returnRec->sellingPrice = $sPrice;
                $returnRec->wholesalePrice = $wholesale;
                $returnRec->isDebt = $isDebt;
                $returnRec->isPaid = $isPaid;
                $returnRec->expiry = $expiry;
                $returnRec->supplier = $supplier;
                $returnRec->account = getCurrentShopId();
                $returnRec->served_by = $served ?? session('username');
                $returnRec->status = 'Returned';

                if (!empty($receivingDate)) {
                    $returnRec->created_at = $receivingDate . ' ' . date('H:i:s');
                    $returnRec->updated_at = $receivingDate . ' ' . date('H:i:s');
                }

                $saveResult = $returnRec->save();

                if (!$saveResult) {
                    $allSaved = false;
                    $errorMessage = 'Failed to save return record for product ' . $product_id;
                    break;
                }

                $create = new logModal();
                $create->title = 'Stock Return';
                $create->description = 'Stock returned from receiving flow by ' . session('username');
                $create->save();
            } else {
                // Create new receiving record
                $product = new recevingModel();
                $product->receivingId = $OrdersIds;
                $product->productId = $product_id;
                $product->quantity = $quantity;
                $product->price = $bPrice;
                $product->sellingPrice = $sPrice;
                $product->wholesalePrice = $wholesale;
                $product->isDebt = $isDebt;
                $product->isPaid = $isPaid;
                $product->expiry = $expiry;
                $product->supplier = $supplier;
                $product->account = getCurrentShopId();
                $product->served_by = $served ?? session('username');

                if (!empty($receivingDate)) {
                    $product->created_at = $receivingDate . ' ' . date('H:i:s');
                    $product->updated_at = $receivingDate . ' ' . date('H:i:s');
                }

                $saveResult = $product->save();

                \Log::info('Receiving save result for product ' . $product_id . ': ' . ($saveResult ? 'success' : 'failed'));

                if (!$saveResult) {
                    \Log::error('Failed to save receiving for product ' . $product_id);
                    $allSaved = false;
                    $errorMessage = 'Failed to save receiving record for product ' . $product_id;
                    break;
                } else {
                    $create = new logModal();
                    $create->title = 'Stock Log';
                    $create->description = 'New Stock Added By ' . session('username');
                    $create->save();
                }
            }
        }

        // Return appropriate response based on whether it's AJAX or regular request
        if ($req->expectsJson()) {
            if ($allSaved) {
                $okMessage = $operationType === 'Return' ? 'Returns saved successfully!' : 'Receivings saved successfully!';
                return response()->json(['success' => true, 'message' => $okMessage], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Error saving some receivings.'], 400);
            }
        }

        // For non-AJAX requests, redirect
        if ($allSaved) {
            $okMessage = $operationType === 'Return' ? 'Returns saved successfully!' : 'Receivings saved successfully!';
            return redirect()->back()->with('success', $okMessage);
        } else {
            return redirect()->back()->with('error', 'Error saving receivings. Please try again.');
        }
    }

    $data = compact('products', 'purchases', 'selectedDate');

    if (strtolower(trim($user->levelStatus)) === 'admin') {
        return view('admin.restock', $data);
    }
    if(!empty($user->levelStatus)) {
        return view('user.restock', $data);
    }
}


  public function restockProd(Request $req)
{
    $product_id = $req->input('product_id');
    $shopFilter  = $req->input('shop', '');

    // Use shop filter if provided, otherwise fall back to session account
    $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

    // Lock the record to prevent race conditions
    $receivings = recevingModel::where('account', $accountId)
                ->where('productId', $product_id)
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

    // Check if already approved
    if (!$receivings || $receivings->status == 'Approved') {
        return redirect()->back()->with('error', 'This product has already been approved');
    }

    $product = productsModel::where('product_id', $product_id)
                ->where('account', $accountId)
                ->first();

    if (!$product) {
        return redirect()->back()->with('error', 'Product not found');
    }

    // Update main stock
    $product->quantity += $receivings->quantity;
    $product->supplier = $receivings->supplier;
    $product->expire = $receivings->expiry;
    $product->wholesale = $receivings->wholesalePrice;
    $product->bPrice = $receivings->price;
    $product->sPrice = $receivings->sellingPrice;
    $product->save();

    // Update receiving status
    $receivings->status = 'Approved';
    $receivings->save();

    if($receivings) {
            // Generate stock name
          $productInfo = productsModel::where('product_id', $product_id)->first();

$uuid_short = 'Stock-' . date('Ymd') . '-' . str_pad(
    stock::where('account', $accountId)
        ->whereDate('created_at', date('Y-m-d'))
        ->count() + 1,
    4, '0', STR_PAD_LEFT
) . '-' . ($productInfo->name01 ?? 'Unknown');

                // Save restock record
                $restock = new stock();
                $restock->name = $uuid_short;
                $restock->productId = $product_id;
                $restock->quantity = $receivings->quantity;
                $restock->bPrice = $receivings->price;
                $restock->sPrice = $receivings->sellingPrice;
                $restock->tBprice = $receivings->quantity * $receivings->price;
                $restock->account = $accountId;
                $restock->save();

                if($restock) {

                     $create = new logModal();
            $create->title = 'Stock Log';
            $create->description =  '  New Stock Addedd By '.session('username');
            $create->save();


                }
    }
    // Create log
    $create = new logModal();
    $create->title = 'Stock Log';
    $create->description = $product->name01.' Stock Added to be used By '.session('username');
    $create->save();

    return redirect()->back()->with('success', 'Product restocked successfully!');
}
    public function returnStock(Request $req) {

        $product_id = $req->input('product_id');
        $stockQ = $req->input('quantity');

        $get = productsModel::where('account', getCurrentShopId())->where('product_id', '=', $product_id)->first();
        
        $recive = recevingModel::where('account', getCurrentShopId())->where('productId', '=', $product_id)->first();

        if($recive->quantity < 1) {
            return redirect()->back()->with('error', 'No stock to return');
        }
        if($stockQ > 0) {
            $get->quantity -= $stockQ;
            $get->save();

            $recive->quantity -= $stockQ;
            $recive->save();

            $create = new logModal();
            $create->title = 'Stock Log';
            $create->description =  $get->name01.' Stock Returned '. $stockQ .' By '.session('username');
$create->save();
            return redirect()->back()->with('success', 'Returned Successfully');

        } else {
            return redirect()->back()->with('error', 'Out of stock to return');
        }
    }
    // In your controller
public function getReceivingsByDate(Request $request)
{
    $date = $request->get('date', date('Y-m-d'));
    $account = Auth::user()->account;
    
    // Get receivings for the specific date with product details
    $receivings = DB::table('restock')
        ->leftJoin('products', 'restock.product_id', '=', 'products.product_id')
        ->where('restock.account', $account)
        ->whereDate('restock.created_at', $date)
        ->select(
            'restock.id',
            'restock.product_id',
            'restock.quantity',
            'restock.bPrice as price',
            'restock.isPaid',
            'restock.status',
            'restock.supplier',
            'restock.served_by',
            'restock.created_at',
            'products.name01 as productName'
        )
        ->orderBy('restock.created_at', 'desc')
        ->get();
    
    // Calculate stats
    $totalValue = 0;
    $totalItems = 0;
    $approvedItems = 0;
    $approvedValue = 0;
    $pendingItems = 0;
    $pendingValue = 0;
    $creditItems = 0;
    $creditValue = 0;
    
    foreach ($receivings as $item) {
        $itemTotal = $item->price * $item->quantity;
        $totalValue += $itemTotal;
        $totalItems += $item->quantity;
        
        if ($item->status == 'Approved') {
            $approvedItems += $item->quantity;
            $approvedValue += $itemTotal;
        } else {
            $pendingItems += $item->quantity;
            $pendingValue += $itemTotal;
        }
        
        if ($item->isPaid == 0) { // 0 means credit
            $creditItems += $item->quantity;
            $creditValue += $itemTotal;
        }
    }
    
    return response()->json([
        'success' => true,
        'receivings' => $receivings,
        'stats' => [
            'totalValue' => $totalValue,
            'totalItems' => $totalItems,
            'approvedItems' => $approvedItems,
            'approvedValue' => $approvedValue,
            'pendingItems' => $pendingItems,
            'pendingValue' => $pendingValue,
            'creditItems' => $creditItems,
            'creditValue' => $creditValue
        ]
    ]);
}
// In your controller (dltrestock method or wherever you handle delete/return)
public function dltrestock(Request $req)
{
    $action = $req->input('action', 'return');
    $productId = $req->input('product_id');
    $quantity = (int) $req->input('quantity', 0);
    $reason = $req->input('reason', '');
    $returnMode = $req->input('return_mode', 'auto'); // auto | receiving_only | stock_and_receiving

            $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

    if (empty($productId)) {
        return redirect()->back()->with('error', 'Product is required');
    }

    if ($action === 'delete') {
    $receivingId = $req->input('receiving_id');
    
    if($receivingId) {
        // Use exact row ID if provided (fixes deleting only single specific row)
        $receiving = recevingModel::where('id', $receivingId)
            ->first();
    }

        if (!$receiving) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $receiving->delete();

        stock::where('productId', $productId)
            ->where('account', getCurrentShopId())
            ->whereDate('created_at', $receiving->created_at)
            ->delete();

        logModal::create([
            'title' => 'Receiving Deleted',
            'description' => 'Receiving record deleted for product ID: ' . $productId . ' by ' . session('username')
        ]);

        return redirect()->back()->with('success', 'Receiving record deleted successfully!');
    }

    $receiving = recevingModel::where('productId', $productId)
        ->where('account', getCurrentShopId())
        ->where('quantity', '>', 0)
        ->where('status', '!=', 'Returned')
        ->orderBy('id', 'desc')
        ->first();

    if (!$receiving) {
        return redirect()->back()->with('error', 'Product not found in receiving records!');
    }

    if ($quantity <= 0) {
        return redirect()->back()->with('error', 'Please enter a valid quantity to return!');
    }

    $currentQuantity = (int) $receiving->quantity;
    if ($quantity > $currentQuantity) {
        return redirect()->back()->with('error', 'Return quantity cannot exceed current quantity!');
    }

    // Decide whether to affect stock:
    // - Approved => stock should be reduced (unless forced receiving_only)
    // - Pending  => only receiving is reduced (unless forced stock_and_receiving)
    $shouldAffectStock = false;
    if ($returnMode === 'stock_and_receiving') {
        $shouldAffectStock = true;
    } elseif ($returnMode === 'receiving_only') {
        $shouldAffectStock = false;
    } else {
        $shouldAffectStock = ($receiving->status === 'Approved');
    }

    if ($shouldAffectStock) {
        $product = productsModel::where('product_id', $productId)
            ->where('account', getCurrentShopId())
            ->first();

        if ($product) {
            $newQty = max(0, ((int) $product->quantity) - $quantity);
            $product->quantity = $newQty;
            $product->save();
        }
    }

    if ($quantity === $currentQuantity) {
        $receiving->quantity = 0;
        $receiving->status = 'Returned';
        $receiving->save();

        stock::where('productId', $productId)
            ->where('account', getCurrentShopId())
            ->whereDate('created_at', $receiving->created_at)
            ->delete();

        $message = 'All quantities returned successfully!';
    } else {
        $receiving->quantity = $currentQuantity - $quantity;
        $receiving->save();

        $stockRow = stock::where('productId', $productId)
            ->where('account', getCurrentShopId())
            ->whereDate('created_at', $receiving->created_at)
            ->first();

        if ($stockRow) {
            $stockRow->quantity = $receiving->quantity;
            $stockRow->tBprice = $receiving->quantity * $receiving->price;
            $stockRow->save();

            $message = $quantity . ' units returned successfully!';
        }
    }

    logModal::create([
        'title' => 'Product Return',
        'description' => $quantity . ' units of product ID: ' . $productId .
            ' returned (' . $returnMode . '). Reason: ' . $reason . ' by ' . session('username')
    ]);

    return redirect()->back()->with('success', $message);
}

    /**
     * Show page to make new receiving
     */
    public function makeReceiving(Request $req)
    {
        $user = Auth::user();
        
        // Get selected shop from request (from shop selector dropdown)
        $requestedShopId = $req->query('shop_id');
        
        // Get selected shop from session (for both admin and regular users)
        $selectedShopId = session('selected_shop_id');
        
        // If shop is provided in request, use it (user changed the shop selector)
        if ($requestedShopId) {
            // Verify user has access to the requested shop
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                // Admin can access any shop - verify it exists
                $shopExists = accountModel::where('id', $requestedShopId)->exists();
                if ($shopExists) {
                    $selectedShopId = $requestedShopId;
                    session(['selected_shop_id' => $selectedShopId]);
                }
            } else {
                // Regular users: check if they have access to the requested shop
                $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
                if (in_array($requestedShopId, $assignedAccountIds)) {
                    $selectedShopId = $requestedShopId;
                    session(['selected_shop_id' => $selectedShopId]);
                }
            }
        }
        
        // If no shop selected, determine default shop
        if (!$selectedShopId) {
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                // Admin: use first available account or session account
                $selectedShopId = getCurrentShopId() ?? accountModel::select('id')->first()?->id;
            } else {
                // Regular user: use primary or first assigned account
                $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
                if ($primaryAccount) {
                    $selectedShopId = $primaryAccount->account;
                } else {
                    $firstAccount = UserAccount::where('user_id', $user->id)->first();
                    $selectedShopId = $firstAccount ? $firstAccount->account : getCurrentShopId();
                }
            }
            if ($selectedShopId) {
                session(['selected_shop_id' => $selectedShopId]);
            }
        }
        
        // Verify user has access to the selected shop
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($selectedShopId, $assignedAccountIds)) {
                // Fallback to primary or first assigned account
                $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
                if ($primaryAccount) {
                    $selectedShopId = $primaryAccount->account;
                } else {
                    $firstAccount = UserAccount::where('user_id', $user->id)->first();
                    $selectedShopId = $firstAccount ? $firstAccount->account : getCurrentShopId();
                }
                session(['selected_shop_id' => $selectedShopId]);
            }
        }
        
        // Get all accessible shops for the user (for the shop selector dropdown)
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $allShops = accountModel::select('id', 'name', 'location')->orderBy('name')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $allShops = collect();
            } else {
                $allShops = accountModel::whereIn('id', $assignedAccountIds)->select('id', 'name', 'location')->get();
            }
        }

        // Always use today's date - no date parameter allowed
        $today = date('Y-m-d');

        // Fetch receivings for today only (only non-returns) from selected shop
        $products = recevingModel::where('account', $selectedShopId)
            ->where('is_return', '!=', 1)
            ->whereDate('created_at', $today)
            ->orderBy('id', 'desc')
            ->get();

        $productIds = $products->pluck('productId')->unique();
        $productMap = productsModel::whereIn('product_id', $productIds)
            ->pluck('name01', 'product_id');

        foreach ($products as $item) {
            $item->productName = $productMap[$item->productId] ?? 'Unknown';
        }

        $purchases = stock::where('account', $selectedShopId)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = compact('products', 'purchases', 'allShops', 'selectedShopId');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.makeReceiving', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.makeReceiving', $data);
        }
    }

    /**
     * Show page to view all receivings (no returns)
     */
    public function viewReceivings(Request $req)
    {
        $user = Auth::user();
        $selectedDate = $req->input('date', date('Y-m-d'));
        $statusFilter = $req->input('status', 'all');
        $fromDate = $req->input('from_date', '');
        $toDate = $req->input('to_date', '');
        $shopFilter = $req->input('shop', '');

        // Get user's assigned accounts
        $userAccounts = $user->accounts()->pluck('account')->toArray();
        if (Auth::user()->account) {
            $userAccounts[] = Auth::user()->account;
        }
        $userAccounts = array_unique($userAccounts);

        // Build base query with shop filtering
        $query = recevingModel::where('is_return', '!=', 1);

        // Apply shop/account filter based on user role
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin: show all receivings by default, optionally filter by selected shop
            if (!empty($shopFilter)) {
                // Show only receivings where this shop is the account (receiving shop)
                $query->where('account', $shopFilter);
            }
        } else {
            // Regular user: only show receivings from their assigned shops
            if (empty($userAccounts)) {
                $query->where('id', '=', 0);
            } else {
                // Show receivings where the account is one of user's assigned shops
                $query->whereIn('account', $userAccounts);
            }
            
            // Additional shop filter if selected (must be within user's assigned shops)
            if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                $query->where('account', $shopFilter);
            }
        }

        // Apply status filter
        if ($statusFilter != 'all') {
            $query->where('status', $statusFilter);
        }

        // Apply date range filter
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }
        // Only filter by single date if a date is selected and no date range
        else if (!empty($selectedDate)) {
            $query->whereDate('created_at', $selectedDate);
        }

        $products = $query->orderBy('id', 'desc')->get();

        // ── Fetch returns for the same date/date-range scope ──
        $returnsQuery = recevingModel::where('is_return', 1);
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            if (!empty($shopFilter)) {
                $returnsQuery->where('account', $shopFilter);
            }
        } else {
            if (empty($userAccounts)) {
                $returnsQuery->where('id', '=', 0);
            } else {
                $returnsQuery->whereIn('account', $userAccounts);
            }
            if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                $returnsQuery->where('account', $shopFilter);
            }
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $returnsQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } else if (!empty($selectedDate)) {
            $returnsQuery->whereDate('created_at', $selectedDate);
        }
        $returns = $returnsQuery->get();

        // Build a lookup: productId => total return quantity for this scope
        $returnQtyMap = [];
        $totalReturnValue = 0;
        foreach ($returns as $ret) {
            $pid = $ret->productId;
            $qty = (int)($ret->quantity ?? 0);
            $price = (float)($ret->price ?? 0);
            if (!isset($returnQtyMap[$pid])) {
                $returnQtyMap[$pid] = 0;
            }
            $returnQtyMap[$pid] += $qty;
            $totalReturnValue += $qty * $price;
        }

        // Get unique account IDs and supplier IDs for eager loading
        $accountIds = $products->pluck('account')->unique()->filter();
        $supplierIds = $products->pluck('supplier')->unique()->filter();
        $servedIds = $products->pluck('served_by')->unique()->filter();

        // Fetch account names (shops) and supplier names (vendors)
        $accountsMap = accountModel::whereIn('id', $accountIds)->pluck('name', 'id');
        $suppliersMap = vendorModal::whereIn('id', $supplierIds)->pluck('name', 'id');
        $servedMap = usersModel::whereIn('id', $servedIds)->pluck('name', 'id');

        // Get product names
        $productIds = $products->pluck('productId')->unique();
        $productMap = productsModel::whereIn('product_id', $productIds)
            ->pluck('name01', 'product_id');

        // Process each receiving to add display names
        foreach ($products as $item) {
            $item->productName = $productMap[$item->productId] ?? 'Unknown';
            $item->accountName = $accountsMap[$item->account] ?? 'Unknown Shop';
            $item->supplierName = $suppliersMap[$item->supplier] ?? 'Unknown Supplier';
            $item->servedByName = $servedMap[$item->served_by] ?? 'Unknown';
        }

        // Get shops list for filter dropdown
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            // For regular users, only show their assigned shops
            $shops = accountModel::whereIn('id', $userAccounts)->get();
        }

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate', 'shopFilter', 'shops', 'returnQtyMap', 'totalReturnValue');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.viewReceivings', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.viewReceivings', $data);
        }
    }

    /**
     * Show page to make new return
     */
    public function makeReturn(Request $req)
    {
        $user = Auth::user();
        $selectedDate = $req->input('date', date('Y-m-d'));
        $statusFilter = 'all';
        $fromDate = '';
        $toDate = '';
        $today = date('Y-m-d');

        // Get selected shop from request (from shop selector dropdown)
        $requestedShopId = $req->query('shop_id');

        // Get selected shop from session (for both admin and regular users)
        $selectedShopId = session('selected_shop_id');

        // If shop is provided in request, use it (user changed the shop selector)
        if ($requestedShopId) {
            // Verify user has access to the requested shop
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                // Admin can access any shop - verify it exists
                $shopExists = accountModel::where('id', $requestedShopId)->exists();
                if ($shopExists) {
                    $selectedShopId = $requestedShopId;
                    session(['selected_shop_id' => $selectedShopId]);
                }
            } else {
                // Regular users: check if they have access to the requested shop
                $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
                if (in_array($requestedShopId, $assignedAccountIds)) {
                    $selectedShopId = $requestedShopId;
                    session(['selected_shop_id' => $selectedShopId]);
                }
            }
        }

        // If no shop selected, determine default shop
        if (!$selectedShopId) {
            if (strtolower(trim($user->levelStatus)) === 'admin') {
                $selectedShopId = getCurrentShopId() ?? accountModel::select('id')->first()?->id;
            } else {
                $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
                if ($primaryAccount) {
                    $selectedShopId = $primaryAccount->account;
                } else {
                    $firstAccount = UserAccount::where('user_id', $user->id)->first();
                    $selectedShopId = $firstAccount ? $firstAccount->account : getCurrentShopId();
                }
            }
            if ($selectedShopId) {
                session(['selected_shop_id' => $selectedShopId]);
            }
        }

        // Verify user has access to the selected shop
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($selectedShopId, $assignedAccountIds)) {
                // Fallback to primary or first assigned account
                $primaryAccount = UserAccount::where('user_id', $user->id)->where('is_primary', true)->first();
                if ($primaryAccount) {
                    $selectedShopId = $primaryAccount->account;
                } else {
                    $firstAccount = UserAccount::where('user_id', $user->id)->first();
                    $selectedShopId = $firstAccount ? $firstAccount->account : getCurrentShopId();
                }
                session(['selected_shop_id' => $selectedShopId]);
            }
        }

        // Get all accessible shops for the user (for the shop selector dropdown)
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $allShops = accountModel::select('id', 'name', 'location')->orderBy('name')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $allShops = collect();
            } else {
                $allShops = accountModel::whereIn('id', $assignedAccountIds)->select('id', 'name', 'location')->get();
            }
        }

        // Fetch returns for the selected date from selected shop
        $products = recevingModel::where('account', $selectedShopId)
            ->where('is_return', 1)
            ->whereDate('created_at', $selectedDate)
            ->orderBy('id', 'desc')
            ->get();

        $productIds = $products->pluck('productId')->unique();
        $productMap = productsModel::whereIn('product_id', $productIds)
            ->pluck('name01', 'product_id');

        foreach ($products as $item) {
            $item->productName = $productMap[$item->productId] ?? 'Unknown';
        }

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate', 'today', 'allShops', 'selectedShopId');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.makeReturn', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.makeReturn', $data);
        }
    }

    /**
     * Show page to view all returns
     */
    public function viewReturns(Request $req)
    {
        $user = Auth::user();
        $selectedDate = $req->input('date', '');
        $statusFilter = $req->input('status', 'all');
        $fromDate = $req->input('from_date', '');
        $toDate = $req->input('to_date', '');
        $shopFilter = $req->input('shop', '');

        // Get user's assigned accounts
        $userAccounts = $user->accounts()->pluck('account')->toArray();
        if (Auth::user()->account) {
            $userAccounts[] = Auth::user()->account;
        }
        $userAccounts = array_unique($userAccounts);

        // Build base query — fetch only returns
        $query = recevingModel::where('is_return', 1);

        // Apply shop/account filter based on user role
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            // Admin: show returns from all shops by default, optionally filter by selected shop
            if (!empty($shopFilter)) {
                $query->where('account', $shopFilter);
            }
        } else {
            // Regular user: only show returns from their assigned shops
            if (empty($userAccounts)) {
                $query->where('id', '=', 0);
            } else {
                $query->whereIn('account', $userAccounts);
            }
            // Additional shop filter if selected (must be within user's assigned shops)
            if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                $query->where('account', $shopFilter);
            }
        }

        // Apply status filter
        if ($statusFilter != 'all') {
            $query->where('status', $statusFilter);
        }

        // Apply date range filter
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }
        // Only filter by single date if a date is selected and no date range
        else if (!empty($selectedDate)) {
            $query->whereDate('created_at', $selectedDate);
        }

        $products = $query->orderBy('id', 'desc')->get();

        $productIds = $products->pluck('productId')->unique();
        $productMap = productsModel::whereIn('product_id', $productIds)
            ->pluck('name01', 'product_id');

        foreach ($products as $item) {
            $item->productName = $productMap[$item->productId] ?? 'Unknown';
        }

        // Get shops list for filter dropdown
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            $shops = accountModel::whereIn('id', $userAccounts)->get();
        }

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate', 'shopFilter', 'shops');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.viewReturns', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.viewReturns', $data);
        }
    }
    /**
     * Receiving Report — shows item, requested qty, received qty, difference, approved qty, total price
     */
    public function receivingReport(Request $req)
    {
        $user = Auth::user();
        $selectedDate = $req->input('date', date('Y-m-d'));
        $fromDate = $req->input('from_date', '');
        $toDate = $req->input('to_date', '');
        $shopFilter = $req->input('shop', '');

        // Get user's assigned accounts
        $userAccounts = $user->accounts()->pluck('account')->toArray();
        if (Auth::user()->account) {
            $userAccounts[] = Auth::user()->account;
        }
        $userAccounts = array_unique($userAccounts);

        // ── 1. Fetch all item requests (requested quantities) ──
        $requestQuery = itemRequestModel::query();
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            if (!empty($shopFilter)) {
                $requestQuery->where('account', $shopFilter);
            }
        } else {
            if (empty($userAccounts)) {
                $requestQuery->where('id', '=', 0);
            } else {
                $requestQuery->whereIn('account', $userAccounts);
            }
            if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                $requestQuery->where('account', $shopFilter);
            }
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $requestQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $requestQuery->whereDate('created_at', $selectedDate);
        }
        $allRequests = $requestQuery->get();

        // Aggregate requested quantities by productId
        $requestedMap = [];
        foreach ($allRequests as $r) {
            $pid = $r->productId;
            if (!isset($requestedMap[$pid])) {
                $requestedMap[$pid] = 0;
            }
            $requestedMap[$pid] += (int)($r->quantity ?? 0);
        }

        // ── 2. Fetch receivings (received quantities) ──
        $receivingQuery = recevingModel::where('is_return', '!=', 1);
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            if (!empty($shopFilter)) {
                $receivingQuery->where('account', $shopFilter);
            }
        } else {
            if (empty($userAccounts)) {
                $receivingQuery->where('id', '=', 0);
            } else {
                $receivingQuery->whereIn('account', $userAccounts);
            }
            if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                $receivingQuery->where('account', $shopFilter);
            }
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $receivingQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $receivingQuery->whereDate('created_at', $selectedDate);
        }
        $allReceivings = $receivingQuery->get();

        // Aggregate received quantities by productId
        $receivedMap = [];
        foreach ($allReceivings as $rec) {
            $pid = $rec->productId;
            if (!isset($receivedMap[$pid])) {
                $receivedMap[$pid] = 0;
            }
            $receivedMap[$pid] += (int)($rec->quantity ?? 0);
        }

        // ── 2b. Fetch returns (return quantities) ──
        $returnQuery = recevingModel::where('is_return', 1);
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            if (!empty($shopFilter)) {
                $returnQuery->where('account', $shopFilter);
            }
        } else {
            if (empty($userAccounts)) {
                $returnQuery->where('id', '=', 0);
            } else {
                $returnQuery->whereIn('account', $userAccounts);
            }
            if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                $returnQuery->where('account', $shopFilter);
            }
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $returnQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $returnQuery->whereDate('created_at', $selectedDate);
        }
        $allReturns = $returnQuery->get();

        // Aggregate return quantities by productId
        $returnMap = [];
        $totalReturnQty = 0;
        foreach ($allReturns as $ret) {
            $pid = $ret->productId;
            if (!isset($returnMap[$pid])) {
                $returnMap[$pid] = 0;
            }
            $returnMap[$pid] += (int)($ret->quantity ?? 0);
            $totalReturnQty += (int)($ret->quantity ?? 0);
        }

        // ── 3. Fetch approved quantities from receivings ──
        $approvedQuery = recevingModel::where('is_return', '!=', 1)
            ->where('status', 'Approved');
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            if (!empty($shopFilter)) {
                $approvedQuery->where('account', $shopFilter);
            }
        } else {
            if (empty($userAccounts)) {
                $approvedQuery->where('id', '=', 0);
            } else {
                $approvedQuery->whereIn('account', $userAccounts);
            }
            if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                $approvedQuery->where('account', $shopFilter);
            }
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $approvedQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $approvedQuery->whereDate('created_at', $selectedDate);
        }
        $allApproved = $approvedQuery->get();

        // Aggregate approved quantities by productId
        $approvedMap = [];
        foreach ($allApproved as $rec) {
            $pid = $rec->productId;
            if (!isset($approvedMap[$pid])) {
                $approvedMap[$pid] = 0;
            }
            $approvedMap[$pid] += (int)($rec->quantity ?? 0);
        }

        // ── 3b. Fetch returned approved items (is_return = 1 AND status = 'Returned') ──
        $returnedApprovedQuery = recevingModel::where('is_return', 1)
            ->where('status', 'Returned');
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            if (!empty($shopFilter)) {
                $returnedApprovedQuery->where('account', $shopFilter);
            }
        } else {
            if (empty($userAccounts)) {
                $returnedApprovedQuery->where('id', '=', 0);
            } else {
                $returnedApprovedQuery->whereIn('account', $userAccounts);
            }
            if (!empty($shopFilter) && in_array($shopFilter, $userAccounts)) {
                $returnedApprovedQuery->where('account', $shopFilter);
            }
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $returnedApprovedQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $returnedApprovedQuery->whereDate('created_at', $selectedDate);
        }
        $allReturnedApproved = $returnedApprovedQuery->get();

        // Aggregate returned approved quantities by productId
        $returnedApprovedMap = [];
        $totalReturnedApproved = 0;
        foreach ($allReturnedApproved as $rec) {
            $pid = $rec->productId;
            if (!isset($returnedApprovedMap[$pid])) {
                $returnedApprovedMap[$pid] = 0;
            }
            $returnedApprovedMap[$pid] += (int)($rec->quantity ?? 0);
            $totalReturnedApproved += (int)($rec->quantity ?? 0);
        }

        // ── 4. Build report rows from all unique productIds ──
        $allProductIds = array_unique(array_merge(
            array_keys($requestedMap),
            array_keys($receivedMap),
            array_keys($approvedMap),
            array_keys($returnMap)
        ));

        // Fetch product names
        $productNames = [];
        if (!empty($allProductIds)) {
            $productNames = productsModel::whereIn('product_id', $allProductIds)
                ->pluck('name01', 'product_id')
                ->toArray();
        }

        // ── Supplier comes from the RECEIVING record (the batch/group) ──
        // All items in the same receivingId share the same supplier.
        // Build a map: productId => supplierId from receivings (non-returns only)
        $receivingSupplierMap = [];
        if (!empty($allReceivings)) {
            foreach ($allReceivings as $rec) {
                $pid = $rec->productId;
                if (!isset($receivingSupplierMap[$pid])) {
                    $receivingSupplierMap[$pid] = $rec->supplier;
                }
            }
        }

        // Collect unique supplier IDs from the receiving records
        $supplierIds = array_unique(array_filter(array_values($receivingSupplierMap)));
        $suppliersMap = [];
        if (!empty($supplierIds)) {
            $suppliersMap = vendorModal::whereIn('id', $supplierIds)
                ->pluck('name', 'id')
                ->toArray();
        }

        $reportRows = [];
        $totalRequested = 0;
        $totalReceived = 0;
        $totalReturned = 0;
        $totalApproved = 0;
        foreach ($allProductIds as $pid) {
            $reqQty           = $requestedMap[$pid]        ?? 0;
            $recQty           = $receivedMap[$pid]         ?? 0;
            $retQty           = $returnMap[$pid]           ?? 0;
            $appQty           = $approvedMap[$pid]         ?? 0;
            $retAppQty        = $returnedApprovedMap[$pid] ?? 0;
            // Difference = requested - received - returned
            $diff             = ($reqQty - $recQty) - ($retQty - $retAppQty);

            // Total price = approved qty * price from item_requests
            $price = 0;
            foreach ($allRequests as $r) {
                if ($r->productId === $pid) {
                    $price = (float)($r->price ?? 0);
                    break;
                }
            }
            $totalPrice = $appQty * $price;

            // Resolve supplier display name from the receiving record (batch supplier)
            $supplierId   = $receivingSupplierMap[$pid] ?? null;
            $supplierName = accountModel::where('id', $supplierId)->value('name') ?? 'Unknown Supplier';

            $reportRows[] = [
                'productId'          => $pid,
                'productName'        => $productNames[$pid] ?? 'Unknown',
                'supplierName'       => $supplierName,
                'requestedQty'       => $reqQty,
                'receivedQty'        => $recQty,
                'returnQty'          => $retQty,
                'difference'         => $diff,
                'approvedQty'        => $appQty,
                'returnedApprovedQty'=> $retAppQty,
                'totalPrice'         => $totalPrice,
            ];

            $totalRequested        += $reqQty;
            $totalReceived         += $recQty;
            $totalReturned         += $retQty;
            $totalApproved         += $appQty;
            $totalReturnedApproved += $retAppQty;
        }

        // Get shops list for filter dropdown
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            $shops = accountModel::whereIn('id', $userAccounts)->get();
        }

        $data = compact(
            'reportRows', 'selectedDate', 'fromDate', 'toDate', 'shopFilter', 'shops',
            'totalRequested', 'totalReceived', 'totalReturned', 'totalApproved', 'totalReturnedApproved'
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.receivingReport', $data);
        }
        if (!empty($user->levelStatus)) {
            return view('user.receivingReport', $data);
        }
    }


    /**
     * Process new receiving (called from makeReceiving page)
     */
    public function processReceiving(Request $req)
    {
        $user = Auth::user();
        
        if ($req->isMethod('post') && !empty($req->input('product_id'))) {
            $supplier = $req->input('supplier');
            $product_ids = $req->input('product_id');
            $quantities = $req->input('quantity') ?? [];
            $bPrices = $req->input('bPrice') ?? [];
            $sPrices = $req->input('sPrice') ?? [];
            $wholesales = $req->input('wholesale') ?? [];
            $types = $req->input('transactionType') ?? [];
            $expiries = $req->input('expiry') ?? [];
            // receivingDate parameter is ignored - always use today
            $served = $req->input('served') ?? $user->name;

            // Ensure all inputs are arrays
            if (!is_array($product_ids)) {
                $product_ids = !empty($product_ids) ? [$product_ids] : [];
            }
            if (!is_array($quantities)) {
                $quantities = !empty($quantities) ? [$quantities] : [];
            }
            if (!is_array($bPrices)) {
                $bPrices = !empty($bPrices) ? [$bPrices] : [];
            }
            if (!is_array($sPrices)) {
                $sPrices = !empty($sPrices) ? [$sPrices] : [];
            }
            if (!is_array($wholesales)) {
                $wholesales = !empty($wholesales) ? [$wholesales] : [];
            }
            if (!is_array($types)) {
                $types = !empty($types) ? [$types] : [];
            }
            if (!is_array($expiries)) {
                $expiries = !empty($expiries) ? [$expiries] : [];
            }

            if(empty($product_ids) || empty($served)) {
                return redirect()->back()->with('error', 'Please fill in all required fields.');
            }

            $OrdersIds = 'Rec_'.Uuid::uuid4();
            $allSaved = true;

            foreach ($product_ids as $index => $product_id) {
                $quantity = (int)$quantities[$index];
                $bPrice = (float)$bPrices[$index];
                $sPrice = (float)$sPrices[$index];
                $wholesale = (float)$wholesales[$index];
                $expiry = $expiries[$index];
                $type = (string)$types[$index];
                
                if(strtolower($type) == 'credit') {
                    $isDebt = 1;
                    $isPaid = 0;
                } else {
                    $isDebt = 0;
                    $isPaid = 1;
                }

                // Create new receiving record (not a return)
                $product = new recevingModel();
                $product->receivingId = $OrdersIds;
                $product->productId = $product_id;
                $product->quantity = $quantity;
                $product->price = $bPrice;
                $product->sellingPrice = $sPrice;
                $product->wholesalePrice = $wholesale;
                $product->isDebt = $isDebt;
                $product->isPaid = $isPaid;
                $product->expiry = $expiry;
                $product->supplier = $supplier;
                $product->account = getCurrentShopId();
                $product->served_by = $served ?? session('username');
                $product->is_return = 0; // This is a receiving, not a return
                
                // Use provided receivingDate or default to today
                $receivingDate = $req->input('receivingDate');
                if (!empty($receivingDate)) {
                    $product->created_at = $receivingDate . ' ' . date('H:i:s');
                    $product->updated_at = $receivingDate . ' ' . date('H:i:s');
                } else {
                    $product->created_at = now();
                    $product->updated_at = now();
                }

                $saveResult = $product->save();

                if (!$saveResult) {
                    $allSaved = false;
                } else {
                    $create = new logModal();
                    $create->title = 'Stock Log';
                    $create->description = 'New Stock Added By ' . session('username');
                    $create->save();
                }
            }

            if ($allSaved) {
                return redirect()->back()->with('success', 'Receivings saved successfully!');
            } else {
                return redirect()->back()->with('error', 'Error saving some receivings. Please try again.');
            }
        }

        return redirect()->route('make-receiving');
    }

    /**
     * Process new return (called from makeReturn page)
     * Saves as Pending — stock is NOT deducted until admin approves
     */
    public function processReturn(Request $req)
    {
        $user = Auth::user();

        if ($req->isMethod('post') && !empty($req->input('product_id'))) {
            $supplier = $req->input('supplier');
            $product_ids = $req->input('product_id');
            $quantities = $req->input('quantity') ?? [];
            $bPrices = $req->input('bPrice') ?? [];
            $sPrices = $req->input('sPrice') ?? [];
            $wholesales = $req->input('wholesale') ?? [];
            $types = $req->input('transactionType') ?? [];
            $expiries = $req->input('expiry') ?? [];
            $receivingDate = $req->input('receivingDate');
            $served = $req->input('served') ?? $user->name;
            $reason = $req->input('reason', '');

            // Ensure all inputs are arrays
            if (!is_array($product_ids)) {
                $product_ids = !empty($product_ids) ? [$product_ids] : [];
            }
            if (!is_array($quantities)) {
                $quantities = !empty($quantities) ? [$quantities] : [];
            }
            if (!is_array($bPrices)) {
                $bPrices = !empty($bPrices) ? [$bPrices] : [];
            }
            if (!is_array($sPrices)) {
                $sPrices = !empty($sPrices) ? [$sPrices] : [];
            }
            if (!is_array($wholesales)) {
                $wholesales = !empty($wholesales) ? [$wholesales] : [];
            }
            if (!is_array($types)) {
                $types = !empty($types) ? [$types] : [];
            }
            if (!is_array($expiries)) {
                $expiries = !empty($expiries) ? [$expiries] : [];
            }

            if(empty($product_ids) || empty($served)) {
                return redirect()->back()->with('error', 'Please fill in all required fields.');
            }

            $OrdersIds = 'Ret_'.Uuid::uuid4();
            $allSaved = true;

            foreach ($product_ids as $index => $product_id) {
                $quantity = (int)$quantities[$index];
                $bPrice = (float)$bPrices[$index];
                $sPrice = (float)$sPrices[$index];
                $wholesale = (float)$wholesales[$index];
                $expiry = $expiries[$index];
                $type = (string)$types[$index];

                if(strtolower($type) == 'credit') {
                    $isDebt = 1;
                    $isPaid = 0;
                } else {
                    $isDebt = 0;
                    $isPaid = 1;
                }

                // Create return record with Pending status — NO stock deduction yet
                $returnRec = new recevingModel();
                $returnRec->receivingId = $OrdersIds;
                $returnRec->productId = $product_id;
                $returnRec->quantity = $quantity;
                $returnRec->price = $bPrice;
                $returnRec->sellingPrice = $sPrice;
                $returnRec->wholesalePrice = $wholesale;
                $returnRec->isDebt = $isDebt;
                $returnRec->isPaid = $isPaid;
                $returnRec->expiry = $expiry;
                $returnRec->supplier = $supplier;
                $returnRec->account = getCurrentShopId();
                $returnRec->served_by = $served ?? session('username');
                $returnRec->status = 'Pending'; // Pending admin approval
                $returnRec->is_return = 1; // This is a return

                if (!empty($receivingDate)) {
                    $returnRec->created_at = $receivingDate . ' ' . date('H:i:s');
                    $returnRec->updated_at = $receivingDate . ' ' . date('H:i:s');
                }

                $saveResult = $returnRec->save();

                if (!$saveResult) {
                    $allSaved = false;
                } else {
                    $create = new logModal();
                    $create->title = 'Return Requested';
                    $create->description = 'Return requested: ' . $quantity . ' items. Reason: ' . $reason . ' by ' . session('username') . '. Awaiting admin approval.';
                    $create->save();
                }
            }

            if ($allSaved) {
                return redirect()->back()->with('success', 'Return request submitted successfully! Awaiting admin approval before stock is deducted.');
            } else {
                return redirect()->back()->with('error', 'Error saving some return requests. Please try again.');
            }
        }

        return redirect()->route('make-return');
    }

    /**
     * Approve a pending return (admin only)
     * Deducts stock from products and marks return as Returned
     */
    public function approveReturn(Request $req)
    {
        $returnId = $req->input('return_id');

        if (!$returnId) {
            return redirect()->back()->with('error', 'No return specified.');
        }

        // Find the pending return record
        $returnRec = recevingModel::where('id', $returnId)
            ->where('is_return', 1)
            ->where('status', 'Pending')
            ->first();

        if (!$returnRec) {
            return redirect()->back()->with('error', 'Return request not found or already processed.');
        }

        $productId = $returnRec->productId;
        $quantity  = (int) $returnRec->quantity;

        // Deduct stock from the product
        $product = productsModel::where('product_id', $productId)
            ->where('account', $returnRec->account)
            ->first();

        if ($product) {
            $newStockQty = max(0, ((int) $product->quantity) - $quantity);
            $product->quantity = $newStockQty;
            $product->save();
        }

        // Mark return as approved/returned
        $returnRec->status = 'Returned';
        $returnRec->save();

        // Log
        $create = new logModal();
        $create->title = 'Return Approved';
        $create->description = 'Return approved: ' . $quantity . ' items of product ' . $productId . ' by ' . session('username') . '. Stock deducted.';
        $create->save();

        return redirect()->back()->with('success', 'Return approved successfully! Stock has been deducted.');
    }

    /**
     * Reject a pending return (admin only)
     * Deletes the return record — no stock deduction
     */
    public function rejectReturn(Request $req)
    {
        $returnId = $req->input('return_id');

        if (!$returnId) {
            return redirect()->back()->with('error', 'No return specified.');
        }

        // Find the pending return record
        $returnRec = recevingModel::where('id', $returnId)
            ->where('is_return', 1)
            ->where('status', 'Pending')
            ->first();

        if (!$returnRec) {
            return redirect()->back()->with('error', 'Return request not found or already processed.');
        }

        $productId = $returnRec->productId;
        $quantity  = (int) $returnRec->quantity;

        // Delete the return record (no stock deduction)
        $returnRec->delete();

        // Log
        $create = new logModal();
        $create->title = 'Return Rejected';
        $create->description = 'Return rejected: ' . $quantity . ' items of product ' . $productId . ' by ' . session('username') . '. No stock deducted.';
        $create->save();

        return redirect()->back()->with('success', 'Return request rejected. No stock was deducted.');
    }

    /**
     * Approve selected receiving records
     */
    public function approveSelectedReceivings(Request $req)
    {
        $productIds = $req->input('product_ids', []);
        $shopFilter = $req->input('shop', '');
        $user = Auth::user();

        // Use shop filter if provided, otherwise fall back to session account
        $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

        if (empty($productIds)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        $approvedCount = 0;
        $failedCount = 0;

        foreach ($productIds as $productId) {
            // Find the pending receiving record for the correct shop
            $receiving = recevingModel::where('account', $accountId)
                ->where('productId', $productId)
                ->whereNotIn('status', ['Approved', 'Returned'])
                ->where('is_return', '!=', 1)
                ->first();

            if (!$receiving) {
                $failedCount++;
                continue;
            }

            // Get the product from the correct shop
            $product = productsModel::where('product_id', $productId)
                ->where('account', $accountId)
                ->first();

            if (!$product) {
                $failedCount++;
                continue;
            }

            // Update product quantity and details
            $product->quantity += $receiving->quantity;
            $product->supplier = $receiving->supplier;
            $product->expire = $receiving->expiry;
            $product->wholesale = $receiving->wholesalePrice;
            $product->bPrice = $receiving->price;
            $product->sPrice = $receiving->sellingPrice;
            $product->save();

            // Update receiving status
            $receiving->status = 'Approved';
            $receiving->save();

            // Create stock entry
            $productInfo = productsModel::where('product_id', $productId)->first();
            $uuid_short = 'Stock-' . date('Ymd') . '-' . str_pad(
                stock::where('account', $accountId)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->count() + 1,
                4, '0', STR_PAD_LEFT
            ) . '-' . ($productInfo->name01 ?? 'Unknown');

            $restock = new stock();
            $restock->name = $uuid_short;
            $restock->productId = $productId;
            $restock->quantity = $receiving->quantity;
            $restock->bPrice = $receiving->price;
            $restock->sPrice = $receiving->sellingPrice;
            $restock->tBprice = $receiving->quantity * $receiving->price;
            $restock->account = $accountId;
            $restock->save();

            // Log
            $create = new logModal();
            $create->title = 'Stock Approved (Selected)';
            $create->description = 'Stock approved for ' . ($productInfo->name01 ?? 'Unknown') . ' by ' . session('username');
            $create->save();

            $approvedCount++;
        }

        if ($approvedCount > 0) {
            return redirect()->back()->with('success', "{$approvedCount} item(s) approved successfully!" . ($failedCount > 0 ? " {$failedCount} failed." : ""));
        } else {
            return redirect()->back()->with('error', 'No items could be approved.');
        }
    }

    /**
     * Approve all pending receiving records for selected date
     */
    public function approveAllReceivings(Request $req)
    {
        $selectedDate = $req->input('date', date('Y-m-d'));
        $shopFilter   = $req->input('shop', '');
        $user = Auth::user();

        $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

        // Get all pending (non-approved, non-returned) receivings for the selected date
        $receivings = recevingModel::where('account', $accountId)
            ->whereDate('created_at', $selectedDate)
            ->whereNotIn('status', ['Approved', 'Returned'])
            ->where('is_return', '!=', 1)
            ->get();

        if ($receivings->isEmpty()) {
            return redirect()->back()->with('error', 'No pending receivings to approve.');
        }

        $approvedCount = 0;

        foreach ($receivings as $receiving) {
            $productId = $receiving->productId;

            // Get the product
            $product = productsModel::where('product_id', $productId)
                ->where('account', $accountId)
                ->first();

            if (!$product) {
                continue;
            }

            // Update product quantity and details
            $product->quantity += $receiving->quantity;
            $product->supplier = $receiving->supplier;
            $product->expire = $receiving->expiry;
            $product->wholesale = $receiving->wholesalePrice;
            $product->bPrice = $receiving->price;
            $product->sPrice = $receiving->sellingPrice;
            $product->save();

            // Update receiving status
            $receiving->status = 'Approved';
            $receiving->save();

            // Create stock entry
            $productInfo = productsModel::where('product_id', $productId)->first();
            $uuid_short = 'Stock-' . date('Ymd') . '-' . str_pad(
                stock::where('account', $accountId)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->count() + 1,
                4, '0', STR_PAD_LEFT
            ) . '-' . ($productInfo->name01 ?? 'Unknown');

            $restock = new stock();
            $restock->name = $uuid_short;
            $restock->productId = $productId;
            $restock->quantity = $receiving->quantity;
            $restock->bPrice = $receiving->price;
            $restock->sPrice = $receiving->sellingPrice;
            $restock->tBprice = $receiving->quantity * $receiving->price;
            $restock->account = $accountId;
            $restock->save();

            // Log
            $create = new logModal();
            $create->title = 'Stock Approved (All)';
            $create->description = 'Stock approved for ' . ($productInfo->name01 ?? 'Unknown') . ' by ' . session('username');
            $create->save();

            $approvedCount++;
        }

        return redirect()->back()->with('success', "{$approvedCount} item(s) approved successfully!");
    }

    /**
     * Approve ALL pending receiving records across ALL dates
     */
    public function approveAllReceivingsAllDates(Request $req)
    {
        $shopFilter = $req->input('shop', '');
        $user = Auth::user();

        $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

        // Get ALL pending (non-approved, non-returned) receivings regardless of date
        $receivings = recevingModel::where('account', $accountId)
            ->whereNotIn('status', ['Approved', 'Returned'])
            ->where('is_return', '!=', 1)
            ->get();

        if ($receivings->isEmpty()) {
            return redirect()->back()->with('error', 'No pending receivings to approve.');
        }

        $approvedCount = 0;

        foreach ($receivings as $receiving) {
            $productId = $receiving->productId;

            // Get the product
            $product = productsModel::where('product_id', $productId)
                ->where('account', $accountId)
                ->first();

            if (!$product) {
                continue;
            }

            // Update product quantity and details
            $product->quantity += $receiving->quantity;
            $product->supplier = $receiving->supplier;
            $product->expire = $receiving->expiry;
            $product->wholesale = $receiving->wholesalePrice;
            $product->bPrice = $receiving->price;
            $product->sPrice = $receiving->sellingPrice;
            $product->save();

            // Update receiving status
            $receiving->status = 'Approved';
            $receiving->save();

            // Create stock entry
            $productInfo = productsModel::where('product_id', $productId)->first();
            $uuid_short = 'Stock-' . date('Ymd') . '-' . str_pad(
                stock::where('account', $accountId)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->count() + 1,
                4, '0', STR_PAD_LEFT
            ) . '-' . ($productInfo->name01 ?? 'Unknown');

            $restock = new stock();
            $restock->name = $uuid_short;
            $restock->productId = $productId;
            $restock->quantity = $receiving->quantity;
            $restock->bPrice = $receiving->price;
            $restock->sPrice = $receiving->sellingPrice;
            $restock->tBprice = $receiving->quantity * $receiving->price;
            $restock->account = $accountId;
            $restock->save();

            // Log
            $create = new logModal();
            $create->title = 'Stock Approved (All Dates)';
            $create->description = 'Stock approved for ' . ($productInfo->name01 ?? 'Unknown') . ' by ' . session('username');
            $create->save();

            $approvedCount++;
        }

        return redirect()->back()->with('success', "{$approvedCount} item(s) approved successfully from all dates!");
    }

    /**
     * Undo approved receiving records
     */
   public function undoReceivings(Request $req)
{
    $productIds   = $req->input('product_ids', []);
    $shopFilter   = $req->input('shop', '');
    $accountId    = !empty($shopFilter) ? $shopFilter : getCurrentShopId();
    $receivingId  = $req->input('receiving_id');

    // Fetch records
    if ($receivingId) {
        $receivings = recevingModel::where('id', $receivingId)
            ->where('status', 'Approved')
            ->get();
    } else {
        $receivings = recevingModel::where('account', $accountId)
            ->whereIn('productId', $productIds)
            ->where('status', 'Approved')
            ->get();
    }

    if ($receivings->isEmpty()) {
        return redirect()->back()->with('error', 'No approved receivings to undo.');
    }

    $undoneCount = 0;

    // Use a transaction to ensure all or nothing updates
    \DB::transaction(function () use ($receivings, $accountId, &$undoneCount) {
        foreach ($receivings as $receiving) {
            
            // 1. Update Product Inventory safely
            $product = productsModel::where('product_id', $receiving->productId)
                ->where('account', $accountId)
                ->lockForUpdate() // Prevents race conditions
                ->first();

            if ($product) {
                $product->quantity = max(0, ((int) $product->quantity) - $receiving->quantity);
                $product->save();
            }

            // 2. Fix: Delete specific stock entry using exact timestamp comparison
            stock::where('account', $accountId)
                ->where('productId', $receiving->productId)
                ->where('created_at', $receiving->created_at) // Removed whereDate to target exact time
                ->limit(1) // Safety net to delete only one row per entry
                ->delete();

            // 3. Reset receiving status
            $receiving->status = 'Pending';
            $receiving->save();

            // 4. Create log entry
            $create = new logModal();
            $create->title = 'Receiving Undone';
            $create->description = 'Receiving undone for product ID: ' . $receiving->productId . ' by ' . session('username');
            $create->save();

            $undoneCount++;
        }
    });

    return redirect()->back()->with('success', "{$undoneCount} receiving(s) undone successfully!");
}


    /**
     * Delete selected receiving records (non-approved only)
     */
    public function deleteSelectedReceivings(Request $req)
    {
        $productIds = $req->input('product_ids', []);
        $shopFilter  = $req->input('shop', '');
        $user = Auth::user();

        $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

        if (empty($productIds)) {
            return redirect()->back()->with('error', 'No items selected for deletion.');
        }

        $deletedCount = 0;
        $failedCount = 0;

        foreach ($productIds as $productId) {
            // Build query for pending (non-approved) receiving records
            $query = recevingModel::where('account', $accountId)
                ->where('productId', $productId)
                ->where('is_return', '!=', 1);
            
            // Only restrict to pending for non-admin users
            if(Auth::user()->levelStatus != 'Admin') {
                $query->whereNotIn('status', ['Approved', 'Returned']);
            }
            
            // Delete all matching receiving records for this product
            $deletedForProduct = $query->delete();
            
            if ($deletedForProduct > 0) {
                // Get product name for logging (using first deleted record or fallback)
                $product = productsModel::where('product_id', $productId)
                    ->where('account', $accountId)
                    ->first();
                $productName = $product->name01 ?? 'Unknown';

                // Log the deletion(s)
                $create = new logModal();
                $create->title = 'Receiving Deleted (Selected)';
                $create->description = 'Receiving deleted for ' . $productName . ' by ' . session('username');
                $create->save();

                $deletedCount += $deletedForProduct;
            } else {
                $failedCount++;
            }
        }

        if ($deletedCount > 0) {
            return redirect()->back()->with('success', "{$deletedCount} receiving(s) deleted successfully!" . ($failedCount > 0 ? " {$failedCount} had no deletable records." : ""));
        } else {
            return redirect()->back()->with('error', 'No items could be deleted. Only pending receivings can be deleted.');
        }
    }

    /**
     * Save offer for a product
     */
    public function saveOffer(Request $req)
    {
        $req->validate([
            'product_id' => 'required|string',
            'offer_product_id' => 'required|string',
            'required_quantity' => 'required|integer|min:1',
            'offer_quantity' => 'required|integer|min:1',
        ]);

        // Convert checkbox value from "on" to boolean
        $isActive = $req->input('is_active') === 'on' ? true : ($req->input('is_active', true) === true);

        $offer = Offer::updateOrCreate(
            [
                'account' => getCurrentShopId(),
                'product_id' => $req->input('product_id'),
            ],
            [
                'offer_product_id' => $req->input('offer_product_id'),
                'required_quantity' => $req->input('required_quantity'),
                'offer_quantity' => $req->input('offer_quantity'),
                'is_active' => $isActive,
            ]
        );

        // Log the offer creation/update
        $product = productsModel::where('product_id', $req->input('product_id'))->first();
        $offerProduct = productsModel::where('product_id', $req->input('offer_product_id'))->first();
        
        $create = new logModal();
        $create->title = 'Offer Created/Updated';
        $create->description = 'Offer: Buy ' . $req->input('required_quantity') . ' ' . ($product->name01 ?? 'Unknown') . ' get ' . $req->input('offer_quantity') . ' ' . ($offerProduct->name01 ?? 'Unknown') . ' by ' . session('username');
        $create->save();

        // Return JSON for AJAX requests
        if ($req->expectsJson()) {
            // Reload offer with relationship
            $offer->load('offeredProduct');
            return response()->json([
                'success' => true,
                'message' => 'Offer saved successfully!',
                'offer' => $offer
            ]);
        }

        return redirect()->back()->with('success', 'Offer saved successfully!');
    }

    /**
     * Get offers for a product (API endpoint)
     */
    public function getOffers($productId)
    {
        \Log::info('Getting offers for product: ' . $productId . ' in account: ' . getCurrentShopId());
        
        $offers = Offer::where('account', getCurrentShopId())
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->with('offeredProduct:id,product_id,name01')
            ->get();

        \Log::info('Offers found: ' . $offers->count());
        foreach ($offers as $offer) {
            \Log::info('Offer: ' . $offer->id . ' - Product: ' . ($offer->offeredProduct->name01 ?? 'NOT FOUND'));
        }

        return response()->json($offers);
    }

    /**
    * Get all offers for the current account (API - returns JSON)
    */
   public function getAllOffersApi()
   {
       $offers = Offer::where('account', getCurrentShopId())
           ->where('is_active', true)
           ->get();

       // Get product names
       $productIds = $offers->pluck('product_id')->merge($offers->pluck('offer_product_id'))->unique();
       $products = productsModel::whereIn('product_id', $productIds)
           ->pluck('name01', 'product_id');

       return response()->json([
           'offers' => $offers,
           'products' => $products
       ]);
   }
   
   /**
    * Get all offers for the current account (Page view)
    */
   public function allOffers()
   {
       $offers = Offer::where('account', getCurrentShopId())
           ->where('is_active', true)
           ->get();

       // Get product names
       $productIds = $offers->pluck('product_id')->merge($offers->pluck('offer_product_id'))->unique();
       $products = productsModel::whereIn('product_id', $productIds)
           ->pluck('name01', 'product_id');

       $data = compact('offers', 'products');

       $user = Auth::user();
       if (strtolower(trim($user->levelStatus)) === 'admin') {
           return view('admin.offers', $data);
       }
       return view('user.offers', $data);
   }

    /**
     * Delete an offer
     */
    public function deleteOffer(Request $req)
    {
        $offerId = $req->input('offer_id');
        
        $offer = Offer::where('account', getCurrentShopId())
            ->where('id', $offerId)
            ->first();

        if ($offer) {
            $offer->delete();
            
            // Log the deletion
            $create = new logModal();
            $create->title = 'Offer Deleted';
            $create->description = 'Offer deleted by ' . session('username');
            $create->save();

            // Return JSON for AJAX requests
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Offer deleted successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Offer deleted successfully!');
        }

        // Return JSON error for AJAX
        if ($req->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found'
            ], 404);
        }

        return redirect()->back()->with('error', 'Offer not found');
    }

    /**
     * Check if product has active offer and return offer details
     */
    public function checkOffer($productId, $quantity)
    {
        $offer = Offer::where('account', getCurrentShopId())
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->first();

        if ($offer && $quantity >= $offer->required_quantity) {
            // Calculate how many times the offer applies
            $offerApplies = floor($quantity / $offer->required_quantity);
            $offerQuantity = $offerApplies * $offer->offer_quantity;
            
            // Get offered product details
            $offeredProduct = productsModel::where('product_id', $offer->offer_product_id)->first();

            return response()->json([
                'has_offer' => true,
                'offer' => [
                    'required_quantity' => $offer->required_quantity,
                    'offer_quantity' => $offerQuantity,
                    'offer_product_id' => $offer->offer_product_id,
                    'offer_product_name' => $offeredProduct->name01 ?? 'Unknown',
                    'offer_product_stock' => $offeredProduct->quantity ?? 0,
                ]
            ]);
        }

        return response()->json(['has_offer' => false]);
    }

    /**
     * Show offered products report with date filtering
     */
    public function offeredProductsReport(Request $request)
    {
        $user = Auth::user();
        
        // Get date filter
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        
        // Get offered products from sales table with a join for better performance and reliability
        $offeredProducts = DB::table('sales')
            ->join('products', 'sales.productId', '=', 'products.product_id')
            ->where('sales.account', getCurrentShopId())
            ->where('offered_items', 1)
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('
                sales.productId,
                products.name01 as productName,
                SUM(pQuantity) as total_quantity,
                COUNT(DISTINCT sales_id) as order_count
            ')
            ->groupBy('sales.productId', 'products.name01')
            ->orderByDesc('total_quantity')
            ->get();
        
        // Create a map for the view if needed, though the join above is more efficient
        $products = $offeredProducts->pluck('productName', 'productId');
        
        // Calculate totals
        $totalOfferedItems = $offeredProducts->sum('total_quantity');
        $totalOrdersWithOffers = $offeredProducts->sum('order_count');
        
        $data = compact('offeredProducts', 'products', 'startDate', 'endDate', 'totalOfferedItems', 'totalOrdersWithOffers');
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.offeredProductsReport', $data);
        }
        return view('user.offeredProductsReport', $data);
    }

    public function returnToMainStore(Request $request) {
        $productId = $request->input('product_id');
        
        $product = productsModel::where('product_id', $productId)
            ->where('account', getCurrentShopId())
            ->first();
        
        $MainStoreProduct = productsModel::where('product_id', $productId)
            ->where('account', 'Main Store')
            ->first();
            
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }
        
        if ($product->quantity <= 0) {
            return redirect()->back()->with('error', 'No stock available to return');
        }

        // Generate receiving ID for Main Store
        $receivingId = date('Ymd') . '-' . str_pad(
            recevingModel::whereDate('created_at', date('Y-m-d'))->count() + 1,
            6, '0', STR_PAD_LEFT
        );
        
        // Create receiving record directly for Main Store
        $receiving = new recevingModel();
        $receiving->receivingId = $receivingId;
        $receiving->productId = $product->product_id;
        $receiving->quantity = $product->quantity;
        $receiving->price = $product->sPrice;
        $receiving->is_return = 1;
        $receiving->sellingPrice = $product->sPrice;
        $receiving->wholesalePrice = $product->wPrice;
        $receiving->account = 'Main Store';
        $receiving->served_by = Auth::user()->name;
        $receiving->supplier = getCurrentShopId();
        $receiving->status = 'Not Approved';
        $receiving->save();
        
        // Remove stock from current shop
        $product->quantity = 0;
        $product->save();

        $MainStoreProduct->quantity = $product->quantity;
        $MainStoreProduct->save();
        
        // Log the action
        $log = new logModal();
        $log->title = 'Item Return';
        $log->description = $product->name01 . ' returned to Main Store by ' . session('username');
        $log->save();
        
        return redirect()->back()->with('success', 'Item successfully sent back to Main Store');
    }

    /**
     * Items Report — tracks each item: received, returned, sold, total price, remaining quantity
     */
    public function itemsReport(Request $req)
    {
        $user = Auth::user();
        $selectedDate = $req->input('date', date('Y-m-d'));
        $fromDate = $req->input('from_date');
        $toDate = $req->input('to_date');
        $accountFilter = $req->input('shop', '7');

        // Get user's assigned accounts
        $userAccounts = $user->accounts()->pluck('account')->toArray();
        if (Auth::user()->account) {
            $userAccounts[] = Auth::user()->account;
        }
        $userAccounts = array_unique($userAccounts);

        // Build base account filter
        if(empty($accountFilter)) {
            return redirect()->back()->with('error', 'Please select a shop to view the report.');   
        }

        // ── 1. Fetch all products (remaining quantity) ──
        $productsQuery = productsModel::where('name01', '!=', null);
        if (!empty($accountFilter)) {
            $productsQuery->where('account', $accountFilter);
        }
        $allProducts = $productsQuery->get(['product_id', 'name01', 'name02', 'quantity', 'bPrice', 'sPrice', 'category', 'unit']);

        // Build product map: product_id => product data
        $productMap = [];
        foreach ($allProducts as $p) {
            $productMap[$p->product_id] = $p;
        }

        // ── 2. Fetch receivings (received quantities + total price) ──
        $receivingQuery = recevingModel::where('is_return', '!=', 1);
        if (!empty($accountFilter)) {
            $receivingQuery->where('account', $accountFilter);
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $receivingQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $receivingQuery->whereDate('created_at', $selectedDate);
        }
        $allReceivings = $receivingQuery->get();

        // Aggregate received quantities and total price by productId
        $receivedMap = [];
        $receivedPriceMap = [];
        foreach ($allReceivings as $rec) {
            $pid = $rec->productId;
            if (!isset($receivedMap[$pid])) {
                $receivedMap[$pid] = 0;
                $receivedPriceMap[$pid] = 0;
            }
            $receivedMap[$pid] += (int)($rec->quantity ?? 0);
            $receivedPriceMap[$pid] += ((int)($rec->quantity ?? 0) * (float)($rec->price ?? 0));
        }

        // ── 3. Fetch returns (returned quantities) ──
        $returnQuery = recevingModel::where('is_return', 1);
        if (!empty($accountFilter)) {
            $returnQuery->where('account', $accountFilter);
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $returnQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $returnQuery->whereDate('created_at', $selectedDate);
        }
        $allReturns = $returnQuery->get();

        // Aggregate return quantities by productId
        $returnMap = [];
        foreach ($allReturns as $ret) {
            $pid = $ret->productId;
            if (!isset($returnMap[$pid])) {
                $returnMap[$pid] = 0;
            }
            $returnMap[$pid] += (int)($ret->quantity ?? 0);
        }

        // ── 4. Fetch sales (sold quantities + total price) ──
        $salesQuery = salsModel::query();
        if (!empty($accountFilter)) {
            $salesQuery->where('account', $accountFilter);
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $salesQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $salesQuery->whereDate('created_at', $selectedDate);
        }
        $allSales = $salesQuery->get(['productId', 'pQuantity', 'totalPrice']);

        // Aggregate sold quantities and total price by productId
        $soldMap = [];
        $soldPriceMap = [];
        foreach ($allSales as $sale) {
            $pid = $sale->productId;
            if (!isset($soldMap[$pid])) {
                $soldMap[$pid] = 0;
                $soldPriceMap[$pid] = 0;
            }
            $soldMap[$pid] = (int)($sale->pQuantity ?? 0);
            $soldPriceMap[$pid] = (float)($sale->totalPrice ?? 0);
        }

        // ── 5. Build report rows ──
        $allReportRows = [];
        $totalReceived = 0;
        $totalReturned = 0;
        $totalSold = 0;
        $totalReceivedPrice = 0;
        $totalSoldPrice = 0;
        $totalRemainingQty = 0;
        $totalRemainingValue = 0;

        foreach ($productMap as $pid => $product) {
            $receivedQty  = $receivedMap[$pid]  ?? 0;
            $returnedQty  = $returnMap[$pid]    ?? 0;
            $soldQty      = $soldMap[$pid]      ?? 0;
            $receivedTotal = $receivedPriceMap[$pid] ?? 0;
            $soldTotal    = $soldPriceMap[$pid] ?? 0;
            $remainingQty = (int)($product->quantity ?? 0);
            $unitPrice    = (float)($product->sPrice ?? 0);
            $remainingValue = $remainingQty * $unitPrice;

            $allReportRows[] = [
                'productId'      => $pid,
                'productName'    => $product->name01 ?? 'Unknown',
                'productBrand'   => $product->name02 ?? '',
                'category'       => $product->category ?? '',
                'unit'           => $product->unit ?? '',
                'receivedQty'    => $receivedQty,
                'returnedQty'    => $returnedQty,
                'soldQty'        => $soldQty,
                'receivedTotal'  => $receivedTotal,
                'soldTotal'      => $soldTotal,
                'remainingQty'   => $remainingQty,
                'remainingValue' => $remainingValue,
            ];

            $totalReceived     += $receivedQty;
            $totalReturned     += $returnedQty;
            $totalSold         += $soldQty;
            $totalReceivedPrice += $receivedTotal;
            $totalSoldPrice    += $soldTotal;
            $totalRemainingQty += $remainingQty;
            $totalRemainingValue += $remainingValue;
        }

        // Sort by product name
        usort($allReportRows, function ($a, $b) {
            return strcmp($a['productName'], $b['productName']);
        });

        // Paginate report rows (15 per page)
        $perPage = 600;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentPage = max(1, (int) $currentPage);
        $totalItems = count($allReportRows);
        $lastPage = max(1, (int) ceil($totalItems / $perPage));
        $currentPage = min($currentPage, $lastPage);
        $offset = ($currentPage - 1) * $perPage;
        $reportRows = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($allReportRows, $offset, $perPage),
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Get shops list for filter dropdown
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            $shops = accountModel::where('id', $userAccounts)->get();
        }

        $data = compact(
            'reportRows', 'selectedDate', 'fromDate', 'toDate', 'accountFilter', 'shops',
            'totalReceived', 'totalReturned', 'totalSold',
            'totalReceivedPrice', 'totalSoldPrice',
            'totalRemainingQty', 'totalRemainingValue'
        );

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.itemsReport', $data);
        }
        if (!empty($user->levelStatus)) {
            return view('user.itemsReport', $data);
        }
    }
}

