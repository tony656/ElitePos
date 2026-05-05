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
use App\Models\accountModel;
use Illuminate\Support\Facades\Auth;
use function getSessionAccountId;

class productsController extends Controller
{
    public function index() {
        $vendor = null;
        $user = Auth::user();
        $perPage = request('per_page', 50);
        $search = request('search', '');
        $sort = request('sort', 'name_asc');
        $shopFilter = request('shop', getSessionAccountId()); // Default to session account
        
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
                $products->count(),
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            // Normal view with pagination and sorting
            // Apply sorting
            $productsQuery = $this->applySorting($baseQuery, $sort);
            
            $products = $productsQuery->paginate($perPage);
        }

        foreach($products as $product) {

        $vendor = vendorModal::where('account', getSessionAccountId())->where('id', '=', $product->supplier)->first();
        }
          // Calculate stats based on filtered products
          $accountIds = strtolower(trim($user->levelStatus)) === 'admin'
              ? ($shopFilter ? [$shopFilter] : accountModel::where('id', '!=', getSessionAccountId())->pluck('id')->toArray())
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
        $offers = Offer::where('account', getSessionAccountId())
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
            $currentAccount = getSessionAccountId();
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
            
            $currentAccount = getSessionAccountId();
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

        $report = stock::where('account', getSessionAccountId())->orderBy('id', 'desc')->get();

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
    $uploadType = $req->input('upload_type', 'manual');

    if ($uploadType === 'excel') {
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
    $productsModel->account = getSessionAccountId();
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
                stock::where('account', getSessionAccountId())
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
            $restock->account = getSessionAccountId();
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
 * Handle Excel/CSV bulk upload with optimized chunked processing
 */
private function handleExcelUpload(Request $req) {
    $req->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:20480', // Increased to 20MB
    ]);
    
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
        
        \Log::info("Starting bulk import: {$fileExt} file, size: " . round($fileSize / 1024, 2) . " KB");
        
        // Process in chunks based on file type
        if ($fileExt === 'csv') {
            $result = $this->processCsvInChunks($file);
        } else {
            $result = $this->processExcelInChunks($file);
        }
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        $logEntry = new logModal();
        $logEntry->title = 'Bulk Product Import';
        $logEntry->description = "Imported {$result['success']} products successfully. Failed: {$result['failed']}. Duration: {$duration}s By " . session('username');
        $logEntry->save();
        
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
    
    $handle = fopen($file->getPathname(), 'r');
    if (!$handle) {
        throw new \Exception('Could not open file');
    }
    
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
    
    while (($row = fgetcsv($handle)) !== false) {
        $rowNumber++;
        $rowLower = array_map('strtolower', array_map('trim', $row));
        
        // Check if this is header row
        $hasKnownHeader = false;
        foreach ($knownHeaders as $kh) {
            if (in_array($kh, $rowLower)) {
                $hasKnownHeader = true;
                break;
            }
        }
        
        if ($hasKnownHeader) {
            $headers = array_map('strtolower', array_map('trim', $row));
            $headerRowNumber = $rowNumber;
            \Log::info("Found CSV header row at line {$headerRowNumber}: " . json_encode($headers));
            break;
        }
        
        // After 20 rows without finding headers, use current row as headers
        if ($rowNumber >= 20 && $headers === null) {
            $headers = array_map('strtolower', array_map('trim', $row));
            $headerRowNumber = $rowNumber;
            \Log::info("Using fallback CSV header row at line {$headerRowNumber}: " . json_encode($headers));
            break;
        }
    }
    
    if ($headers === null) {
        fclose($handle);
        throw new \Exception('Could not find header row in CSV file');
    }
    
    // Process data in chunks
    $chunkData = [];
    $chunkCounter = 0;
    
    while (($row = fgetcsv($handle)) !== false) {
        // Skip empty rows
        if (empty(array_filter($row))) {
            continue;
        }
        
        // Map row to associative array
        $item = [];
        foreach ($headers as $index => $header) {
            $item[$header] = isset($row[$index]) ? trim($row[$index]) : '';
        }
        
        // Skip if no name
        $hasName = !empty($item['name']) || !empty($item['item name']) || !empty($item['product name']);
        if (!$hasName) {
            continue;
        }
        
        $chunkData[] = $item;
        $chunkCounter++;
        
        // Process chunk when it reaches chunk size
        if ($chunkCounter >= 100) {
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
        }
    }
    
