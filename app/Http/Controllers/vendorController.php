<?php

namespace App\Http\Controllers;
use App\Models\recevingModel;
use App\Models\vendorModal;
use App\Models\accountModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\logModal;
use App\Models\productsModel;
use App\Models\madeni;
use App\Models\UserAccount;
use function getCurrentShopId;
use function getUserAccounts;

class vendorController extends Controller
{
    public function index(Request $req) {

    if (!canUser('view_suppliers')) {
        abort(403, 'Unauthorized access');
    }
        $user = Auth::user();
        $Account = getCurrentShopId();
        $shops = getUserAccounts();


        // Determine selected shop (for admin filter)
        $selectedShopId = $req->input('shop');
  

        // Build query
        $query = vendorModal::query();

            if ($selectedShopId) {
                $query->where('account', $selectedShopId);
            }
        
        $fetch = $query->get();

        $data = compact(
            'fetch',
            'shops',
        );

            return view('vendors', $data);
    
    }
    


    public function newVendor(Request $req) {

        $Account = getCurrentShopId();

        $name = $req->input('name');
        $contact = $req->input('contact');
        $address = $req->input('address');
        $type = $req->input('type');
        $credit = $req->input('credit');
        $bank = $req->input('bank');
        $account = $req->input('account');
        $description = $req->input('description');

        $insert = new vendorModal();
        $insert->name = $name;
        $insert->location = $address;
        $insert->contact = $contact;
        $insert->description = $description;
        $insert->businessType = $type;
        $insert->credit = $credit;
        $insert->bank = $bank;
        $insert->card = $account;
        $insert->createdBy = Auth::user()->name;
        $insert->account = 7;
        $insert->save();

        if($insert) {

            $create = new logModal();
            $create->title = 'Supplier Created';
            $create->description = $name.'(Supplier) created by '.Auth::user()->name;
            $create->save();

            return redirect()->back()->with('success', 'Supplier added successfully');
        } else {
              $create = new logModal();
            $create->title = 'Supplier Creation Failed';
            $create->description = $name.'(Supplier) creation Failed, by '.Auth::user()->name;
            $create->save();

        return redirect()->back()->with('success', 'faild to add Supplier');

        }
    }

    public function dltVendeor(Request $req) {

                $Account = getCurrentShopId();

        $prodId = $req->input('product_id');

         $deletes = vendorModal::where('id', $prodId)->first();

        $delete = vendorModal::where('id', $prodId)->delete();

        if($delete) {
             $create = new logModal();
            $create->title = 'Supplier Deleted';
            $create->description = $deletes->name .'(Supplier) Deleted By '.Auth::user()->name;
            $create->save();
           
            return redirect()->back()->with('success', 'Supplier Deleted successfully');
        } else {
                       
            return redirect()->back()->with('success', 'Supplier failed to delete');
        }
    }

    public function updateVendor(Request $req) {

        $Account = getCurrentShopId();

        $vendorId = $req->input('vendor_id');

        $vendor = vendorModal::where('id', $vendorId)->first();

        if (!$vendor) {
            return redirect()->back()->with('error', 'Supplier not found');
        }

        $vendor->name = $req->input('name');
        $vendor->location = $req->input('address');
        $vendor->contact = $req->input('contact');
        $vendor->description = $req->input('description');
        $vendor->businessType = $req->input('type');
        $vendor->bank = $req->input('bank');
        $vendor->card = $req->input('account');
        $vendor->save();

        if ($vendor) {
            $create = new logModal();
            $create->title = 'Supplier Updated';
            $create->description = $vendor->name . '(Supplier) updated by ' . Auth::user()->name;
            $create->save();

            return redirect()->back()->with('success', 'Supplier updated successfully');
        } else {
            $create = new logModal();
            $create->title = 'Supplier Update Failed';
            $create->description = $vendor->name . '(Supplier) update failed, by ' . Auth::user()->name;
            $create->save();

            return redirect()->back()->with('error', 'Failed to update Supplier');
        }
    }


