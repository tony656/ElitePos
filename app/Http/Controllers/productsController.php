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
use Illuminate\Support\Facades\Cache;
use App\Models\stock;
use Illuminate\Support\Facades\DB;
use App\Models\madeni;
use App\Models\UserAccount;
use App\Models\accountModel;
use App\Models\itemRequestModel;
use App\Models\OfferItem;
use App\Models\salsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use function getCurrentShopId;
use function getuserAccounts;

class productsController extends Controller
{
      // Cache durations
    const CACHE_TTL = 86400; // 1 day
    const STATS_CACHE_TTL = 3600; // 1 hour for stats
    
    public function index()
{
    if (!canUser('view_items')) {
        abort(403, 'Unauthorized access');
    }

    $shopFilter = request('shop');
    if(!empty($shopFilter)) {
        
        session([
            'selected_shop_id' => $shopFilter
        ]);

    }
        $shopFilter = getCurrentShopId();

$shops = getuserAccounts();

    
    $query = productsModel::query();

    if(!empty($shopFilter)) {
        $query->where('account', $shopFilter);
    } 
    
    // IMPORTANT: Use paginate() instead of get()
    $perPage = request('per_page', 600);
    $products = $query->where('name01', '!=', null)
                      ->orderBy('name01', 'asc')
                      ->paginate($perPage)
                      ->appends(request()->query()); // Preserve query parameters
    
    // Extract data
    $getAllAccounts = $shops;
    $offers = Offer::where('account', $shopFilter)
            ->where('is_active', true)
            ->pluck('product_id')
            ->toArray();
    
    // Stats - these need to be calculated on ALL products, not just current page
    $allProductsQuery = productsModel::query();
    if(!empty($shopFilter)) {
        $allProductsQuery->where('account', $shopFilter);
    } else {
        $allProductsQuery->where('account', getCurrentShopId());
    }
    $allProducts = $allProductsQuery->where('name01', '!=', null)->get();
    
    $TProducts = $products->count();
  $totalCostWorth = $allProducts->sum(function($product) {
    return $product->bPrice * $product->quantity;
});

$totalSellingWorth = $products->sum(function($product) {
    return $product->sPrice * $product->quantity;
});
$Negative = productsModel::query();
        if(!empty($shopFilter)) {
        $Negative->where('account', $shopFilter);
    } else {
        $Negative->where('account', getCurrentShopId());
    }
        $Negative->where('quantity', '<', 0);
    $negatives = $Negative->get();

        $query->where('quantity', '<', 0);
    $available = $query->get();

    $outOfSyncShops = [];
    $currentShopMismatches = [];
    $mainStoreId = 7;

    $mainStoreProducts = productsModel::where('account', $mainStoreId)
        ->get(['product_id', 'bPrice', 'sPrice'])
        ->keyBy('product_id');

    $otherAccountIds = collect($getAllAccounts)
        ->pluck('id')
        ->filter(fn($id) => $id != $mainStoreId && !empty($id))
        ->toArray();

    if (!empty($otherAccountIds) && $mainStoreProducts->isNotEmpty()) {
        $allOtherProducts = productsModel::whereIn('account', $otherAccountIds)
            ->get(['account', 'product_id', 'bPrice', 'sPrice', 'name01']);

        $groupedByShop = $allOtherProducts->groupBy('account');

        foreach ($groupedByShop as $shopId => $shopProducts) {
            $mismatches = [];
            foreach ($shopProducts as $shopProduct) {
                $mainProduct = $mainStoreProducts->get($shopProduct->product_id);
                if ($mainProduct && ($mainProduct->bPrice != $shopProduct->bPrice || $mainProduct->sPrice != $shopProduct->sPrice)) {
                    $mismatches[] = [
                        'product_id' => $shopProduct->product_id,
                        'name' => $shopProduct->name01,
                        'main_bPrice' => $mainProduct->bPrice,
                        'main_sPrice' => $mainProduct->sPrice,
                        'shop_bPrice' => $shopProduct->bPrice,
                        'shop_sPrice' => $shopProduct->sPrice,
                    ];
                }
            }

            if (!empty($mismatches)) {
                $accountName = collect($getAllAccounts)->firstWhere('id', $shopId)['name'] ?? "Shop {$shopId}";
                $outOfSyncShops[] = [
                    'name' => $accountName,
                    'id' => $shopId,
                    'count' => count($mismatches),
                ];

                if ($shopId == $shopFilter) {
                    $currentShopMismatches = $mismatches;
                }
            }
        }
    }

    $data = compact('products', 'getAllAccounts', 'negatives', 'available','TProducts', 
                   'totalCostWorth', 'totalSellingWorth', 'offers', 'outOfSyncShops', 'currentShopMismatches');
    
    // Return view based on role
       return view('products', $data);


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
            
// Get all accounts except source account
$accounts = accountModel::where('id', '!=', $currentAccount)->get();

foreach ($productIds as $productId) {

    $originalProduct = productsModel::where('product_id', $productId)
        ->where('account', $currentAccount)
        ->first();

    if (!$originalProduct) {
        continue;
    }

    foreach ($accounts as $account) {

        // Skip if product already exists in this account
        $exists = productsModel::where('product_id', $productId)
            ->where('account', $account->id)
            ->exists();

        if ($exists) {
            continue;
        }

        $newProduct = new productsModel();
        $newProduct->product_id = $originalProduct->product_id;
        $newProduct->name01 = $originalProduct->name01;
        $newProduct->name02 = $originalProduct->name02;
        $newProduct->category = $originalProduct->category;
        $newProduct->unit = $originalProduct->unit;
        $newProduct->supplier = $originalProduct->supplier;
        $newProduct->location = $account->id;
        $newProduct->expire = $originalProduct->expire;
        $newProduct->description = $originalProduct->description;
        $newProduct->bPrice = $originalProduct->bPrice;
        $newProduct->sPrice = $originalProduct->sPrice;
        $newProduct->wholesale = $originalProduct->wholesale;

        if ($includeStock) {
            $newProduct->quantity = $originalProduct->quantity;
        } else {
            $newProduct->quantity = 0;
        }

        $newProduct->account = $account->id;
        $newProduct->save();

        // Create stock record only if stock is included
        if ($includeStock && $originalProduct->quantity > 0) {

            $uuid_short = 'Stock-' . date('YMd') . '-' .
                str_pad(
                    stock::where('account', $account->id)
                        ->whereDate('created_at', date('Y-m-d'))
                        ->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                ) .
                '-' . $originalProduct->name01 .
                '-' . $originalProduct->name02;

            $restock = new stock();
            $restock->name = $uuid_short;
            $restock->productId = $originalProduct->product_id;
            $restock->quantity = $originalProduct->quantity;
            $restock->tBprice = $originalProduct->quantity * $originalProduct->bPrice;
            $restock->bPrice = $originalProduct->bPrice;
            $restock->sPrice = $originalProduct->sPrice;
            $restock->account = $account->id;
            $restock->save();
        }

        $duplicatedCount++;
    }
}
            

            // Log completion
            $completeLog = new logModal();
            $completeLog->title = 'Product Duplication Complete';
            $completeLog->description = 'Successfully duplicated ' . $duplicatedCount . ' products from ' . $currentAccount . ' to ' . $targetAccount . ' by ' . Auth::user()->name;
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

    public static function clearOffersCache($account)
    {
        Cache::forget("offers:account:{$account}");
        Cache::forget('active_offers');
    }

    /**
     * Apply sorting to products query based on sort parameter
     */
    

    public function report() {
        $user = Auth::user();

        $report = stock::where('account', getCurrentShopId())->orderBy('id', 'desc')->get();

        $create = new logModal();
            $create->title = 'Products Report';
            $create->description = 'Report Generated By '.Auth::user()->name;
            $create->save();

            $data = compact(
        'report'
    );

       return view('stock', $data);
 

        
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
    $productsModel->account = 7;
    $productsModel->save();

    if ($productsModel) {
        // Log product creation
        $create = new logModal();
        $create->title = 'Product Created';
        $create->description = 'Product('. $name01 .') Created successfully By ' . Auth::user()->name;
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
                $create->description = $uuid_short . ' Created successfully By ' . Auth::user()->name;
                $create->save();
            } else {
                $create = new logModal();
                $create->title = 'Stock Creation Failed';
                $create->description = $uuid_short . ' Creation Failed By ' . Auth::user()->name;
                $create->save();
            }
        }

        return redirect()->back()->with('success', 'Product saved successfully!');
    } else {
        $create = new logModal();
        $create->title = 'Product Creation Failed';
        $create->description = 'Product Creation Failed By ' . Auth::user()->name;
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
            $product->account = 7;
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
        $sessionUsername = Auth::user()->name;
        
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
            $product->account = 7;
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
        $log->description = $uuid_short . ' created from bulk import by ' . Auth::user()->name;
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
        $products = productsModel::where('account', getCurrentShopId())->where('product_id', $product_id)->first();

        if (empty($products)) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $vendor = vendorModal::where('account', getCurrentShopId())->where('id', $products->supplier ?? '')->first();
         if(!$vendor) {
            $vendor = vendorModal::where('account', getCurrentShopId())->where('name', $products->supplier ?? '')->first();
        }
        $data = compact(
        'products','vendor'
    );

       return view('viewProduct', $data);

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
                $create->description = $productName . ' Product deleted successfully By ' . Auth::user()->name;
                $create->save();

                if ($dltStock) {
                    $create = new logModal();
                    $create->title = 'Stock report deleted';
                    $create->description = $productName . ' Stock deleted successfully By ' . Auth::user()->name;
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
        $product_id2 = $req->input('product_id2');
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
            $create->description =  $imageName.' Image moved to images folder By '.Auth::user()->name;
       $create->save();
        }
    
        // Fetch the product
        $productsModel = productsModel::where('product_id', $product_id)->where('account', getCurrentShopId())->get();
    
        // Check if the product exists
        if ($productsModel) {
            foreach ($productsModel as $product) {
                if(!empty($product_id2)) {
                    $product->product_id = $product_id2;
                }
        $product->name01 = $name01;
        $product->name02 = $name02;
        $product->category = $category;
        $product->description = $description;
        $product->unit = $unit;
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
            $create->description =  $name01.' Product Updated By '.Auth::user()->name;
            $create->save();

                // If updating from main store, propagate all non-quantity field changes to all accounts
                if (getCurrentShopId() == 7) {
                    $allAccounts = accountModel::where('id', '!=', 7)->get();

                    foreach ($allAccounts as $account) {
                        $otherProduct = productsModel::where('product_id', $product_id)
                            ->where('account', $account->id)
                            ->first();

                        if ($otherProduct) {
                            // Product exists in this account — update all non-quantity fields only
                            $otherProduct->name01       = $name01;
                            $otherProduct->name02       = $name02;
                            $otherProduct->category     = $category;
                            $otherProduct->description  = $description;
                            $otherProduct->unit         = $unit;
                            $otherProduct->img          = $product->img;
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
                            $propagateLog->description = 'Product "' . $name01 . '" synced in account "' . $account->name . '" from Main Store by ' . Auth::user()->name;
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
                            $newProduct->account     = 7;
                            $newProduct->save();

                            // Log the creation
                            $createLog = new logModal();
                            $createLog->title       = 'Product Synced (Created)';
                            $createLog->description = 'Product "' . $name01 . '" created in account "' . $account->name . '" from Main Store by ' . Auth::user()->name;
                            $createLog->save();
                        }
                    }
                }

             return redirect()->back()->with('success', 'Product updated successfully!');
            } else{
                 $create = new logModal();
            $create->title = 'Product Update';
            $create->description =  $name01.' Product Update Failed By '.Auth::user()->name;
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
        if (!canUser('create_sales')) {
            abort(403, 'Unauthorized access');
        }
        $user = Auth::user();

        // Persist order type across page reloads while creating an order
        $orderType = $request->session()->get('orderType', 'Sell');
        if ($request->has('orderType')) {
            $orderType = $request->input('orderType');
            $request->session()->put('orderType', $orderType);
        }

        // Get selected shop from request (from shop selector dropdown)
        $requestedShopId = $request->query('shop_id');

        if(!empty($requestedShopId)) {
            session(['selected_shop_id' => $requestedShopId]);
        }
        // Get selected shop from session (for both admin and regular users)
        $selectedShopId = getCurrentShopId();
        
        
        $allShops = getUserAccounts();
        $shopIds = array_column($allShops, 'id');

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
        ->where('status', 'Suspended')
        ->select('cName',DB::raw('MAX(order_id) as order_id'), DB::raw('SUM(totalPrice) as total_price'))
        ->groupBy('cName')
        ->get();

            // Get cart items - show both own orders and returned orders (Pending status)
            $cart = ordersModel::where('account', $selectedShopId)
            ->where('order_id',  $orders->order_id ?? '' )
            ->where('productId', '!=', null)
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
                ->with('requiredItems')
                ->get();

            session(['totalP' => $totalP]);
            $data = compact(
            'cart','totalP','totalD','totalDI','customers','orders','Suspended','offers','allShops','selectedShopId','orderType'
        );

            return view('newOrder', $data);
  
    }
   public function search(Request $request)
   {   

     $query = trim($request->query('query'));

     if ($query === '') {
         return response()->json([]);
     }

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
public function requestSearch(Request $request)
   {   

     $query = trim($request->query('query'));

     if ($query === '') {
         return response()->json([]);
     }

     $currentAccount = getCurrentShopId();

         $products = productsModel::where('account', 7)
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
        $accountId = $request->query('account', getCurrentShopId());

        $productsQuery = productsModel::where('account', $accountId)
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
    if (!canUser('view_receivings')) {
        abort(403, 'Unauthorized access');
    }

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
                $returnRec->served_by = $served ?? Auth::user()->name;
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
                $create->description = 'Stock returned from receiving flow by ' . Auth::user()->name;
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
                $product->served_by = $served ?? Auth::user()->name;

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
                    $create->description = 'New Stock Added By ' . Auth::user()->name;
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


        return view('restock', $data);

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
            $create->description =  '  New Stock Addedd By '.Auth::user()->name;
            $create->save();


                }
    }
    // Create log
    $create = new logModal();
    $create->title = 'Stock Log';
    $create->description = $product->name01.' Stock Added to be used By '.Auth::user()->name;
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
            $create->description =  $get->name01.' Stock Returned '. $stockQ .' By '.Auth::user()->name;
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
    $receivingId = $req->input('product_id');
    $quantity = (int) $req->input('quantity', 0);
    $reason = $req->input('reason', '');
    $returnMode = $req->input('return_mode', 'auto'); // auto | receiving_only | stock_and_receiving

            $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

    if (empty($receivingId)) {
        return redirect()->back()->with('error', 'Product is required');
    }

    if ($action === 'delete') {
    
        // Use exact row ID if provided (fixes deleting only single specific row)
        $receiving = recevingModel::where('id', $receivingId)
            ->first();
   

        if (!$receiving) {
            return redirect()->back()->with('error', 'Record not found!');
        }

 
        stock::where('productId', $receiving->productId)
            ->where('account', getCurrentShopId())
            ->whereDate('created_at', $receiving->created_at)
            ->delete();

        logModal::create([
            'title' => 'Receiving Deleted',
            'description' => 'Receiving record deleted for product ID: ' . $receiving->productId . ' by ' . Auth::user()->name
        ]);

        return redirect()->back()->with('success', 'Receiving record deleted successfully!');
    }

    $receiving = recevingModel::where('id', $receivingId)
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
        $product = productsModel::where('product_id', $receiving->productId)
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

        stock::where('productId', $receiving->productId)
            ->where('account', getCurrentShopId())
            ->whereDate('created_at', $receiving->created_at)
            ->delete();

        $message = 'All quantities returned successfully!';
    } else {
        $receiving->quantity = $currentQuantity - $quantity;
        $receiving->save();

        $stockRow = stock::where('productId', $receiving->productId)
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
        'description' => $quantity . ' units of product ID: ' . $receiving->productId .
            ' returned (' . $returnMode . '). Reason: ' . $reason . ' by ' . Auth::user()->name
    ]);

