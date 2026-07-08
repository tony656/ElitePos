<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionDiscrepancy;
use App\Models\TransactionBalance;
use App\Models\accountModel;
use App\Models\UserAccount;
use App\Models\salsModel;
use App\Models\expensesModel;
use App\Models\debtsModel;
use App\Models\recevingModel;
use App\Models\cashSubmitModel;
use App\Models\BankingTransfer;
use App\Models\BankingChip;
use App\Models\madeni;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use function getUserAccounts;

class BalanceCheckController extends Controller
{
    /**
     * Display all discrepancies for a specific shop and date
     */
    public function showDiscrepancies($shopId, $date)
    {
        $user = Auth::user();
        
        // Check user permission to view this shop
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($shopId, $assignedAccountIds)) {
                return redirect()->back()->with('error', 'You do not have permission to view this shop.');
            }
        }
        
        // Get shop details
        $shop = accountModel::find($shopId);
        if (!$shop) {
            return redirect()->back()->with('error', 'Shop not found.');
        }
        
        // Get transaction balance for this shop/date
        $balance = TransactionBalance::where('shop_id', $shopId)
            ->where('balance_date', $date)
            ->first();
        
        if (!$balance) {
            return redirect()->back()->with('error', 'No balance record found for this date.');
        }
        
        // Get all transactions for this shop and date
        $transactions = \App\Models\Transaction::where('shop_id', $shopId)
            ->whereDate('transaction_date', $date)
            ->orderBy('transaction_date', 'asc')
            ->get();
        
        // Get all discrepancies for this balance
        $discrepancies = TransactionDiscrepancy::where('balance_id', $balance->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Group discrepancies by type
        $groupedDiscrepancies = $discrepancies->groupBy('discrepancy_type');
        
        // Calculate summary statistics
        $summary = [
            'total' => $discrepancies->count(),
            'critical' => $discrepancies->where('severity', 'critical')->count(),
            'high' => $discrepancies->where('severity', 'high')->count(),
            'medium' => $discrepancies->where('severity', 'medium')->count(),
            'low' => $discrepancies->where('severity', 'low')->count(),
            'resolved' => $discrepancies->where('is_resolved', true)->count(),
            'unresolved' => $discrepancies->where('is_resolved', false)->count(),
        ];
        
        $data = compact('shop', 'balance', 'discrepancies', 'groupedDiscrepancies', 'date', 'summary', 'transactions');
        
            return view('balance-discrepancies', $data);

    }
    
    /**
     * Show detailed information about a specific discrepancy
     */
    public function showDiscrepancyDetail($id)
    {
        $user = Auth::user();
        
        $discrepancy = TransactionDiscrepancy::find($id);
        if (!$discrepancy) {
            return redirect()->back()->with('error', 'Discrepancy not found.');
        }
        
        // Check permission to view this shop
        $balance = $discrepancy->balance;
        if (!$balance) {
            return redirect()->back()->with('error', 'Associated balance not found.');
        }
        
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($balance->shop_id, $assignedAccountIds)) {
                return redirect()->back()->with('error', 'You do not have permission to view this discrepancy.');
            }
        }
        
        // Get related transactions if available
        $relatedTransactions = [];
        if ($discrepancy->transaction_ids) {
            $transactionIds = json_decode($discrepancy->transaction_ids, true);
            if (!empty($transactionIds)) {
                $relatedTransactions = \App\Models\Transaction::whereIn('id', $transactionIds)->get();
            }
        }
        
        $data = compact('discrepancy', 'relatedTransactions');
        
            return view('discrepancy-detail', $data);
     
    }
    
    /**
     * Mark a discrepancy as resolved
     */
    public function resolveDiscrepancy($id, Request $request)
    {
        $user = Auth::user();
        
        // Check permission - only admin or users with specific permission can resolve
        if (strtolower(trim($user->levelStatus)) !== 'admin' && !canUser('resolve_discrepancies')) {
            return response()->json(['success' => false, 'message' => 'You do not have permission to resolve discrepancies.'], 403);
        }
        
        $discrepancy = TransactionDiscrepancy::find($id);
        if (!$discrepancy) {
            return response()->json(['success' => false, 'message' => 'Discrepancy not found.'], 404);
        }
        
        $request->validate([
            'resolution_notes' => 'nullable|string|max:1000',
        ]);
        
        $discrepancy->is_resolved = true;
        $discrepancy->resolved_by = $user->name ?? $user->id;
        $discrepancy->resolution_notes = $request->input('resolution_notes');
        $discrepancy->resolved_at = now();
        $discrepancy->save();
        
        // Log the resolution
        $log = new \App\Models\logModal();
        $log->title = 'Discrepancy Resolved';
        $log->description = "Discrepancy #{$discrepancy->id} ({$discrepancy->discrepancy_type}) marked as resolved by " . ($user->name ?? 'System');
        $log->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Discrepancy resolved successfully.',
            'discrepancy' => $discrepancy
        ]);
    }
    
    /**
     * Recalculate balance for a specific shop and date
     */
    public function recalculateBalance($shopId, $date)
    {
        $user = Auth::user();
        
        // Check permission
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (!in_array($shopId, $assignedAccountIds)) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to recalculate this balance.'], 403);
            }
        }
        
        try {
            $transactionService = new \App\Services\TransactionService();
            $reportDate = Carbon::parse($date);
            
            // Recalculate the balance
            $balance = $transactionService->calculateDailyBalance($shopId, $reportDate);
            
            return response()->json([
                'success' => true,
                'message' => 'Balance recalculated successfully.',
                'balance' => $balance
            ]);
        } catch (\Exception $e) {
            \Log::error('Balance recalculation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error recalculating balance: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Show all discrepancies across all shops (admin only)
     */
    public function allDiscrepancies(Request $req)
    {
        $user = Auth::user();
        
        if (strtolower(trim($user->levelStatus)) !== 'admin') {
            return redirect()->back()->with('error', 'Only administrators can view all discrepancies.');
        }
        
        $dateFrom = $req->input('date_from');
        $dateTo = $req->input('date_to');
        $shopFilter = $req->input('shop_id');
        $statusFilter = $req->input('status', 'all'); // all, pending, resolved
        
        $query = TransactionDiscrepancy::with(['balance.shop', 'resolver'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($dateFrom) {
            $query->whereHas('balance', function($q) use ($dateFrom) {
                $q->where('balance_date', '>=', $dateFrom);
            });
        }
        
        if ($dateTo) {
            $query->whereHas('balance', function($q) use ($dateTo) {
                $q->where('balance_date', '<=', $dateTo);
            });
        }
        
        if ($shopFilter) {
            $query->whereHas('balance', function($q) use ($shopFilter) {
                $q->where('shop_id', $shopFilter);
            });
        }
        
        if ($statusFilter === 'pending') {
            $query->where('is_resolved', false);
        } elseif ($statusFilter === 'resolved') {
            $query->where('is_resolved', true);
        }
        
        $discrepancies = $query->paginate(50);
        
        // Get all shops for filter dropdown
        $shops = accountModel::orderBy('name', 'asc')->get();
        
        // Summary statistics
        $totalPending = TransactionDiscrepancy::where('is_resolved', false)->count();
        $totalResolved = TransactionDiscrepancy::where('is_resolved', true)->count();
        $totalAmount = TransactionDiscrepancy::where('is_resolved', false)->sum('expected_amount') - 
                      TransactionDiscrepancy::where('is_resolved', false)->sum('actual_amount');
        
        $data = compact(
            'discrepancies', 
            'shops', 
            'totalPending', 
            'totalResolved', 
            'totalAmount',
            'dateFrom',
            'dateTo',
            'shopFilter',
            'statusFilter'
        );
        
        return view('all-discrepancies', $data);
    }
    
    /**
     * Get discrepancy summary for dashboard/API
     */
    public function getDiscrepancySummary(Request $req)
    {
        $user = Auth::user();
        $date = $req->input('date', date('Y-m-d'));
        $shops = getUserAccounts();
        $shopIds = array_column($shops, 'id');
 
        
        if (empty($shopIds)) {
            return response()->json([
                'success' => true,
                'summary' => []
            ]);
        }
        
        $summary = [];
        foreach ($shopIds as $shopId) {
            $balance = TransactionBalance::where('shop_id', $shopId)
                ->where('balance_date', $date)
                ->first();
            
            if ($balance) {
                $pendingCount = TransactionDiscrepancy::where('balance_id', $balance->id)
                    ->where('is_resolved', false)
                    ->count();
                
                if ($pendingCount > 0) {
                    $shop = accountModel::find($shopId);
                    $summary[] = [
                        'shop_id' => $shopId,
                        'shop_name' => $shop ? $shop->name : 'Unknown',
                        'pending_discrepancies' => $pendingCount,
                        'is_balanced' => $balance->is_balanced,
                        'cash_difference' => $balance->cash_difference,
                    ];
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'date' => $date,
            'summary' => $summary,
            'total_shops_with_issues' => count($summary)
        ]);
    }
}