    public function viewVendor(Request $req) {

        $user = Auth::user();
        $vendorId = $req->input('vendorId');
        $Account = getCurrentShopId();

        $fetchProduct = productsModel::where('supplier', $vendorId)->get();
        $fetch = vendorModal::where('id', $vendorId)->first();

            $data = compact(
        'fetch','fetchProduct'
    );

        return view('vendorView', $data);

    }
public function supplierCredit(Request $req) {
if (!canUser('manage_supplier_credit')) {
        abort(403, 'Unauthorized access');
    }
    $user = Auth::user();
        $selectedShopId = $req->input('shop');
        $shops = getUserAccounts();
        $shopIds = array_column(getUserAccounts(),'id');

        if(!empty($selectedShopId)) {
            session([
                'selected_shop_id' => $selectedShopId
            ]);
        } else {
            $selectedShopId = null;
        }

    // Build query with shop filter - only show debt/credit items (isDebt = 1)
    $query = recevingModel::where('isDebt', 1)->where('is_return', '!=', 1);
            $query->whereIn('account', $shopIds);
   
             // Get return items (negative values)
    $returnQuery = recevingModel::where('isDebt', 1)
        ->where('is_return', 1)
        ->whereIn('account', $shopIds);

      if ($selectedShopId) {
            $query->where('account', $selectedShopId);
            $returnQuery->where('account', $selectedShopId);
        }
 
    // Filter by date range - default to today if no dates provided
    if ($req->has('date_from') && !empty($req->date_from)) {
        $query->whereDate('created_at', '>=', $req->date_from);
        $returnQuery->whereDate('created_at', '>=', $req->date_from);
    } else {
        // Default to today's date if no date_from provided
        $query->whereDate('created_at', '>=', date('Y-m-d'));
        $returnQuery->whereDate('created_at', '>=', date('Y-m-d'));
    }
    
    if ($req->has('date_to') && !empty($req->date_to)) {
        $query->whereDate('created_at', '<=', $req->date_to);
        $returnQuery->whereDate('created_at', '<=', $req->date_to);
    } else {
        // Default to today's date if no date_to provided
        $query->whereDate('created_at', '<=', date('Y-m-d'));
        $returnQuery->whereDate('created_at', '<=', date('Y-m-d'));
    }

    // Get supplier credits grouped by date and supplier
    $returnTotals = $returnQuery->selectRaw('DATE(created_at) as order_date, supplier, account, SUM(price * quantity) as return_total')
        ->groupBy('order_date', 'supplier', 'account')
        ->get()
        ->keyBy(function ($r) {
            return $r->order_date . '|' . $r->supplier . '|' . $r->account;
        });

    $credits = $query->selectRaw('MAX(receivingId) as receivingId,MAX(receivingName) as receivingName,DATE(created_at) as order_date,account, supplier, SUM(quantity) as quantity, SUM(price * quantity) as total_price')
        ->groupBy('order_date', 'supplier')
        ->groupBy('order_date', 'account')
        ->get();

    $credits = $credits->map(function ($credit) use ($returnTotals) {
        $key = $credit->order_date . '|' . $credit->supplier . '|' . $credit->account;
        $credit->return_total = $returnTotals->has($key)
            ? (float) $returnTotals->get($key)->return_total
            : 0;

        return $credit;
    });

    $returns = 0;

    // Group by date for easier display
    $groupedCredits = $credits->groupBy('order_date');

    // Initialize products array
    $products = collect();

        $data = compact('groupedCredits', 'shops', 'selectedShopId','returns');



        return view('deptors', $data);

}

public function mainCredit(Request $req) {

    $user = Auth::user();
    $selectedShopId = 7;
    $tab = $req->input('tab', 'all'); // all, completed, incomplete, daily-payments
    $dateFrom = $req->input('date_from', now()->startOfYear()->format('Y-m-d'));
    $dateTo = $req->input('date_to', now()->format('Y-m-d'));
    $supplierId = $req->input('supplierId');

    $shops = getUserAccounts();

    // Get all suppliers (vendors) for the shop
    $allSuppliers = vendorModal::where('account', $selectedShopId)
        ->orderBy('name', 'asc')
        ->get();

    // Get all credit receivings (isDebt=1, is_return=0) for the date range
    $creditReceivings = recevingModel::where('isDebt', 1)
        ->where('is_return', 0)
        ->where('account', $selectedShopId)
        ->whereDate('created_at', '>=', $dateFrom)
        ->whereDate('created_at', '<=', $dateTo)
        ->get();

    // Remove exact duplicate product-line rows (same order+product+qty+price)
    // that exist in the legacy data so totals are not doubled.
    $creditReceivings = $creditReceivings->unique(function ($r) {
        return $r->receivingId . '|' . $r->productId . '|' . $r->quantity . '|' . $r->price;
    });

    // Get all payments (madeni) for these receivings
    $receivingIds = $creditReceivings->pluck('receivingId')->toArray();
    $payments = [];
    if (!empty($receivingIds)) {
        $paymentRecords = madeni::whereIn('receivingsId', $receivingIds)
            ->where('account', $selectedShopId)
            ->get();
        
        foreach ($paymentRecords as $payment) {
            $payments[$payment->receivingsId] = ($payments[$payment->receivingsId] ?? 0) + $payment->amount;
        }
    }

    // Get advance payments (receivingsId is null) per supplier for credit balance
    $advancePayments = [];
    $advanceRecords = madeni::whereNull('receivingsId')
        ->where('account', $selectedShopId)
        ->get();
    foreach ($advanceRecords as $advance) {
        $advancePayments[$advance->supplierId] = ($advancePayments[$advance->supplierId] ?? 0) + $advance->amount;
    }

    // Build supplier data with their credits
    $supplierData = [];
    foreach ($allSuppliers as $supplier) {
        $supplierReceivings = $creditReceivings->where('supplier', $supplier->id);

        $totalCredit = 0;
        $totalPaid = 0;
        $totalRemaining = 0;
        $creditCount = 0;
        $completedCount = 0;
        $incompleteCount = 0;

        foreach ($supplierReceivings->groupBy('receivingId') as $receivingId => $rows) {
            $receiving = $rows->first();
            $paid = $payments[$receivingId] ?? 0;
            $lineTotal = $rows->sum(fn($r) => (float)($r->price ?? 0) * (int)($r->quantity ?? 0));
            $remaining = $lineTotal - $paid;

            $totalCredit += $lineTotal;
            $totalPaid += $paid;
            $totalRemaining += max(0, $remaining);
            $creditCount++;

            if ($remaining <= 0 || $receiving->isPaid == 1) {
                $completedCount++;
            } else {
                $incompleteCount++;
            }
        }

        // Subtract advance payments (credit balance) from total remaining
        $advanceCredit = $advancePayments[$supplier->id] ?? 0;
        $totalPaid += $advanceCredit;
        $totalRemaining = $totalRemaining - $advanceCredit;

        $supplierData[] = [
            'supplier' => $supplier,
            'total_credit' => $totalCredit,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalRemaining,
            'credit_count' => $creditCount,
            'completed_count' => $completedCount,
            'incomplete_count' => $incompleteCount,
            'has_credits' => $creditCount > 0,
        ];
    }

    // Filter by tab
    switch ($tab) {
        case 'completed':
            $supplierData = array_filter($supplierData, fn($s) => $s['completed_count'] > 0 && $s['incomplete_count'] == 0);
            break;
        case 'incomplete':
            $supplierData = array_filter($supplierData, fn($s) => $s['incomplete_count'] > 0);
            break;
        case 'daily-payments':
            // Show suppliers who have payments made today
            $today = date('Y-m-d');
            $todayReceivingIds = $creditReceivings->where('created_at', '>=', $today . ' 00:00:00')
                ->where('created_at', '<=', $today . ' 23:59:59')
                ->pluck('receivingId')
                ->toArray();
            
            $todayPayments = madeni::whereIn('receivingsId', $todayReceivingIds)
                ->where('account', $selectedShopId)
                ->whereDate('created_at', $today)
                ->get()
                ->groupBy('receivingsId');
            
            $suppliersWithTodayPayments = [];
            foreach ($todayPayments as $receivingId => $pays) {
                $receiving = $creditReceivings->firstWhere('receivingId', $receivingId);
                if ($receiving) {
                    $suppliersWithTodayPayments[] = $receiving->supplier;
                }
            }
            $suppliersWithTodayPayments = array_unique($suppliersWithTodayPayments);
            
            $supplierData = array_filter($supplierData, fn($s) => in_array($s['supplier']->id, $suppliersWithTodayPayments));
            break;
        case 'all':
        default:
            // Show all suppliers
            break;
    }

    // Re-index array
    $supplierData = array_values($supplierData);

    // Calculate summary metrics
    $totalSuppliers = count($allSuppliers);
    $totalCreditAmount = array_sum(array_column($supplierData, 'total_credit'));
    $totalPaidAmount = array_sum(array_column($supplierData, 'total_paid'));
    $totalRemainingAmount = array_sum(array_column($supplierData, 'total_remaining'));
    $totalOutstanding = count(array_filter($supplierData, fn($s) => $s['incomplete_count'] > 0));

    // If supplierId is provided, show detail view
    if ($supplierId) {
        $supplier = vendorModal::where('id', $supplierId)
            ->where('account', $selectedShopId)
            ->firstOrFail();

        $supplierReceivings = $creditReceivings->where('supplier', $supplierId);
        
        $groupedByDate = [];
        $totalDebt = 0;
        $totalPaidDetail = 0;

        foreach ($supplierReceivings as $receiving) {
            $paid = $payments[$receiving->receivingId] ?? 0;
            $lineTotal = $receiving->price * $receiving->quantity;
            $remaining = $lineTotal - $paid;
            $orderDate = $receiving->created_at instanceof \Carbon\Carbon 
                ? $receiving->created_at->format('Y-m-d') 
                : date('Y-m-d', strtotime($receiving->created_at));

            if (!isset($groupedByDate[$orderDate])) {
                $groupedByDate[$orderDate] = [
                    'date' => $orderDate,
                    'total' => 0,
                    'paid' => 0,
                    'remaining' => 0,
                    'items' => [],
                ];
            }

            $groupedByDate[$orderDate]['total'] += $lineTotal;
            $groupedByDate[$orderDate]['paid'] += $paid;
            $groupedByDate[$orderDate]['remaining'] += max(0, $remaining);
            $groupedByDate[$orderDate]['items'][] = [
                'receiving' => $receiving,
                'paid' => $paid,
                'remaining' => $remaining,
            ];

            $totalDebt += $lineTotal;
            $totalPaidDetail += $paid;
        }

        uasort($groupedByDate, fn($a, $b) => $b['date'] <=> $a['date']);
        $groupedDetails = array_values($groupedByDate);

        $data = compact(
            'supplier', 'groupedDetails', 'shops', 'selectedShopId', 'tab',
            'dateFrom', 'dateTo', 'totalDebt', 'totalPaidDetail',
            'supplierData', 'totalSuppliers', 'totalCreditAmount',
            'totalPaidAmount', 'totalRemainingAmount', 'totalOutstanding'
        );

        return view('main-credit', $data);
    }

    $data = compact(
        'supplierData', 'shops', 'selectedShopId', 'tab',
        'dateFrom', 'dateTo', 'totalSuppliers', 'totalCreditAmount',
        'totalPaidAmount', 'totalRemainingAmount', 'totalOutstanding',
        'creditReceivings', 'payments', 'allSuppliers'
    );

    return view('main-credit', $data);
}


public function supplierDebts(Request $req) {
    $user = Auth::user();
    $selectedShopId = $req->input('shop', 7);
    $shops = getUserAccounts();

    // Get all suppliers
    $allSuppliers = vendorModal::where('account', $selectedShopId)
        ->orderBy('name', 'asc')
        ->get();

    // Get all credit receivings for this shop
    $creditReceivings = recevingModel::where('isDebt', 1)
        ->where('is_return', 0)
        ->where('account', $selectedShopId)
        ->get();

    // Remove exact duplicate product-line rows so totals are not doubled.
    $creditReceivings = $creditReceivings->unique(function ($r) {
        return $r->receivingId . '|' . $r->productId . '|' . $r->quantity . '|' . $r->price;
    });

    // Get all payments
    $receivingIds = $creditReceivings->pluck('receivingId')->toArray();
    $payments = [];
    if (!empty($receivingIds)) {
        $paymentRecords = madeni::whereIn('receivingsId', $receivingIds)
            ->where('account', $selectedShopId)
            ->get();
        
        foreach ($paymentRecords as $payment) {
            $payments[$payment->receivingsId] = ($payments[$payment->receivingsId] ?? 0) + $payment->amount;
        }
    }

    // Get advance payments (receivingsId is null) per supplier for credit balance
    $advancePayments = [];
    $advanceRecords = madeni::whereNull('receivingsId')
        ->where('account', $selectedShopId)
        ->get();
    foreach ($advanceRecords as $advance) {
        $advancePayments[$advance->supplierId] = ($advancePayments[$advance->supplierId] ?? 0) + $advance->amount;
    }

    // Build supplier debt summary
    $supplierDebts = [];
    foreach ($allSuppliers as $supplier) {
        $supplierReceivings = $creditReceivings->where('supplier', $supplier->id);

        // Group by receivingId to sum all product lines per receiving order
        $receivingGroups = $supplierReceivings->groupBy('receivingId');

        $totalDebt = 0;
        $totalPaid = 0;
        $orderCount = 0;

        foreach ($receivingGroups as $receivingId => $rows) {
            $receiving = $rows->first();
            $paid = $payments[$receivingId] ?? 0;
            $lineTotal = $rows->sum(fn($r) => (float)($r->price ?? 0) * (int)($r->quantity ?? 0));
            $remaining = $lineTotal - $paid;

            $totalDebt += $lineTotal;
            $totalPaid += $paid;
            $orderCount++;
        }

        // Subtract advance payments (credit balance) from remaining
        $advanceCredit = $advancePayments[$supplier->id] ?? 0;
        $totalPaid += $advanceCredit;

        $supplierDebts[] = [
            'supplier' => $supplier,
            'total_debt' => $totalDebt,
            'total_paid' => $totalPaid,
            'remaining' => $totalDebt - $totalPaid,
            'order_count' => $orderCount,
        ];
    }

    // Sort by remaining debt (highest first)
    usort($supplierDebts, function($a, $b) {
        return $b['remaining'] <=> $a['remaining'];
    });

    $data = compact('supplierDebts', 'shops', 'selectedShopId');

    return view('supplier-debts', $data);
}

public function supplierDebtDetail(Request $req, $supplierId) {
    $user = Auth::user();
    $selectedShopId = $req->input('shop', 7);
    $shops = getUserAccounts();

    // Get supplier info
    $supplier = vendorModal::where('id', $supplierId)
        ->where('account', $selectedShopId)
        ->firstOrFail();

    // Get all credit receivings for this supplier
    $receivings = recevingModel::where('isDebt', 1)
        ->where('is_return', 0)
        ->where('account', $selectedShopId)
        ->where('supplier', $supplierId)
        ->orderBy('created_at', 'desc')
        ->get();

    // Remove exact duplicate product-line rows so totals are not doubled.
    $receivings = $receivings->unique(function ($r) {
        return $r->receivingId . '|' . $r->productId . '|' . $r->quantity . '|' . $r->price;
    });

    // Get payments for these receivings
    $receivingIds = $receivings->pluck('receivingId')->toArray();
    $payments = [];
    if (!empty($receivingIds)) {
        $paymentRecords = madeni::whereIn('receivingsId', $receivingIds)
            ->where('account', $selectedShopId)
            ->get();
        
        foreach ($paymentRecords as $payment) {
            $payments[$payment->receivingsId] = ($payments[$payment->receivingsId] ?? 0) + $payment->amount;
        }
    }

    // Get advance payments (receivingsId is null) for this supplier
    $advanceCredit = madeni::whereNull('receivingsId')
        ->where('account', $selectedShopId)
        ->where('supplierId', $supplierId)
        ->sum('amount');

    // Build detailed debt data
    $debtDetails = [];
    $totalDebt = 0;
    $totalPaid = 0;

    foreach ($receivings as $receiving) {
        $paid = $payments[$receiving->receivingId] ?? 0;
        $lineTotal = $receiving->price * $receiving->quantity;
        $remaining = $lineTotal - $paid;

        $debtDetails[] = [
            'receiving' => $receiving,
            'paid' => $paid,
            'remaining' => $remaining,
            'payment_history' => madeni::where('receivingsId', $receiving->receivingId)->where('account', $selectedShopId)->orderByDesc('id')->get(),
        ];

        $totalDebt += $lineTotal;
        $totalPaid += $paid;
    }

    // Subtract advance credit from totals
    $totalPaid += $advanceCredit;

    $data = compact(
        'supplier', 'debtDetails', 'shops', 'selectedShopId',
        'totalDebt', 'totalPaid'
    );

    return view('supplier-debt-detail', $data);
}

public function deleteDebtRecord(Request $req) {
    $receivingId = $req->input('receivingId');
    $supplierId = $req->input('supplierId');
    $shop = $req->input('shop', 7);

    try {
        // Delete the receiving record
        $deleted = recevingModel::where('receivingId', $receivingId)
            ->where('account', $shop)
            ->delete();

        if ($deleted) {
            // Also delete related payments
            madeni::where('receivingsId', $receivingId)->delete();

            return redirect()->back()->with('success', 'Debt record deleted successfully');
        }

        return redirect()->back()->with('error', 'Record not found');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Deletion failed: ' . $e->getMessage());
    }
}