    return redirect()->back()->with('success', $message);
}

    /**
     * Show page to make new receiving
     */
    public function makeReceiving(Request $req)
    {
        if (!canUser('manage_receivings')) {
        abort(403, 'Unauthorized access');
    }
        $user = Auth::user();
        
        // Get selected shop from request (from shop selector dropdown)
        $requestedShopId = $req->query('shop_id');
        
        if(!empty($requestedShopId)) {
            session(['selected_shop_id' => $requestedShopId]);
        }
        // Get selected shop from session (for both admin and regular users)
        $selectedShopId = $requestedShopId;
        
        // Get all accessible shops for the user (for the shop selector dropdown)
        $allShops = getUserAccounts();
        $shopIds = array_column($allShops, 'id');

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

            return view('makeReceiving', $data);
 
    }


    public function mainReceiving(Request $req)
    {
        $user = Auth::user();
        
       
        $shopIds = 7;

        // Always use today's date - no date parameter allowed
        $today = date('Y-m-d');

        // Fetch receivings for today only (only non-returns) from selected shop
        $products = recevingModel::where('account', $shopIds)
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

        $purchases = stock::where('account', $shopIds)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = compact('products', 'purchases');

            return view('main-receiving', $data);
 
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
        $shops = getUserAccounts();
        $userAccounts = array_column($shops, 'id');

        // Build base query with shop filtering
        $query = recevingModel::where('is_return', '!=', 1);
  
            if (!empty($shopFilter)) {
                // Show only receivings where this shop is the account (receiving shop)
                $query->where('account', $shopFilter);
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
        

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate', 'shopFilter', 'shops', 'returnQtyMap', 'totalReturnValue');


            return view('viewReceivings', $data);
 
    }

    public function mainReceivings(Request $req)
    {
        $user = Auth::user();
        $selectedDate = $req->input('date', date('Y-m-d'));
        $statusFilter = $req->input('status', 'all');
        $fromDate = $req->input('from_date', '');
        $toDate = $req->input('to_date', '');
        $shopFilter = 7;
        $shops = getUserAccounts();
        $userAccounts = array_column($shops, 'id');

        // Build base query with shop filtering
        $query = recevingModel::where('is_return', '!=', 1);

            // Admin: show all receivings by default, optionally filter by selected shop
            if (!empty($shopFilter)) {
                // Show only receivings where this shop is the account (receiving shop)
                $query->where('account', $shopFilter);
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
            if (!empty($shopFilter)) {
                $returnsQuery->where('account', $shopFilter);
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
        

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate', 'shopFilter', 'shops', 'returnQtyMap', 'totalReturnValue');


            return view('main-receivings', $data);
   
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

        if(!empty($requestedShopId)) {
            session(['selected_shop_id' => $requestedShopId]);
        }
        // Get selected shop from session (for both admin and regular users)
        $selectedShopId = $requestedShopId;

        $allShops = getUserAccounts();
        $shopIds = array_column($allShops, 'id');

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


            return view('makeReturn', $data);

    }


    public function mainReturn(Request $req)
    {
        $user = Auth::user();
        $selectedDate = $req->input('date', date('Y-m-d'));
        $statusFilter = 'all';
        $fromDate = '';
        $toDate = '';
        $today = date('Y-m-d');

        $shopIds = 7;

        // Fetch returns for the selected date from selected shop
        $products = recevingModel::where('account', $shopIds)
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

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate', 'today');


            return view('main-return', $data);

    }
    

    /**
     * Show page to view all returns
     */
    public function viewReturns(Request $req)
    {
        $user = Auth::user();
        $statusFilter = $req->input('status', 'all');
        $fromDate = $req->input('from_date', date('Y-m-d'));
        $toDate = $req->input('to_date', date('Y-m-d'));
        $shopFilter = $req->input('shop', getCurrentShopId());
        $shops = getUserAccounts();
        // Get user's assigned accounts
        $userAccounts = $shops;

        // Build base query — fetch only returns
        $query = recevingModel::where('is_return', 1);

        if (!empty($shopFilter)) {
                $query->where('account', $shopFilter);
            }


        // Apply date range filter
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }


        $products = $query->orderBy('id', 'desc')->get();

        $productIds = $products->pluck('productId')->unique();
        $productMap = productsModel::whereIn('product_id', $productIds)
            ->pluck('name01', 'product_id');

        foreach ($products as $item) {
            $item->productName = $productMap[$item->productId] ?? 'Unknown';
        }


        $data = compact('products', 'statusFilter', 'fromDate', 'toDate', 'shopFilter', 'shops');


            return view('viewReturns', $data);
   
    }
    public function mainReturns(Request $req)
    {
        $user = Auth::user();
        $statusFilter = $req->input('status', 'all');
        $fromDate = $req->input('from_date', date('Y-m-d'));
        $toDate = $req->input('to_date', date('Y-m-d'));
        $shopFilter = $req->input('shop', getCurrentShopId());
        $shops = getUserAccounts();
        // Get user's assigned accounts
        $userAccounts = $shops;

        // Build base query — fetch only returns
        $query = recevingModel::where('is_return', 1);

        if (!empty($shopFilter)) {
                $query->where('account', 7);
            }


        // Apply date range filter
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }


        $products = $query->orderBy('id', 'desc')->get();

        $productIds = $products->pluck('productId')->unique();
        $productMap = productsModel::whereIn('product_id', $productIds)
            ->pluck('name01', 'product_id');

        foreach ($products as $item) {
            $item->productName = $productMap[$item->productId] ?? 'Unknown';
        }


        $data = compact('products', 'statusFilter', 'fromDate', 'toDate', 'shops');


            return view('main-returns', $data);
   
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
    $shops = getUserAccounts();
    // Get user's assigned accounts
    $userAccounts = array_column($shops, 'id');

    // ── 1. Fetch all item requests (requested quantities) ──
    $requestQuery = itemRequestModel::query();
    if (!empty($shopFilter)) {
        $requestQuery->where('account', $shopFilter);
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

    if (!empty($shopFilter)) {
        $receivingQuery->where('account', $shopFilter);
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

    // ── 3. Fetch returns (return quantities) ──
    $returnQuery = recevingModel::where('is_return', 1);

    if (!empty($shopFilter)) {
        $returnQuery->where('account', $shopFilter);
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

    // ── 4. Fetch approved quantities from receivings ──
    $approvedQuery = recevingModel::where('is_return', '!=', 1)
        ->where('status', 'Approved');

    if (!empty($shopFilter)) {
        $approvedQuery->where('account', $shopFilter);
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

    // ── 5. Fetch returned approved items (is_return = 1 AND status = 'Returned') ──
    $returnedApprovedQuery = recevingModel::where('is_return', 1)
        ->where('status', 'Returned');

    if (!empty($shopFilter)) {
        $returnedApprovedQuery->where('account', $shopFilter);
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

    // ── 6. Fetch SOLD items from sales table (exclude returns) ──
    $soldQuery = salsModel::
        select('productId', DB::raw('SUM(pQuantity) as total_sold'))
        ->where('status', '!=', 'Return')
        ->groupBy('productId');

if (!empty($shopFilter)) {
    $soldQuery->where('account', $shopFilter);
}

if (!empty($fromDate) && !empty($toDate)) {
    $soldQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
} elseif (!empty($selectedDate)) {
    $soldQuery->whereDate('created_at', $selectedDate);
}

$allSold = $soldQuery->get();

    // Convert to map
    $soldMap = [];
    $totalSoldQty = 0;
    foreach ($allSold as $sold) {
        $pid = $sold->productId;
        $soldMap[$pid] = (int)($sold->total_sold ?? 0);
        $totalSoldQty += (int)($sold->total_sold ?? 0);
    }

    // ── 7. Fetch customer returns ──
    $allProductIds = array_unique(array_merge(
        array_keys($requestedMap),
        array_keys($receivedMap),
        array_keys($approvedMap),
        array_keys($returnMap),
        array_keys($soldMap),
    ));

    // Fetch product names
    $productNames = [];
    if (!empty($allProductIds)) {
        $productNames = productsModel::whereIn('product_id', $allProductIds)
            ->pluck('name01', 'product_id')
            ->toArray();
    }

    // Supplier comes from the RECEIVING record (the batch/group)
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
    $totalSold = 0;
    $totalCustomerReturned = 0;
    $totalRemaining = 0;

    foreach ($allProductIds as $pid) {
        $reqQty           = $requestedMap[$pid]        ?? 0;
        $recQty           = $receivedMap[$pid]         ?? 0;
        $retQty           = $returnMap[$pid]           ?? 0;        // Returns to supplier
        $appQty           = $approvedMap[$pid]         ?? 0;
        $retAppQty        = $returnedApprovedMap[$pid] ?? 0;
        $soldQty          = $soldMap[$pid]             ?? 0;        // Items sold to customers
        $customerRetQty   = $customerReturnMap[$pid]   ?? 0;        // Items returned by customers
        
        // Calculate NET received = approved quantity - returns approved
        $netReceived = $appQty;
        
        // Calculate NET sold = sold quantity - customer returns
        $netSold = $soldQty - $customerRetQty;
        
        // Calculate REMAINING STOCK = net received - net sold - returns to supplier
        $remaining = $netReceived - $netSold - $retAppQty;
        
        // Difference for requested vs received (original logic)
        $diff = ($reqQty - $recQty) - ($retQty - $retAppQty);

        // Total price = approved qty * price from item_requests
        $price = 0;
        foreach ($allRequests as $r) {
            if ($r->productId === $pid) {
                $price = (float)($r->price ?? 0);
                break;
            }
        }
        $totalPrice = $appQty * $price;

        // Resolve supplier display name
        $supplierId   = $receivingSupplierMap[$pid] ?? null;
        $supplierName = accountModel::where('id', $supplierId)->value('name') ?? 'Unknown Supplier';

        $reportRows[] = [
            'productId'          => $pid,
            'productName'        => $productNames[$pid] ?? 'Unknown',
            'supplierName'       => $supplierName,
            'requestedQty'       => $reqQty,
            'receivedQty'        => $recQty,
            'approvedQty'        => $appQty,
            'returnedApprovedQty'=> $retAppQty,
            'returnQty'          => $retQty,
            'soldQty'            => $soldQty,
            'customerReturnQty'  => $customerRetQty,
            'netSold'            => $netSold,
            'netReceived'        => $netReceived,
            'remainingStock'     => $remaining,
            'difference'         => $diff,
            'totalPrice'         => $totalPrice,
        ];

        $totalRequested        += $reqQty;
        $totalReceived         += $recQty;
        $totalReturned         += $retQty;
        $totalApproved         += $appQty;
        $totalReturnedApproved += $retAppQty;
        $totalSold             += $soldQty;
        $totalCustomerReturned += $customerRetQty;
        $totalRemaining        += $remaining;
    }

    $data = compact(
        'reportRows', 'selectedDate', 'fromDate', 'toDate', 'shopFilter', 'shops',
        'totalRequested', 'totalReceived', 'totalReturned', 'totalApproved', 
        'totalReturnedApproved', 'totalSold', 'totalCustomerReturned', 'totalRemaining'
    );

    return view('receivingReport', $data);
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
                $product->served_by = $served ?? Auth::user()->name;
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
                    $create->description = 'New Stock Added By ' . Auth::user()->name;
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
                $returnRec->served_by = $served ?? Auth::user()->name;
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
                    $create->description = 'Return requested: ' . $quantity . ' items. Reason: ' . $reason . ' by ' . Auth::user()->name . '. Awaiting admin approval.';
                    $create->save();
                }
            }

            if ($allSaved) {
                $message = 'Return request submitted successfully! Awaiting admin approval before stock is deducted.';
                if ($req->expectsJson()) {
                    return response()->json(['success' => true, 'message' => $message]);
                }
                return redirect()->back()->with('success', $message);
            } else {
                $message = 'Error saving some return requests. Please try again.';
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }
        }

        if ($req->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Invalid request.'], 422);
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

            $MainStore = productsModel::where('product_id', $productId)
            ->where('account', 7)
            ->first();

            if ($MainStore) {
                $newStockQty = max(0, ((int) $MainStore->quantity) + $quantity);
                $MainStore->quantity = $newStockQty;
                $MainStore->save();
            }
        }

        // Mark return as approved/returned
        $returnRec->status = 'Returned';
        $returnRec->save();

        // Log
        $create = new logModal();
        $create->title = 'Return Approved';
        $create->description = 'Return approved: ' . $quantity . ' items of product ' . $productId . ' by ' . Auth::user()->name . '. Stock deducted.';
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
        $create->description = 'Return rejected: ' . $quantity . ' items of product ' . $productId . ' by ' . Auth::user()->name . '. No stock deducted.';
        $create->save();

        return redirect()->back()->with('success', 'Return request rejected. No stock was deducted.');
    }

    /**
     * Approve selected receiving records
     */
    public function approveSelectedReceivings(Request $req)
    {
        $receiving_id = $req->input('product_ids', []);
        $shopFilter = $req->input('shop', '');
        $user = Auth::user();

        // Use shop filter if provided, otherwise fall back to session account
        $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

        if (empty($receiving_id)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        $approvedCount = 0;
        $failedCount = 0;

        foreach ($receiving_id as $productId) {
            // Find the pending receiving record for the correct shop
            $receiving = recevingModel::where('account', $accountId)
                ->where('id', $productId)
                ->whereNotIn('status', ['Approved', 'Returned'])
                ->where('is_return', '!=', 1)
                ->orderBy('id', 'desc')
                ->first();

            if (!$receiving) {
                $failedCount++;
                continue;
            }

            // Get the product from the correct shop
            $product = productsModel::where('product_id', $receiving->productId)
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
            $restock->productId = $receiving->productId;
            $restock->quantity = $receiving->quantity;
            $restock->bPrice = $receiving->price;
            $restock->sPrice = $receiving->sellingPrice;
            $restock->tBprice = $receiving->quantity * $receiving->price;
            $restock->account = $accountId;
            $restock->save();

            // Log
            $create = new logModal();
            $create->title = 'Stock Approved (Selected)';
            $create->description = 'Stock approved for ' . ($productInfo->name01 ?? 'Unknown') . ' by ' . Auth::user()->name;
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
            $create->description = 'Stock approved for ' . ($productInfo->name01 ?? 'Unknown') . ' by ' . Auth::user()->name;
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
            $create->description = 'Stock approved for ' . ($productInfo->name01 ?? 'Unknown') . ' by ' . Auth::user()->name;
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
        $receiving_id = $req->input('product_ids', []);
        $shopFilter = $req->input('shop', '');
        $user = Auth::user();


        // Use shop filter if provided, otherwise fall back to session account
        $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

        if (empty($receiving_id)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        $undoCount = 0;
        $failedCount = 0;

        foreach ($receiving_id as $productId) {
           
            // Find the pending receiving record for the correct shop
$receivingQuery = recevingModel::query();
    $receivingQuery->where('id', $productId);

$receiving = $receivingQuery->first(); // Assign the result here

if (!$receiving) {
    $failedCount++;
    continue;
}

// Get the product from the correct shop
$product = productsModel::where('product_id', $receiving->productId)
    ->where('account', $accountId)
    ->first();

if (!$product) {
    $failedCount++;
    continue;
}

// Update product quantity and details
$product->quantity -= $receiving->quantity;
$product->save();

// Update receiving status
$receiving->status = 'Submitted';
$receiving->save();

            
            // Log
            $create = new logModal();
            $create->title = 'Stock Undo (Selected)';
            $create->description = 'Stock Undone for ' . ($productInfo->name01 ?? 'Unknown') . ' by ' . Auth::user()->name;
            $create->save();

            $undoCount++;
        }

        if ($undoCount > 0) {
            return redirect()->back()->with('success', "{$undoCount} item(s) Undone successfully!" . ($failedCount > 0 ? " {$failedCount} failed." : ""));
        } else {
            return redirect()->back()->with('error', 'No items could be Undone.');
        }
        }


    /**
     * Delete selected receiving records (non-approved only)
     */
    public function deleteSelectedReceivings(Request $req)
    {
        $receiving_id = $req->input('product_ids', []);
        $shopFilter  = $req->input('shop', '');
        $user = Auth::user();

        $accountId = !empty($shopFilter) ? $shopFilter : getCurrentShopId();

        if (empty($receiving_id)) {
            return redirect()->back()->with('error', 'No items selected for deletion.');
        }

        $deletedCount = 0;
        $failedCount = 0;

            try {
        DB::beginTransaction(); // Ensure your transaction starts before the loop

        foreach ($receiving_id as $productId) {
            // Build query for pending (non-approved) receiving records
            $query = recevingModel::where('id', $productId)
                ->where('is_return', '!=', 1)
                ->whereNotIn('status', ['Approved', 'Returned']);
            $receivings = $query->first();

            $products = productsModel::where('product_id', $receivings->productId)
                ->where('account', 7)
                ->first();
            $products->quantity += $receivings->quantity;
            $products->save();
            if($products) {
                $request = itemRequestModel::where('requestName', $receivings->receivingName)
                    ->where('account', $receivings->account)
                    ->first();
                if($request) {
                $request->status = 'Submitted';
                $request->save();
                }
            // Delete all matching receiving records for this product
            $deletedForProduct = $query->delete();
            }
            if ($deletedForProduct > 0) {
                $deletedCount += $deletedForProduct;
            } else {
                $failedCount++;
            }
        }

        DB::commit(); // Commit changes if the loop completes successfully

        return redirect()->back()->with('success',
            'Quantities cleared successfully for ' . $deletedCount . ' products'); // Fixed variable name from $clearedCount to $deletedCount

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Bulk Clear Quantity Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while clearing quantities');
    }

    }

    public function syncPricesToShops(Request $request)
    {
        $user = $request->user();

        $accountIds = $request->input('account_ids', []);
        $productIds = $request->input('product_ids', []);

        if (empty($accountIds) || empty($productIds)) {
            return redirect()->back()->with('error', 'Invalid request: missing accounts or products');
        }

        $validAccounts = array_column(getuserAccounts(), 'id');
        $accountIds = array_values(array_intersect($accountIds, $validAccounts));

        if (empty($accountIds)) {
            return redirect()->back()->with('error', 'No valid accounts selected');
        }

        $mainStoreId = 7;
        $mainStoreProducts = productsModel::where('account', $mainStoreId)
            ->whereIn('product_id', $productIds)
            ->get(['product_id', 'bPrice', 'sPrice', 'wholesale', 'discount'])
            ->keyBy('product_id');

        if ($mainStoreProducts->isEmpty()) {
            return redirect()->back()->with('error', 'No matching products found in the Main Store to sync from');
        }

        $syncCount = 0;

        DB::beginTransaction();
        try {
            foreach ($productIds as $productId) {
                $mainProduct = $mainStoreProducts->get($productId);
                if (!$mainProduct) {
                    continue;
                }

                $updated = productsModel::where('product_id', $productId)
                    ->whereIn('account', $accountIds)
                    ->update([
                        'bPrice'    => $mainProduct->bPrice,
                        'sPrice'    => $mainProduct->sPrice,
                        'wholesale' => $mainProduct->wholesale,
                        'discount'  => $mainProduct->discount,
                    ]);

                $syncCount += $updated;
            }

            DB::commit();

            $log = new logModal();
            $log->title = 'Bulk Sync Prices';
            $log->description = 'Synced prices for ' . $syncCount . ' products across ' . count($accountIds) . ' accounts by ' . Auth::user()->name;
            $log->save();

            return redirect()->back()->with('success',
                'Prices synced successfully for ' . $syncCount . ' products');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk Sync Prices Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while syncing prices');
        }
    }


    /**
     * Get offers for a product (API endpoint)
     */
    public function getOffers($productId)
    {
        $offers = Offer::where('account', getCurrentShopId())
            ->where('is_active', true)
            ->where(function ($q) use ($productId) {
                $q->where('product_id', $productId)
                  ->orWhere('offer_product_id', $productId)
                  ->orWhereHas('requiredItems', function ($q2) use ($productId) {
                      $q2->where('product_id', $productId);
                  });
            })
            ->with(['requiredItems.product', 'offeredProduct:id,product_id,name01'])
            ->get()
            ->map(function ($offer) {
                $data = [
                    'id' => $offer->id,
                    'offer_product_id' => $offer->offer_product_id,
                    'offer_product_name' => $offer->offeredProduct->name01 ?? 'Unknown',
                    'offer_quantity' => $offer->offer_quantity,
                    'requiredItems' => $offer->requiredItems->map(function ($reqItem) {
                        return [
                            'product_id' => $reqItem->product_id,
                            'product_name' => $reqItem->product->name01 ?? 'Unknown',
                            'required_quantity' => $reqItem->required_quantity,
                        ];
                    })->toArray(),
                ];
                if ($offer->product_id) {
                    $data['product_id'] = $offer->product_id;
                    $primary = $offer->requiredItems->firstWhere('product_id', $offer->product_id);
                    $data['required_quantity'] = $primary ? $primary->required_quantity : null;
                }
                return $data;
            });

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
        $accounts = getUserAccounts();
        $items = Offer::where('account', getCurrentShopId())
            ->where('is_active', true)
            ->get();

        // Get product names
        $productIds = $items->pluck('product_id')->merge($items->pluck('offer_product_id'))->unique();
        $products = productsModel::whereIn('product_id', $productIds)
            ->pluck('name01', 'product_id');

        $data = compact('items', 'products', 'accounts');

        $user = Auth::user();

            return view('offers', $data);

    }
public function offers(Request $req)
   {
    $accounts = getUserAccounts();
    $shop = $req->input('shop');

       $offers = Offer::query();
       if(!empty($shop))
        {
            session([
                'selected_shop_id' => $shop
            ]);
        $offers->where('account', $shop);
        } else {
            $offers->where('account', getCurrentShopId());
        }
        $items = $offers->with('requiredItems')->get();

       // Get product names from required items and offered product
       $productIds = $items->pluck('offer_product_id')->unique();
       foreach ($items as $offer) {
           foreach ($offer->requiredItems as $reqItem) {
               $productIds->push($reqItem->product_id);
           }
       }
       $productIds = $productIds->unique();
       $products = productsModel::whereIn('product_id', $productIds)
           ->pluck('name01', 'product_id');

       $data = compact('items', 'products','accounts');

       $user = Auth::user();

           return view('offers', $data);

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
            $create->description = 'Offer deleted by ' . Auth::user()->name;
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
        $account = getCurrentShopId();
        $offer = Offer::where('account', $account)
            ->where('is_active', true)
            ->whereHas('requiredItems', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->with(['requiredItems', 'offeredProduct'])
            ->first();

        if (!$offer) {
            return response()->json(['has_offer' => false]);
        }

        $requiredItems = $offer->requiredItems;
        if ($requiredItems->count() === 1) {
            $reqItem = $requiredItems->first();
            if ($quantity >= $reqItem->required_quantity) {
                $offerApplies = floor($quantity / $reqItem->required_quantity);
                $offerQuantity = $offerApplies * $offer->offer_quantity;

                return response()->json([
                    'has_offer' => true,
                    'offer' => [
                        'required_quantity' => $reqItem->required_quantity,
                        'offer_quantity' => $offerQuantity,
                        'offer_product_id' => $offer->offer_product_id,
                        'offer_product_name' => $offer->offeredProduct->name01 ?? 'Unknown',
                        'offer_product_stock' => $offer->offeredProduct->quantity ?? 0,
                    ]
                ]);
            }
        }

        return response()->json(['has_offer' => false]);
    }

    /**
     * Show offered products report with date and shop filtering
     */
    public function offeredProductsReport(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $shopFilter = $request->input('shop');

        if (!empty($shopFilter)) {
            session(['selected_shop_id' => $shopFilter]);
        }

        $accountIds = !empty($shopFilter) ? [$shopFilter] : array_column(getuserAccounts(), 'id');

        $offeredProducts = DB::table('sales')
            ->join('products', 'sales.productId', '=', 'products.product_id')
            ->whereIn('sales.account', $accountIds)
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

        $products = $offeredProducts->pluck('productName', 'productId');

        $totalOfferedItems = $offeredProducts->sum('total_quantity');
        $totalOrdersWithOffers = $offeredProducts->sum('order_count');

        $activeOffers = Offer::whereIn('account', $accountIds)
            ->where('is_active', true)
            ->with('requiredItems')
            ->get();

        $totalActiveOffers = $activeOffers->count();
        $totalProductsWithOffers = $activeOffers->pluck('requiredItems.*.product_id')->flatten()->unique()->count();

        $soldOffers = DB::table('sales')
            ->join('products', 'sales.productId', '=', 'products.product_id')
            ->whereIn('sales.account', $accountIds)
            ->where('offered_items', 1)
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                'sales.*',
                'products.name01 as productName',
                'products.name02 as productBrand'
            )
            ->orderByDesc('sales.created_at')
            ->get();

        $shops = getuserAccounts();

        $data = compact(
            'offeredProducts', 'products', 'startDate', 'endDate',
            'totalOfferedItems', 'totalOrdersWithOffers', 'shops', 'shopFilter',
            'activeOffers', 'totalActiveOffers', 'totalProductsWithOffers',
            'soldOffers'
        );

        return view('offeredProductsReport', $data);
    }

    public function fetchActiveOffers(Request $request)
    {
        $shopFilter = $request->input('shop');
        $accountIds = !empty($shopFilter) ? [$shopFilter] : array_column(getuserAccounts(), 'id');

        $offers = Offer::whereIn('account', $accountIds)
            ->where('is_active', true)
            ->with(['requiredItems.product', 'offeredProduct'])
            ->get()
            ->map(function ($offer) {
                return [
                    'id' => $offer->id,
                    'account' => $offer->account,
                    'offer_product_id' => $offer->offer_product_id,
                    'offer_product_name' => $offer->offeredProduct->name01 ?? 'Unknown',
                    'offer_quantity' => $offer->offer_quantity,
                    'is_active' => $offer->is_active,
                    'required_items' => $offer->requiredItems->map(function ($reqItem) {
                        $product = $reqItem->product;
                        return [
                            'product_id' => $reqItem->product_id,
                            'product_name' => $product->name01 ?? 'Unknown',
                            'required_quantity' => $reqItem->required_quantity,
                        ];
                    })->toArray(),
                ];
            });

        return response()->json(['offers' => $offers]);
    }

    public function fetchSoldOffers(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $shopFilter = $request->input('shop');
        $accountIds = !empty($shopFilter) ? [$shopFilter] : array_column(getuserAccounts(), 'id');

        $sales = DB::table('sales')
            ->join('products', 'sales.productId', '=', 'products.product_id')
            ->whereIn('sales.account', $accountIds)
            ->where('offered_items', 1)
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                'sales.*',
                'products.name01 as productName',
                'products.name02 as productBrand'
            )
            ->orderByDesc('sales.created_at')
            ->get();

        return response()->json(['sales' => $sales]);
    }

    public function saveOffer(Request $request)
    {
        $isActive = $request->input('is_active') === 'on';
        $account = $request->input('account', getCurrentShopId());

        $request->validate([
            'offer_product_id' => 'required|string',
            'offer_quantity' => 'required|integer|min:1',
        ]);

        $requiredItems = $request->input('required_items', []);
        if (empty($requiredItems) && $request->filled('product_id')) {
            $requiredItems = [[
                'product_id' => $request->input('product_id'),
                'required_quantity' => $request->input('required_quantity', 1),
            ]];
        }

        $offer = Offer::create([
            'account' => $account,
            'product_id' => $requiredItems[0]['product_id'] ?? null,
            'offer_product_id' => $request->input('offer_product_id'),
            'offer_quantity' => $request->input('offer_quantity'),
            'is_active' => $isActive,
        ]);

        foreach ($requiredItems as $item) {
            if (!empty($item['product_id']) && !empty($item['required_quantity'])) {
                OfferItem::updateOrCreate(
                    ['offer_id' => $offer->id, 'product_id' => $item['product_id']],
                    ['required_quantity' => $item['required_quantity'], 'account' => $account]
                );
            }
        }

        $create = new logModal();
        $create->title = 'Offer Created';
        $create->description = 'Offer created by ' . Auth::user()->name;
        $create->save();

        if ($request->expectsJson()) {
            $offer->load('requiredItems.product', 'offeredProduct');
            return response()->json([
                'success' => true,
                'message' => 'Offer saved successfully!',
                'offer' => $offer
            ]);
        }

        return redirect()->back()->with('success', 'Offer saved successfully!');
    }

    public function updateOffer(Request $request, $id)
    {
        $offer = Offer::where('account', getCurrentShopId())->findOrFail($id);

        $rawActive = $request->input('is_active', $offer->is_active);
        if (is_string($rawActive)) {
            $isActive = in_array(strtolower($rawActive), ['1', 'true', 'on', 'yes']);
        } else {
            $isActive = (bool) $rawActive;
        }
        $account = $request->input('account', $offer->account);

        $updateData = [
            'account' => $account,
            'is_active' => $isActive,
        ];

        if ($request->has('offer_product_id')) {
            $updateData['offer_product_id'] = $request->input('offer_product_id');
        }
        if ($request->has('offer_quantity')) {
            $updateData['offer_quantity'] = $request->input('offer_quantity');
        }

        $offer->update($updateData);

        $requiredItems = $request->input('required_items', []);
        if (!empty($requiredItems)) {
            $existingIds = [];
            foreach ($requiredItems as $item) {
                if (!empty($item['product_id']) && !empty($item['required_quantity'])) {
                    $offerItem = OfferItem::updateOrCreate(
                        ['offer_id' => $offer->id, 'product_id' => $item['product_id']],
                        ['required_quantity' => $item['required_quantity'], 'account' => $account]
                    );
                    $existingIds[] = $offerItem->id;
                }
            }

            OfferItem::where('offer_id', $offer->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            $firstItem = $requiredItems[0] ?? null;
            if ($firstItem && !empty($firstItem['product_id'])) {
                $offer->update(['product_id' => $firstItem['product_id']]);
            }
        } elseif ($request->has('product_id') && $request->has('required_quantity')) {
            OfferItem::updateOrCreate(
                ['offer_id' => $offer->id, 'product_id' => $request->input('product_id')],
                ['required_quantity' => $request->input('required_quantity'), 'account' => $account]
            );
            $offer->update(['product_id' => $request->input('product_id')]);
        }

        $product = productsModel::where('account', $account)->where('product_id', $offer->offer_product_id)->first();

        $create = new logModal();
        $create->title = 'Offer Updated';
        $create->description = 'Offer updated by ' . Auth::user()->name;
        $create->save();

        if ($request->expectsJson()) {
            $offer->load('requiredItems.product', 'offeredProduct');
            return response()->json([
                'success' => true,
                'message' => 'Offer updated successfully!',
                'offer' => $offer
            ]);
        }

        return redirect()->back()->with('success', 'Offer updated successfully!');
    }

    public function removeOffer(Request $request)
    {
        $offerId = $request->input('offer_id');

        $offer = Offer::where('account', getCurrentShopId())
            ->where('id', $offerId)
            ->first();

        if ($offer) {
            $offer->delete();

            $create = new logModal();
            $create->title = 'Offer Deleted';
            $create->description = 'Offer deleted by ' . Auth::user()->name;
            $create->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Offer deleted successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Offer deleted successfully!');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Offer not found'
            ], 404);
        }

        return redirect()->back()->with('error', 'Offer not found');
    }

    public function removeSoldOffer(Request $request)
    {
        $salesId = $request->input('sales_id');

        $sale = salsModel::where('account', getCurrentShopId())
            ->where('sales_id', $salesId)
            ->where('offered_items', 1)
            ->first();

        if ($sale) {
            $restoredQty = (int) ($sale->pQuantity ?? 0);

            $product = productsModel::where('account', getCurrentShopId())
                ->where('productId', $sale->productId)
                ->first();

            if ($product) {
                $product->quantity += $restoredQty;
                $product->save();
            }

            $sale->delete();

            $create = new logModal();
            $create->title = 'Sold Offer Removed';
            $create->description = 'Sold offer record removed by ' . Auth::user()->name;
            $create->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sold offer removed successfully!',
                    'restoredQty' => $restoredQty
                ]);
            }

            return redirect()->back()->with('success', 'Sold offer removed successfully!');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Sold offer record not found'
            ], 404);
        }

        return redirect()->back()->with('error', 'Sold offer record not found');
    }

    public function updateSoldOffer(Request $request, $salesId)
    {
        $sale = salsModel::where('account', getCurrentShopId())
            ->where('sales_id', $salesId)
            ->where('offered_items', 1)
            ->firstOrFail();

        $request->validate([
            'pQuantity' => 'required|integer|min:1',
        ]);

        $oldQty = (int) $sale->pQuantity;
        $newQty = (int) $request->input('pQuantity');
        $diff = $newQty - $oldQty;

        $product = productsModel::where('account', getCurrentShopId())
            ->where('productId', $sale->productId)
            ->first();

        if ($product) {
            if ($diff > 0) {
                if ($product->quantity < $diff) {
                    return redirect()->back()->with('error', 'Insufficient stock to increase quantity');
                }
                $product->quantity -= $diff;
            } else {
                $product->quantity += abs($diff);
            }
            $product->save();
        }

        $sale->pQuantity = $newQty;
        $sale->totalPrice = $newQty * $sale->productPrice;
        $sale->save();

        $create = new logModal();
        $create->title = 'Sold Offer Updated';
        $create->description = 'Sold offer quantity updated by ' . Auth::user()->name;
        $create->save();

        return redirect()->back()->with('success', 'Sold offer updated successfully!');
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
        $receiving->account = 7;
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
        $log->description = $product->name01 . ' returned to Main Store by ' . Auth::user()->name;
        $log->save();
        
        return redirect()->back()->with('success', 'Item successfully sent back to Main Store');
    }

    public function bulkClearQuantity(Request $request)
    {
        $user = $request->user();

        $accountIds = $request->input('account_ids', []);
        $productIds = $request->input('product_ids', []);

        if (empty($accountIds) || empty($productIds)) {
            return redirect()->back()->with('error', 'Invalid request: missing accounts or products');
        }

        $validAccounts = array_column(getuserAccounts(), 'id');
        $accountIds = array_values(array_intersect($accountIds, $validAccounts));

        if (empty($accountIds)) {
            return redirect()->back()->with('error', 'No valid accounts selected');
        }

        $clearedCount = 0;

        DB::beginTransaction();

        try {
            foreach ($productIds as $productId) {
                $products = productsModel::where('product_id', $productId)
                    ->whereIn('account', $accountIds)
                    ->get();

                foreach ($products as $product) {
                    $product->quantity = 0;
                    $product->save();
                    $clearedCount++;
                }
            }

            DB::commit();

            $log = new logModal();
            $log->title = 'Bulk Clear Quantity';
            $log->description = 'Cleared quantity for ' . $clearedCount . ' products on ' . count($accountIds) . ' accounts by ' . Auth::user()->name;
            $log->save();

            return redirect()->back()->with('success', 'Quantities cleared successfully for ' . $clearedCount . ' products');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk Clear Quantity Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while clearing quantities');
        }
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
        $shops = getUserAccounts();
        // Get user's assigned accounts
        $userAccounts = $shops;

        // Build base account filter
        if(!empty($accountFilter)) {
            session([
                'selected_shop_id' => $accountFilter
            ]);
        }
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

        $mainStoreRec = recevingModel::where('is_return', '!=', 1);
        if (!empty($accountFilter)) {
            $mainStoreRec->where('supplier', 7);
        }
        if (!empty($fromDate) && !empty($toDate)) {
            $mainStoreRec->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } elseif (!empty($selectedDate)) {
            $mainStoreRec->whereDate('created_at', $selectedDate);
        }
        $mainReceivings = $mainStoreRec->get();

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

        $mainreceivedMap = [];
        foreach ($mainReceivings as $mainrec) {
            $pid = $mainrec->productId;
            if (!isset($mainreceivedMap[$pid])) {
                $mainreceivedMap[$pid] = 0;
            }
            $mainreceivedMap[$pid] += (int)($mainrec->quantity ?? 0);
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
            $soldMap[$pid] += (int)($sale->pQuantity ?? 0);
            $soldPriceMap[$pid] += (float)($sale->totalPrice * $sale->pQuantity);
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
            $mainRecQty = $mainreceivedMap[$pid] ?? 0;
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
                'mainQty'        => $mainRecQty,
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


        $data = compact(
            'reportRows', 'selectedDate', 'fromDate', 'toDate', 'accountFilter', 'shops',
            'totalReceived', 'totalReturned', 'totalSold',
            'totalReceivedPrice', 'totalSoldPrice',
            'totalRemainingQty', 'totalRemainingValue'
        );

            return view('itemsReport', $data);
   
    }

    public function mostSoldProducts(Request $req)
    {
        $user = Auth::user();
        $fromDate = $req->input('from_date');
        $toDate = $req->input('to_date');
        $accountFilter = $req->input('shop', '');
        $sortBy = $req->input('sort_by', 'qty');

        $shops = getUserAccounts();
        $accountIds = array_column($shops, 'id');

        if (!empty($accountFilter)) {
            session(['selected_shop_id' => $accountFilter]);
            $queryAccountIds = [$accountFilter];
        } else {
            $queryAccountIds = $accountIds;
        }

        $dateFilter = [];
        if (!empty($fromDate) && !empty($toDate)) {
            $dateFilter = [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'];
        }

        $query = salsModel::query()
            ->whereIn('account', $queryAccountIds)
            ->where('offered_items', '!=', 1)
            ->where('status', '!=', 'Return')
            ->where(function($q) {
                $q->where('salesName', '!=', '')->orWhereNull('salesName');
            })
            ->select('productId')
            ->selectRaw('SUM(pQuantity) as total_qty')
            ->selectRaw('SUM(totalPrice) as total_price')
            ->groupBy('productId');

        if (!empty($dateFilter)) {
            $query->whereBetween('created_at', $dateFilter);
        }

        if ($sortBy == 'price') {
            $query->orderByDesc('total_price')->orderByDesc('total_qty');
        } else {
            $query->orderByDesc('total_qty')->orderByDesc('total_price');
        }

        $salesData = $query->get();

        $productIds = $salesData->pluck('productId')->toArray();
        $products = productsModel::whereIn('product_id', $productIds)->get();

        $productMap = [];
        foreach ($products as $p) {
            $productMap[$p->product_id] = $p;
        }

        $reportRows = [];
        $totalQty = 0;
        $totalPrice = 0;
        $rank = 1;

        foreach ($salesData as $sale) {
            $pid = $sale->productId;
            $product = $productMap[$pid] ?? null;
            $productName = $product ? ($product->name01 ?? 'Unknown') : 'Unknown (deleted)';
            $qty = $sale->total_qty;
            $price = $sale->total_price;

            $totalQty += $qty;
            $totalPrice += $price;

            $reportRows[] = [
                'rank' => $rank++,
                'productId' => $pid,
                'productName' => $productName,
                'totalQty' => $qty,
                'totalPrice' => $price,
                'avgPrice' => $qty > 0 ? $price / $qty : 0,
                'percentage' => 0,
            ];
        }

        $grandTotalQty = $totalQty;
        foreach ($reportRows as &$row) {
            $row['percentage'] = $grandTotalQty > 0 ? ($row['totalQty'] / $grandTotalQty) * 100 : 0;
        }

        // Paginate report rows
        $perPage = 100;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentPage = max(1, (int) $currentPage);
        $totalItems = count($reportRows);
        $lastPage = max(1, (int) ceil($totalItems / $perPage));
        $currentPage = min($currentPage, $lastPage);
        $offset = ($currentPage - 1) * $perPage;
        $reportRows = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($reportRows, $offset, $perPage),
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $data = compact(
            'reportRows', 'totalQty', 'totalPrice', 'fromDate', 'toDate', 'accountFilter', 'shops', 'sortBy'
        );

        return view('mostSoldProducts', $data);
    }
}