    // Process remaining data
    if (!empty($chunkData)) {
        $result = $this->processDataChunk($chunkData);
        $successCount += $result['success'];
        $failedCount += $result['failed'];
        $errorMessages = array_merge($errorMessages, $result['errors']);
    }
    
    fclose($handle);
    
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
        
        // Process data in chunks using iterator
        $chunkData = [];
        $chunkSize = 50; // Smaller chunk size for Excel
        $processedRows = 0;
        
        for ($row = $headerRowIndex + 1; $row <= $highestRow; $row++) {
            $rowData = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $rowData[$headers[$col - 1]] = $cellValue !== null ? trim((string)$cellValue) : '';
            }
            
            // Skip empty rows
            if (empty(array_filter($rowData))) {
                continue;
            }
            
            // Skip if no name
            $hasName = !empty($rowData['name']) || !empty($rowData['item name']) || !empty($rowData['product name']);
            if (!$hasName) {
                continue;
            }
            
            $chunkData[] = $rowData;
            $processedRows++;
            
            // Process chunk
            if (count($chunkData) >= $chunkSize) {
                $result = $this->processDataChunk($chunkData);
                $successCount += $result['success'];
                $failedCount += $result['failed'];
                $errorMessages = array_merge($errorMessages, $result['errors']);
                
                $chunkData = [];
                
                // Allow garbage collection
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
                
                \Log::info("Processed {$processedRows} rows, {$successCount} successful, {$failedCount} failed");
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
    
    DB::beginTransaction();
    
    try {
        foreach ($chunkData as $index => $row) {
            try {
                $product = $this->createProductFromExcelRow($row);
                if ($product) {
                    $successCount++;
                } else {
                    $failedCount++;
                    $errorMessages[] = "Row: Failed to create product";
                }
            } catch (\Exception $e) {
                $failedCount++;
                $errorMessages[] = "Row: " . $e->getMessage();
                \Log::error('Row processing failed: ' . $e->getMessage());
            }
        }
        
        DB::commit();
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Chunk processing failed: ' . $e->getMessage());
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
        
        if (empty($itemName)) {
            throw new \Exception('Missing item name');
        }
        
        // Limit name length to avoid database issues
        $itemName = substr($itemName, 0, 255);
        
        // ── Product Code ───────────────────────────────────────
        $productCode = trim($row['product code'] ?? $row['code'] ?? '');
        
        // ── Quantity ───────────────────────────────────────────
        $quantity = (int) $this->parseNumber(
            $row['quantity'] ?? 
            $row['qty'] ?? 
            0
        );
        
        // ── Price (Selling Price) ─────────────────────────────
        $sPrice = $this->parsePrice(
            $row['selling price'] ??
            $row['sprice'] ??
            $row['price'] ??
            0
        );
        
        // Cost price
        $bPrice = $this->parsePrice(
            $row['cost price'] ??
            $row['buying price'] ??
            $row['bprice'] ??
            $sPrice
        );
        
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
        $firstVendor = vendorModal::where('account', getSessionAccountId())->first();
        $supplier = trim($row['supplier'] ?? $row['vendor'] ?? '');
        if (empty($supplier) && $firstVendor) {
            $supplier = $firstVendor->name;
        } elseif (empty($supplier)) {
            $supplier = 'Bulk Import';
        }
        
        // ── Generate UUID for product ID ──────────────────────
        $uuid = !empty($productCode) ? $productCode : (string) \Ramsey\Uuid\Uuid::uuid4();
        
        // Check if product already exists - use simpler query for speed
        $existingProduct = productsModel::where('product_id', $uuid)
            ->where('account', getSessionAccountId())
            ->first();
        
        if ($existingProduct) {
            // Update existing product - only update quantity
            $existingProduct->quantity += $quantity;
            $existingProduct->save();
            
            \Log::info("Updated product: {$itemName} | New quantity: {$existingProduct->quantity}");
        } else {
            // Create new product
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
            $product->account = getSessionAccountId();
            $product->save();
            
            \Log::info("Created product: {$itemName}");
        }
        
        // Create stock entry for new stock
        if ($quantity > 0) {
            $this->createStockEntryFast($uuid, $itemName, $name02, $quantity, $bPrice, $sPrice);
        }
        
        return true;
        
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
        $seq = stock::where('account', getSessionAccountId())
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
        $restock->account = getSessionAccountId();
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
    if ($value === null || $value === '') {
        return 0;
    }
    // Remove currency labels and thousand-separator commas
    $value = str_ireplace(['tsh', 'tzs', '$', '€', '£', ',', ' '], '', strval($value));
    $value = trim($value);
    return is_numeric($value) ? (float) $value : 0;
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
    } while (productsModel::where('code', $code)->where('account', getSessionAccountId())->exists());
 
    return $code;
}
 
// ============================================================
 
/**
 * Create a stock entry record
 */
private function createStockEntry($productId, $itemName, $brand, $quantity, $bPrice, $sPrice) {
    try {
        $seq = stock::where('account', getSessionAccountId())
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
        $restock->account  = getSessionAccountId();
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
        '• ID - Product identifier (leave blank to auto-generate)',
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
        $products = productsModel::where('account', getSessionAccountId())->where('product_id', '=', value: $product_id)->first();

       


        $vendor = vendorModal::where('account', getSessionAccountId())->where('id', '=', $products->supplier ?? '')->first();
         if(!$vendor) {
            $vendor = vendorModal::where('account', getSessionAccountId())->where('name', '=', $products->supplier ?? '')->first();
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
            $dlt = productsModel::where('account', getSessionAccountId())->where('product_id', $product_id)->first();

            if ($dlt) {
                $productName = $dlt->name01;
                $dlt->delete();

                // Delete associated stock records
                $dltStock = stock::where('account', getSessionAccountId())->where('productId', $product_id)->delete();

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
        $productsModel = productsModel::where('product_id', $product_id)->where('account', getSessionAccountId())->get();
    
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

                // If updating from main store, propagate price changes to all accounts
                if (getSessionAccountDisplayName() === 'Main Store' ) {
                    $allAccounts = accountModel::where('name', '!=', 'Main Store')->get();

                    foreach ($allAccounts as $account) {
                        $otherProduct = productsModel::where('name01', $name01)
                            ->where('account', $account->name)
                            ->first();

                        if ($otherProduct) {
                            $otherProduct->bPrice = $bPrice;
                            $otherProduct->sPrice = $sPrice;
                            $otherProduct->wholesale = $wholesale;
                            $otherProduct->save();

                            // Log the propagation
                            $propagateLog = new logModal();
                            $propagateLog->title = 'Price Propagation';
                            $propagateLog->description = 'Prices for product "' . $name01 . '" updated in account "' . $account->name . '" from Main Store by ' . session('username');
                            $propagateLog->save();
                        } else {
                            // Log if product not found in other account
                            $notFoundLog = new logModal();
                            $notFoundLog->title = 'Price Propagation Failed';
                            $notFoundLog->description = 'Product "' . $name01 . '" not found in account "' . $account->name . '" during price propagation from Main Store by ' . session('username');
                            $notFoundLog->save();
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

    public function newOrder(){
        $user = Auth::user();

         // Get active order - show both user's own orders and returned orders (any seller)
         $orders = ordersModel::where('account', getSessionAccountId())
    ->whereIn('status', ['Sell', 'Pending'])
    ->orderBy('id', 'desc')
    ->first();

     $Suspended = ordersModel::where('account', getSessionAccountId())
    ->where('served_by', session('username'))
    ->where('status', 'Suspended')
    ->select('cName',DB::raw('MAX(order_id) as order_id'), DB::raw('SUM(totalPrice) as total_price'))
    ->groupBy('cName')
    ->get();

        // Get cart items - show both own orders and returned orders (Pending status)
        $cart = ordersModel::where('account', getSessionAccountId())
        ->where('order_id',  $orders->order_id ?? '' )
        ->orderBy('id', 'desc')
        ->get();
        
        // Calculate totals based on the current cart items, not filtered by served_by
        // This allows editing past sales returned to orders
        $totalP = $cart->sum('totalPrice');
        $totalD = $cart->sum('discount');
        $totalDI = $cart->sum('discount_increase');
        $customers = customerModel::where('account', getSessionAccountId())->get();

        // Get all active offers for the current account
        $offers = Offer::where('account', getSessionAccountId())
            ->where('is_active', true)
            ->get();

        session(['totalP' => $totalP]);
        $data = compact(
        'cart','totalP','totalD','totalDI','customers','orders','Suspended','offers'
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
  if (!getSessionAccountId()) {
      return response()->json(['error' => 'Unauthorized', 'redirect' => '/login'], 401);
  }

  $query = trim($request->query('query'));

  if ($query === '') {
      return response()->json([]);
  }

  $products = productsModel::where('account', getSessionAccountId())
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
          'sPrice',
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
       if (!getSessionAccountId()) {
           return response()->json(['error' => 'Unauthorized', 'redirect' => '/login'], 401);
       }

       $query = trim($request->query('q'));

       $productsQuery = productsModel::where('account', getSessionAccountId())
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
    $purchases = stock::where('account', getSessionAccountId())
                      ->whereDate('created_at', $selectedDate)
                      ->orderBy('created_at', 'desc')
                      ->get();
    
    // Fetch receivings for the selected date only
    $products = recevingModel::where('account', getSessionAccountId())
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
                $mainProduct = productsModel::where('account', getSessionAccountId())
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
                $returnRec->account = getSessionAccountId();
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
                $product->account = getSessionAccountId();
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

    // Lock the record to prevent race conditions
    $receivings = recevingModel::where('account', getSessionAccountId())
                ->where('productId', $product_id)
                ->orderBy('id', 'desc')
                ->lockForUpdate()  // Add this line
                ->first();

    // Check if already approved
    if (!$receivings || $receivings->status == 'Approved') {
        return redirect()->back()->with('error', 'This product has already been approved');
    }

    $product = productsModel::where('product_id', $product_id)
                ->where('account', getSessionAccountId())
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
    stock::where('account', getSessionAccountId())
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
                $restock->account = getSessionAccountId();
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

        $get = productsModel::where('account', getSessionAccountId())->where('product_id', '=', $product_id)->first();
        
        $recive = recevingModel::where('account', getSessionAccountId())->where('productId', '=', $product_id)->first();

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

    if (empty($productId)) {
        return redirect()->back()->with('error', 'Product is required');
    }

    if ($action === 'delete') {
    $receivingId = $req->input('receiving_id');
    
    if($receivingId) {
        // Use exact row ID if provided (fixes deleting only single specific row)
        $receiving = recevingModel::where('id', $receivingId)
            ->where('account', getSessionAccountId())
            ->first();
    } else {
        // Fallback to old method for backwards compatibility
        $receiving = recevingModel::where('productId', $productId)
            ->where('account', getSessionAccountId())
            ->orderBy('id', 'desc');
        
        // Only restrict to pending for non-admin users
        if(Auth::user()->levelStatus != 'Admin') {
            $receiving->whereNotIn('status', ['Approved', 'Returned']);
        }
        
        $receiving = $receiving->first();
    }

        if (!$receiving) {
            return redirect()->back()->with('error', 'Record not found!');
        }

        $receiving->delete();

        stock::where('productId', $productId)
            ->where('account', getSessionAccountId())
            ->whereDate('created_at', $receiving->created_at)
            ->delete();

        logModal::create([
            'title' => 'Receiving Deleted',
            'description' => 'Receiving record deleted for product ID: ' . $productId . ' by ' . session('username')
        ]);

        return redirect()->back()->with('success', 'Receiving record deleted successfully!');
    }

    $receiving = recevingModel::where('productId', $productId)
        ->where('account', getSessionAccountId())
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
            ->where('account', getSessionAccountId())
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
            ->where('account', getSessionAccountId())
            ->whereDate('created_at', $receiving->created_at)
            ->delete();

        $message = 'All quantities returned successfully!';
    } else {
        $receiving->quantity = $currentQuantity - $quantity;
        $receiving->save();

        $stockRow = stock::where('productId', $productId)
            ->where('account', getSessionAccountId())
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
        // Always use today's date - no date parameter allowed
        $today = date('Y-m-d');

        // Fetch receivings for today only (only non-returns)
        $products = recevingModel::where('account', getSessionAccountId())
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

        $purchases = stock::where('account', getSessionAccountId())
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = compact('products', 'purchases');

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

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate', 'shopFilter', 'shops');

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

        // Fetch returns for the selected date
        $products = recevingModel::where('account', getSessionAccountId())
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

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate');

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

        // Fetch only returns
        $query = recevingModel::where('account', getSessionAccountId())
            ->where('is_return', 1);

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

        $data = compact('products', 'selectedDate', 'statusFilter', 'fromDate', 'toDate');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.viewReturns', $data);
        }
        if(!empty($user->levelStatus)) {
            return view('user.viewReturns', $data);
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
                $product->account = getSessionAccountId();
                $product->served_by = $served ?? session('username');
                $product->is_return = 0; // This is a receiving, not a return
                // Always use current timestamp (today)
                $product->created_at = now();
                $product->updated_at = now();

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
     * This decreases product quantity in products table
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

                // Get the product to decrease quantity
                $mainProduct = productsModel::where('account', getSessionAccountId())
                    ->where('product_id', $product_id)
                    ->first();

                if (!$mainProduct) {
                    $allSaved = false;
                    continue;
                }

                // Decrease product quantity (return reduces stock)
                $newStockQty = max(0, ((int) $mainProduct->quantity) + $quantity);
                $mainProduct->quantity = $newStockQty;
                $mainProduct->save();

                // Create return record
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
                $returnRec->account = getSessionAccountId();
                $returnRec->served_by = $served ?? session('username');
                $returnRec->status = 'Returned';
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
                    $create->title = 'Stock Return';
                    $create->description = 'Stock returned: ' . $quantity . ' items. Reason: ' . $reason . ' by ' . session('username');
                    $create->save();
                }
            }

            if ($allSaved) {
                return redirect()->back()->with('success', 'Returns saved successfully and product quantities updated!');
            } else {
                return redirect()->back()->with('error', 'Error saving some returns. Please try again.');
            }
        }

        return redirect()->route('make-return');
    }

    /**
     * Approve selected receiving records
     */
    public function approveSelectedReceivings(Request $req)
    {
        $productIds = $req->input('product_ids', []);
        $user = Auth::user();
        
        if (empty($productIds)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        $approvedCount = 0;
        $failedCount = 0;

        foreach ($productIds as $productId) {
            // Find the pending receiving record
            $receiving = recevingModel::where('account', getSessionAccountId())
                ->where('productId', $productId)
                ->whereNotIn('status', ['Approved', 'Returned'])
                ->where('is_return', '!=', 1)
                ->first();

            if (!$receiving) {
                $failedCount++;
                continue;
            }

            // Get the product
            $product = productsModel::where('product_id', $productId)
                ->where('account', getSessionAccountId())
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
                stock::where('account', getSessionAccountId())
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
            $restock->account = getSessionAccountId();
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
        $user = Auth::user();
        
        // Get all pending (non-approved, non-returned) receivings for the selected date
        $receivings = recevingModel::where('account', getSessionAccountId())
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
                ->where('account', getSessionAccountId())
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
                stock::where('account', getSessionAccountId())
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
            $restock->account = getSessionAccountId();
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
        $user = Auth::user();
        
        // Get ALL pending (non-approved, non-returned) receivings regardless of date
        $receivings = recevingModel::where('account', getSessionAccountId())
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
                ->where('account', getSessionAccountId())
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
                stock::where('account', getSessionAccountId())
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
            $restock->account = getSessionAccountId();
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
        $productIds = $req->input('product_ids', []);
        $selectedDate = $req->input('date', date('Y-m-d'));
        
        // Check if we have exact receiving ID for single row undo
        $receivingId = $req->input('receiving_id');
        
        if($receivingId) {
           // Undo ONLY the exact single row clicked (FIXED!)
           $receivings = recevingModel::where('id', $receivingId)
               ->where('account', getSessionAccountId())
               ->where('status', 'Approved')
               ->get();
        }
        elseif (empty($productIds)) {
           // If no specific IDs, undo all approved for the date
           $receivings = recevingModel::where('account', getSessionAccountId())
               ->whereDate('created_at', $selectedDate)
               ->where('status', 'Approved')
               ->where('is_return', '!=', 1)
               ->get();
       } else {
           // Undo only specific single record
           $receivings = recevingModel::where('account', getSessionAccountId())
               ->whereIn('productId', $productIds)
               ->where('status', 'Approved')
               ->take(1) // FIX: Only undo the single matching row
               ->get();
       }

        if ($receivings->isEmpty()) {
            return redirect()->back()->with('error', 'No approved receivings to undo.');
        }

        $undoneCount = 0;

        foreach ($receivings as $receiving) {
            $productId = $receiving->productId;
            $quantity = $receiving->quantity;

            // Get the product
            $product = productsModel::where('product_id', $productId)
                ->where('account', getSessionAccountId())
                ->first();

            if ($product) {
                // Reduce product quantity
                $newQty = max(0, ((int) $product->quantity) - $quantity);
                $product->quantity = $newQty;
                $product->save();
            }

            // Delete stock entry
            stock::where('account', getSessionAccountId())
                ->where('productId', $productId)
                ->whereDate('created_at', $receiving->created_at)
                ->delete();

            // Reset receiving status to pending
            $receiving->status = 'Pending';
            $receiving->save();

            // Log
            $create = new logModal();
            $create->title = 'Receiving Undone';
            $create->description = 'Receiving undone for product ID: ' . $productId . ' by ' . session('username');
            $create->save();

            $undoneCount++;
        }

        return redirect()->back()->with('success', "{$undoneCount} receiving(s) undone successfully!");
    }

    /**
     * Delete selected receiving records (non-approved only)
     */
    public function deleteSelectedReceivings(Request $req)
    {
        $productIds = $req->input('product_ids', []);
        $user = Auth::user();
        
        if (empty($productIds)) {
            return redirect()->back()->with('error', 'No items selected for deletion.');
        }

        $deletedCount = 0;
        $failedCount = 0;

        foreach ($productIds as $productId) {
            // Find the pending (non-approved) receiving record
            $receiving = recevingModel::where('account', getSessionAccountId())
                ->where('productId', $productId)
                ->where('is_return', '!=', 1);
            
            // Only restrict to pending for non-admin users
            if(Auth::user()->levelStatus != 'Admin') {
                $receiving->whereNotIn('status', ['Approved', 'Returned']);
            }
            
            $receiving = $receiving->first();

            if (!$receiving) {
                $failedCount++;
                continue;
            }

            // Get product name for logging
            $product = productsModel::where('product_id', $productId)
                ->where('account', getSessionAccountId())
                ->first();
            $productName = $product->name01 ?? 'Unknown';

            // Delete the receiving record
            $receiving->delete();

            // Log the deletion
            $create = new logModal();
            $create->title = 'Receiving Deleted (Selected)';
            $create->description = 'Receiving deleted for ' . $productName . ' by ' . session('username');
            $create->save();

            $deletedCount++;
        }

        if ($deletedCount > 0) {
            return redirect()->back()->with('success', "{$deletedCount} receiving(s) deleted successfully!" . ($failedCount > 0 ? " {$failedCount} could not be deleted (already approved)." : ""));
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
                'account' => getSessionAccountId(),
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
        \Log::info('Getting offers for product: ' . $productId . ' in account: ' . getSessionAccountId());
        
        $offers = Offer::where('account', getSessionAccountId())
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
       $offers = Offer::where('account', getSessionAccountId())
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
       $offers = Offer::where('account', getSessionAccountId())
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
        
        $offer = Offer::where('account', getSessionAccountId())
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
        $offer = Offer::where('account', getSessionAccountId())
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
            ->where('sales.account', getSessionAccountId())
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
            ->where('account', getSessionAccountId())
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
        $receiving->supplier = getSessionAccountId();
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

}