    public function updateDebtPayment(Request $req) {
        $receivingId = $req->input('receivingId');
        $supplierId = $req->input('supplierId');
        $newAmount = $req->input('amount');
        $shop = $req->input('shop', 7);

        try {
            // Get the receiving order
            $receiving = recevingModel::where('receivingId', $receivingId)
                ->where('account', $shop)
                ->first();

            if (!$receiving) {
                return redirect()->back()->with('error', 'Receiving order not found');
            }

            // Get all payments for this receiving
            $existingPayments = madeni::where('receivingsId', $receivingId)
                ->where('account', $shop)
                ->get();

            $totalPaid = $existingPayments->sum('amount');

            $lineTotal = $receiving->price * $receiving->quantity;

            /* Validate new amount
            if ($newAmount > $lineTotal) {
                return redirect()->back()->with('error', 'Payment amount cannot exceed total debt');
            } */

            // If there are existing payments, update the most recent one
            if ($existingPayments->count() > 0) {
                $latestPayment = $existingPayments->sortByDesc('id')->first();
                $latestPayment->amount = $newAmount;
                $latestPayment->payment_date = $req->input('payment_date', now()->toDateString());
                $latestPayment->save();
            } else {
                // Create new payment record
                $payment = new madeni();
                $payment->receivingsId = $receivingId;
                $payment->supplierId = $supplierId;
                $payment->amount = $newAmount;
                $payment->payment_date = $req->input('payment_date', now()->toDateString());
                $payment->payment_method = $req->input('payment_method', 'cash');
                $payment->account = $shop;
                $payment->save();
            }

            return redirect()->back()->with('success', 'Payment updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function processDebtPayment(Request $req) {
        $receivingId = $req->input('receivingId');
        $supplierId = $req->input('supplierId');
        $amount = (float) $req->input('amount', 0);
        $shop = $req->input('shop', 7);

        try {
            $receivings = collect();

            if ($receivingId) {
                $receivingIds = array_filter(array_map('trim', explode(',', $receivingId)));

                foreach ($receivingIds as $singleReceivingId) {
                    $receiving = recevingModel::where('receivingId', $singleReceivingId)
                        ->where('account', $shop)
                        ->first();

                    if ($receiving) {
                        $receivings->push($receiving);
                    }
                }

                if ($receivings->isEmpty()) {
                    return redirect()->back()->with('error', 'Receiving order not found');
                }
            } elseif ($supplierId) {
                $receivingRows = recevingModel::where('isDebt', 1)
                    ->where('is_return', 0)
                    ->where('account', $shop)
                    ->where('supplier', $supplierId)
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Remove exact duplicate product-line rows so totals are not doubled.
                $receivingRows = $receivingRows->unique(function ($r) {
                    return $r->receivingId . '|' . $r->productId . '|' . $r->quantity . '|' . $r->price;
                });

                if ($receivingRows->isEmpty()) {
                    return redirect()->back()->with('error', 'No credit orders found for this supplier');
                }

                // Group multiple product lines by receivingId so each receiving is paid once
                $receivings = collect();
                foreach ($receivingRows->groupBy('receivingId') as $receivingId => $rows) {
                    $first = $rows->first();
                    $first->total_line_total = $rows->sum(fn($r) => (float)($r->price ?? 0) * (int)($r->quantity ?? 0));
                    $receivings->push($first);
                }
            } else {
                return redirect()->back()->with('error', 'Invalid payment request');
            }

            $payments = madeni::whereIn('receivingsId', $receivings->pluck('receivingId')->toArray())
                ->where('account', $shop)
                ->get()
                ->groupBy('receivingsId')
                ->map(fn($pays) => $pays->sum('amount'));

            $totalRemaining = 0;
            foreach ($receivings as $r) {
                $paid = $payments[$r->receivingId] ?? 0;
                $lineTotal = (float)($r->total_line_total ?? ($r->price * $r->quantity));
                $remaining = max(0, $lineTotal - $paid);
                $totalRemaining += $remaining;
            }

            if ($amount < 0) {
                return redirect()->back()->with('error', 'Payment amount cannot be negative');
            }


            $paymentDate = $req->input('payment_date', now()->toDateString());
            $paymentMethod = $req->input('payment_type', 'cash');
            $remainingToPay = $amount;
            $effectiveSupplierId = $supplierId ?? $receivings->first()->supplier;

            foreach ($receivings as $receiving) {
                if ($remainingToPay <= 0) {
                    break;
                }

                $paid = $payments[$receiving->receivingId] ?? 0;
                $lineTotal = (float)($receiving->total_line_total ?? ($receiving->price * $receiving->quantity));
                $remaining = max(0, $lineTotal - $paid);

                if ($remaining <= 0) {
                    continue;
                }

                $payForThis = min($remainingToPay, $remaining);

                $payment = new madeni();
                $payment->receivingsId = $receiving->receivingId;
                $payment->supplierId = $effectiveSupplierId;
                $payment->amount = $payForThis;
                $payment->payment_date = $paymentDate;
                $payment->payment_method = $paymentMethod;
                $payment->account = $shop;
                $payment->created_at = $paymentDate;
                $payment->save();

                $remainingToPay -= $payForThis;
            }

            // Store any excess as advance payment (credit balance for future purchases)
            if ($remainingToPay > 0) {
                $advancePayment = new madeni();
                $advancePayment->receivingsId = null;
                $advancePayment->supplierId = $effectiveSupplierId;
                $advancePayment->amount = $remainingToPay;
                $advancePayment->payment_date = $paymentDate;
                $advancePayment->payment_method = $paymentMethod;
                $advancePayment->account = $shop;
                $advancePayment->created_at = $paymentDate;
                $advancePayment->save();

                $successMessage = 'Payment of ' . number_format($amount, 2) . ' processed successfully. '
                    . 'Advance credit of ' . number_format($remainingToPay, 2) . ' stored for future purchases.';
            } else {
                $successMessage = 'Payment of ' . number_format($amount, 2) . ' processed successfully';
            }

            return redirect()->back()->with('success', $successMessage);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

public function deletePayment(Request $req) {
    $id = $req->input('id');

    $query = madeni::where('id', $id)->delete();

    if($query) {
        return redirect()->back()->with('success', "Payment Deleted Successfully");
    } else {
        return redirect()->back()->with('error', "Failed to Delete Payment");
    }
}

    protected $vendorNameCache = [];
    protected $accountNameCache = [];

    /**
     * Resolve a supplier reference to a display name.
     *
     * The `supplier` column stores whichever reference the recording flow had:
     *   • a vendor id             -> vendors table (the normal case)
     *   • a shop ACCOUNT id       -> accounts table (e.g. 7 = "Main Store",
     *                               written by returnToMainStore / shop transfers)
     *   • a plain name (e.g. "Bulk Import")
     *
     * Vendor ids win when they exist, then accounts, then the raw name. Only if
     * nothing matches do we return 'Unknown'. Lookups are cached because a single
     * page repeat the same references (e.g. Main Store) hundreds of times.
     */
    protected function resolveSupplierName(...$candidates)
    {
        $vendorName = function ($id) {
            if (!array_key_exists($id, $this->vendorNameCache)) {
                $this->vendorNameCache[$id] = vendorModal::find($id)->name ?? null;
            }

            return $this->vendorNameCache[$id];
        };

        $accountName = function ($id) {
            if (!array_key_exists($id, $this->accountNameCache)) {
                $this->accountNameCache[$id] = accountModel::find($id)->name ?? null;
            }

            return $this->accountNameCache[$id];
        };

        // 1) Vendor id (or a stored name)
        foreach ($candidates as $value) {
            if (is_numeric($value)) {
                $name = $vendorName($value);
                if (!empty($name)) {
                    return $name;
                }
            } elseif (!is_null($value) && $value !== '') {
                return vendorModal::where('name', $value)->value('name') ?: $value;
            }
        }

        // 2) Account id (e.g. supplier = 7 means "Main Store")
        foreach ($candidates as $value) {
            if (is_numeric($value)) {
                $name = $accountName($value);
                if (!empty($name)) {
                    return $name;
                }
            }
        }

        return 'Unknown';
    }

    public function suplierPayments (Request $req) {
        $user = Auth::user();
        $selectedShopId = $req->input('shop');
        $date_from = $req->date_from ?? date('Y-m-d');
        $date_to = $req->date_to ?? date('Y-m-d');
        $shops = getUserAccounts();
        $shopIds = array_column(getUserAccounts(),'id');
        
        if(!empty($selectedShopId)) {
            session([
                'selected_shop_id' => $selectedShopId
            ]);
        } else {
            $selectedShopId = null;
        }
   
 // Filter by date range - default to today if no dates provided


    // Build query with shop filter - only show debt/credit items (isDebt = 1)
    $querys = madeni::query();
    $querys->whereIn('account', $shopIds);
    if($selectedShopId) {
    $querys->where('account', $selectedShopId);
    }
    $querys->whereDate('created_at', '>=', $date_from)->whereDate('created_at', '<=', $date_to);

    $payments = $querys->get()->map(function ($payment) {
        // Supplier can come from the receiving record or from the payment itself
        $receiving = \App\Models\recevingModel::where('receivingId', $payment->receivingsId)->first();
        $supplierRef = $receiving->supplier ?? $payment->supplierId;

        $payment->supplier_name = $this->resolveSupplierName($supplierRef, $payment->supplierId);
        $payment->shop_name = \App\Models\accountModel::find($payment->account)?->name ?? '';

        return $payment;
    });

    $query = $payments->groupBy(function ($payment) {
        return \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('Y-m-d');
    })->sortKeysDesc();

    $data = compact('query', 'shops', 'selectedShopId');

    return view('supplier-payments', $data);

}

public function mainPaid (Request $req) {
        $user = Auth::user();
        $selectedShopId = $req->input('shop');
        $date_from = $req->date_from ?? date('Y-m-d');
        $date_to = $req->date_to ?? date('Y-m-d');
        $shops = getUserAccounts();
        
            $selectedShopId = 7;
        

    // Build query with shop filter - only show debt/credit items (isDebt = 1)
    $query = madeni::where('account', $selectedShopId)->whereDate('created_at', '>=', $date_from)->whereDate('created_at', '<=', $date_to)->get();

    $payments = $query->map(function ($payment) {
        // Supplier can come from the receiving record or from the payment itself
        $receiving = \App\Models\recevingModel::where('receivingId', $payment->receivingsId)->first();
        $supplierRef = $receiving->supplier ?? $payment->supplierId;

        $payment->supplier_name = $this->resolveSupplierName($supplierRef, $payment->supplierId);
        $payment->shop_name = \App\Models\accountModel::find($payment->account)?->name ?? '';

        return $payment;
    });

    $query = $payments->groupBy(function ($payment) {
        return \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('Y-m-d');
    })->sortKeysDesc();

    $data = compact('query', 'shops', 'selectedShopId');

    return view('main-paid', $data);

}

public function supplierItems(Request $req)
{
    $user = Auth::user();
    
    // Build base query
    $query = recevingModel::where('supplier', $req->supplier)
        ->where('isDebt', 1);

    // Apply account filter based on user role (same logic as supplierCredit)
        $selectedShopId = $req->input('shop') ?? $req->input('account'); // Try both parameters
        if ($selectedShopId) {
            $query->where('account', $selectedShopId);
        }
  

    // Filter by date range - default to today if no dates provided
    if ($req->has('date_from') && !empty($req->date_from)) {
        $query->whereDate('created_at', '>=', $req->date_from);
    } else {
        // Default to today's date if no date_from provided
        $query->whereDate('created_at', '>=', date('Y-m-d'));
    }
    
    if ($req->has('date_to') && !empty($req->date_to)) {
        $query->whereDate('created_at', '<=', $req->date_to);
    } else {
        // Default to today's date if no date_to provided
        $query->whereDate('created_at', '<=', date('Y-m-d'));
    }

    $prods = $query->get();

    $result = [];

    foreach ($prods as $prod) {
        $item = productsModel::where('product_id', $prod->productId)->first();

        if ($item) {
            $result[] = [
                'name01'   => $item->name01,
                'quantity' => $prod->quantity,
                'bPrice'   => (float) $prod->price,
                'supplier' => $prod->supplier,
                'created_at' => $prod->created_at
            ];
        }
    }

    return response()->json($result);
}
 public function supplierPay(Request $request) {
        $date = $request->input('date');
        $supplier = $request->input('supplier');
        $orderid = $request->input('orderid');
        $amountPaid = $request->input('amount');
        $shop = $request->input('shop');

        try {
            // Validate input
            if (!$date || !$supplier || !$orderid) {
                return redirect()->back()->with('error', 'Invalid payment data');
            }

            // Get the receiving order
            $receiving = recevingModel::where('receivingId', $orderid)
                                      ->where('supplier', $supplier)
                                      ->first();

            $receivings = recevingModel::where('receivingId', $orderid)
                                      ->where('supplier', $supplier)
                                      ->sum(DB::raw('price * quantity'));

            if (!$receiving) {
                return redirect()->back()->with('error', 'Receiving order not found');
            }

            // If amount not provided, treat as full payment
            if (!$amountPaid || $amountPaid == '') {
                $amountPaid = $receivings;
            }

            // Validate amount
            if ($amountPaid <= 0) {
                return redirect()->back()->with('error', 'Payment amount must be greater than 0');
            }

            // Create madeni (payment) record
            $payment = new madeni();
            $payment->supplierId = $receiving->supplier ?? $supplier;
            $payment->amount = $amountPaid;
            $payment->receivingsId = $receiving->receivingId ?? $orderid;
            $payment->payment_date = $date ?: ($receiving->created_at ?? now())->toDateString();
            $payment->payment_method = $request->input('payment_method', 'cash');
            $payment->account = $shop ?? '';
            $payment->created_at = $date ?: ($receiving->created_at ?? now())->toDateString();
            $payment->save();



            // Log the transaction
            $logEntry = new logModal();
            $logEntry->title = 'Supplier Payment';
            $logEntry->description = $supplier . ' (Supplier) made a payment of ' . $amountPaid . ' for order ' . $orderid . ' by ' . Auth::user()->name;
            $logEntry->save();

            return redirect()->back()->with('success', 'Payment of ' . number_format($amountPaid, 2) . ' recorded successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

/**

     * Create a manual credit record for a supplier

     */

    public function createManualCredit(Request $request) {

        $supplier = $request->input('supplier');

        $amount = $request->input('amount');

        $shop = $request->input('shop', 7);

        $date = $request->input('date');

        $description = $request->input('description', 'Manual Credit');



        try {

            // Validate input

            if (!$supplier || !$amount || $amount <= 0) {

                return redirect()->back()->with('error', 'Invalid credit data. Supplier and a positive amount are required.');

            }



            // Verify supplier exists

            $vendor = vendorModal::where('id', $supplier)->first();

            if (!$vendor) {

                return redirect()->back()->with('error', 'Supplier not found');

            }



            // Generate a unique receivingId for the manual credit

            $receivingId = 'MC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));



            // Create the receiving (credit) record

            $receiving = new recevingModel();

            $receiving->receivingName = $description;

            $receiving->receivingId = $receivingId;

            $receiving->productId = '0';

            $receiving->quantity = 1;

            $receiving->price = $amount;

            $receiving->sellingPrice = 0;

            $receiving->wholesalePrice = 0;

            $receiving->isDebt = 1;

            $receiving->expiry = '';

            $receiving->supplier = $supplier;

            $receiving->account = $shop;

            $receiving->served_by = Auth::user()->name;

            $receiving->status = 'Debt';

            $receiving->is_return = 0;

            $receiving->isPaid = 0;

            $receiving->created_at = $date ? \Carbon\Carbon::parse($date) : now();

            $receiving->save();



            // Log the transaction

            $logEntry = new logModal();

            $logEntry->title = 'Manual Credit Created';

            $logEntry->description = $vendor->name . ' (Supplier) manual credit of ' . number_format($amount, 2) . ' created by ' . Auth::user()->name;

            $logEntry->save();



            return redirect()->back()->with('success', 'Manual credit of ' . number_format($amount, 2) . ' created successfully for ' . $vendor->name);

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Failed to create manual credit: ' . $e->getMessage());

        }

    }

    public function updateManualCredit(Request $req) {

        $receivingId = $req->input('receiving_id');

        $receiving = recevingModel::where('receivingId', $receivingId)

            ->where('isDebt', 1)

            ->where('is_return', 0)

            ->first();

        if (!$receiving || !str_starts_with($receivingId, 'MC-')) {

            return redirect()->back()->with('error', 'Manual credit not found');

        }

        $receiving->receivingName = $req->input('description', $receiving->receivingName);

        $receiving->price = $req->input('amount');

        $receiving->quantity = 1;

        $receiving->created_at = $req->input('date', $receiving->created_at);

        $receiving->save();

        $logEntry = new logModal();

        $logEntry->title = 'Manual Credit Updated';

        $logEntry->description = 'Manual credit ' . $receivingId . ' updated by ' . Auth::user()->name;

        $logEntry->save();

        return redirect()->back()->with('success', 'Manual credit updated successfully');

    }

    public function deleteManualCredit(Request $req) {

        $receivingId = $req->input('receiving_id');

        $receiving = recevingModel::where('receivingId', $receivingId)

            ->where('isDebt', 1)

            ->where('is_return', 0)

            ->first();

        if (!$receiving || !str_starts_with($receivingId, 'MC-')) {

            return redirect()->back()->with('error', 'Manual credit not found');

        }

        $name = $receiving->receivingName ?: $receivingId;

        $receiving->delete();

        madeni::where('receivingsId', $receivingId)->delete();

        $logEntry = new logModal();

        $logEntry->title = 'Manual Credit Deleted';

        $logEntry->description = 'Manual credit ' . $name . ' deleted by ' . Auth::user()->name;

        $logEntry->save();

        return redirect()->back()->with('success', 'Manual credit deleted successfully');

    }


    /**
     * Delete debt record
     */
    public function deleteDebt(Request $request) {
        $date = $request->input('date');
        $supplier = $request->input('supplier');
        $start_date = date('Y-m-d 00:00:00', strtotime($date));
        $end_date = date('Y-m-d 23:59:59', strtotime($date));

        try {
            $deleted = recevingModel::where('supplier', $supplier)
                                    ->whereBetween('created_at', [$start_date, $end_date])
                                    ->delete();

            if ($deleted > 0) {
                // Log the deletion
                $logEntry = new logModal();
                $logEntry->title = 'Debt Deleted';
                $logEntry->description = 'Supplier credit for ' . $supplier . ' on ' . $date . ' was deleted by ' . Auth::user()->name;
                $logEntry->save();

                return redirect()->back()->with('success', 'Supplier credit deleted successfully');
            }

            return redirect()->back()->with('error', 'No records found to delete');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
}