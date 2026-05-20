<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionBalance;
use App\Models\TransactionDiscrepancy;
use App\Models\accountModel;
use App\Models\salsModel;
use App\Models\debtsModel;
use App\Models\expensesModel;
use App\Models\recevingModel;
use App\Models\madeni;
use App\Models\BankingTransfer;
use App\Models\BankingChip;
use App\Models\cashSubmitModel;
use App\Models\BankingTransferAllocation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionService
{
    /**
     * Calculate daily balance for a shop.
     * This is the main method that aggregates all transactions and detects discrepancies.
     */
    public function calculateDailyBalance($shopId, Carbon $date)
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();

        // Get or create balance record
        $balance = TransactionBalance::firstOrCreate(
            [
                'shop_id' => $shopId,
                'balance_date' => $date->toDateString(),
            ],
            [
                'account' => $shopId,
                'opening_balance' => $this->getOpeningBalance($shopId, $date),
            ]
        );

        // Calculate all components
        $cashSales = $this->getCashSales($shopId, $startDate, $endDate);
        $creditSales = $this->getCreditSales($shopId, $startDate, $endDate);
        $cashReturns = $this->getCashReturns($shopId, $startDate, $endDate);
        $creditReturns = $this->getCreditReturns($shopId, $startDate, $endDate);
        $receivablesCollected = $this->getReceivablesCollected($shopId, $startDate, $endDate);
        $paymentsMade = $this->getPaymentsMade($shopId, $startDate, $endDate);
        $expenses = $this->getExpenses($shopId, $startDate, $endDate);
        $bankTransfers = $this->getBankTransfers($shopId, $startDate, $endDate);
        $chipDeposits = $this->getChipDeposits($shopId, $startDate, $endDate);
        $chipUsage = $this->getChipUsage($shopId, $startDate, $endDate);
        $cashSubmitted = $this->getCashSubmitted($shopId, $date);
        $paidReceivings = $this->getPaidReceivings($shopId, $startDate, $endDate);
        $supplierPayments = $this->getSupplierPayments($shopId, $startDate, $endDate);

        // Calculate expected cash
        $expectedCash = $cashSales 
            + $receivablesCollected 
            + $chipDeposits 
            + $balance->opening_balance
            - $expenses 
            - $paidReceivings 
            - $supplierPayments 
            - $chipUsage;

        // Calculate cash difference
        $cashDifference = $expectedCash - $cashSubmitted;

        // Calculate closing balance
        $closingBalance = $expectedCash;

        // Update balance record
        $balance->update([
            'cash_sales' => $cashSales,
            'credit_sales' => $creditSales,
            'cash_returns' => $cashReturns,
            'credit_returns' => $creditReturns,
            'receivables_collected' => $receivablesCollected,
            'payments_made' => $paymentsMade,
            'expenses' => $expenses,
            'bank_transfers' => $bankTransfers,
            'chip_deposits' => $chipDeposits,
            'chip_usage' => $chipUsage,
            'cash_submitted' => $cashSubmitted,
            'closing_balance' => $closingBalance,
            'expected_cash' => $expectedCash,
            'cash_difference' => $cashDifference,
            'is_balanced' => abs($cashDifference) <= 0.01,
        ]);

        // Detect and record discrepancies
        $this->detectDiscrepancies($balance, [
            'cash_sales' => $cashSales,
            'credit_sales' => $creditSales,
            'cash_returns' => $cashReturns,
            'credit_returns' => $creditReturns,
            'receivables_collected' => $receivablesCollected,
            'payments_made' => $paymentsMade,
            'expenses' => $expenses,
            'bank_transfers' => $bankTransfers,
            'chip_deposits' => $chipDeposits,
            'chip_usage' => $chipUsage,
            'cash_submitted' => $cashSubmitted,
            'expected_cash' => $expectedCash,
        ]);

        return $balance;
    }

    /**
     * Get opening balance from previous day's closing balance.
     */
    private function getOpeningBalance($shopId, Carbon $date)
    {
        $previousDay = $date->copy()->subDay();
        $previousBalance = TransactionBalance::where('shop_id', $shopId)
            ->where('balance_date', $previousDay->toDateString())
            ->first();

        return $previousBalance ? $previousBalance->closing_balance : 0;
    }

    /**
     * Get total cash sales for the period.
     */
    private function getCashSales($shopId, $startDate, $endDate)
    {
        $sales = salsModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('transactionType', 'Cash')
            ->sum('paid');

        return (float) $sales;
    }

    /**
     * Get total credit sales for the period.
     */
    private function getCreditSales($shopId, $startDate, $endDate)
    {
        $sales = salsModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('transactionType', 'Credit')
            ->sum('credit');

        return (float) $sales;
    }

    /**
     * Get total cash returns for the period.
     */
    private function getCashReturns($shopId, $startDate, $endDate)
    {
        $returns = salsModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Return')
            ->where('transactionType', 'Cash')
            ->sum('totalPrice');

        return (float) $returns;
    }

    /**
     * Get total credit returns for the period.
     */
    private function getCreditReturns($shopId, $startDate, $endDate)
    {
        $returns = salsModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Return')
            ->where('transactionType', 'Credit')
            ->sum('totalPrice');

        return (float) $returns;
    }

    /**
     * Get total receivables collected (paid invoices).
     */
    private function getReceivablesCollected($shopId, $startDate, $endDate)
    {
        $payments = debtsModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        return (float) $payments;
    }

    /**
     * Get total payments made (expenses, supplier payments, etc.).
     */
    private function getPaymentsMade($shopId, $startDate, $endDate)
    {
        // This includes expenses and supplier payments (madeni)
        $expenses = expensesModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $supplierPayments = madeni::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        return (float) ($expenses + $supplierPayments);
    }

    /**
     * Get total expenses for the period.
     */
    private function getExpenses($shopId, $startDate, $endDate)
    {
        $expenses = expensesModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        return (float) $expenses;
    }

    /**
     * Get total bank transfers for the period.
     */
    private function getBankTransfers($shopId, $startDate, $endDate)
    {
        $transfers = BankingTransfer::where('shop_id', $shopId)
            ->whereBetween('transfer_date', [$startDate, $endDate])
            ->sum('amount');

        return (float) $transfers;
    }

    /**
     * Get total chip deposits for the period.
     */
    private function getChipDeposits($shopId, $startDate, $endDate)
    {
        $chips = BankingChip::where('shop_id', $shopId)
            ->whereBetween('transfer_date', [$startDate, $endDate])
            ->sum('chip_amount');

        return (float) $chips;
    }

    /**
     * Get total chip usage for the period.
     */
    private function getChipUsage($shopId, $startDate, $endDate)
    {
        // Chip usage is tracked in debt payments where payment_method = 'chip'
        $chipUsed = debtsModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_method', 'chip')
            ->sum('chip_amount');

        return (float) $chipUsed;
    }

    /**
     * Get total cash submitted for the period.
     */
    private function getCashSubmitted($shopId, Carbon $date)
    {
        $submitted = cashSubmitModel::where('account', $shopId)
            ->whereDate('report_date', $date->toDateString())
            ->sum('submitted_cash');

        return (float) $submitted;
    }

    /**
     * Get total paid receivings (cash received from receivings).
     */
    private function getPaidReceivings($shopId, $startDate, $endDate)
    {
        $paidReceivings = recevingModel::where('account', $shopId)
            ->where('isPaid', 1)
            ->where(function($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'Returned');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('COALESCE(SUM(price * COALESCE(quantity, 0)), 0) as total')
            ->first();

        return (float) ($paidReceivings->total ?? 0);
    }

    /**
     * Get total supplier payments (madeni).
     */
    private function getSupplierPayments($shopId, $startDate, $endDate)
    {
        $payments = madeni::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        return (float) $payments;
    }

    /**
     * Detect discrepancies in the balance.
     */
    private function detectDiscrepancies(TransactionBalance $balance, array $components)
    {
        $shopId = $balance->shop_id;
        $date = $balance->balance_date;

        // Clear old unresolved discrepancies for this balance
        TransactionDiscrepancy::where('balance_id', $balance->id)
            ->where('is_resolved', false)
            ->delete();

        $discrepancies = [];

        // 1. Check cash balance mismatch
        if (abs($balance->cash_difference) > 0.01) {
            $discrepancies[] = [
                'discrepancy_type' => 'cash_mismatch',
                'description' => 'Cash balance mismatch: Expected ' . number_format($balance->expected_cash, 2) . 
                                ', Submitted ' . number_format($balance->cash_submitted, 2) . 
                                ', Difference ' . number_format($balance->cash_difference, 2),
                'expected_value' => $balance->expected_cash,
                'actual_value' => $balance->cash_submitted,
                'impact_amount' => $balance->cash_difference,
                'severity' => $balance->cash_difference > 10000 ? 'critical' : ($balance->cash_difference > 1000 ? 'high' : 'medium'),
            ];
        }

        // 2. Check sales balance (Cash + Credit should equal Total Sales)
        $totalSales = $components['cash_sales'] + $components['credit_sales'];
        $actualTotalSales = $this->getTotalSalesFromSalesTable($shopId, $date);
        
        if (abs($totalSales - $actualTotalSales) > 0.01) {
            $discrepancies[] = [
                'discrepancy_type' => 'sales_mismatch',
                'description' => 'Sales mismatch: Cash (' . number_format($components['cash_sales'], 2) . 
                                ') + Credit (' . number_format($components['credit_sales'], 2) . 
                                ') = ' . number_format($totalSales, 2) . 
                                ', but Total Sales shows ' . number_format($actualTotalSales, 2),
                'expected_value' => $totalSales,
                'actual_value' => $actualTotalSales,
                'impact_amount' => $totalSales - $actualTotalSales,
                'severity' => abs($totalSales - $actualTotalSales) > 10000 ? 'critical' : 'high',
            ];
        }

        // 3. Check for missing transactions (transactions in source tables not tracked)
        $missingCount = $this->countMissingTransactions($shopId, $date);
        if ($missingCount > 0) {
            $discrepancies[] = [
                'discrepancy_type' => 'missing_transactions',
                'description' => "{$missingCount} transactions found in source tables but not tracked in transaction log",
                'expected_value' => $missingCount,
                'actual_value' => 0,
                'impact_amount' => 0,
                'severity' => 'high',
            ];
        }

        // 4. Check chip allocation consistency
        $chipIssues = $this->checkChipConsistency($shopId, $date);
        if ($chipIssues > 0) {
            $discrepancies[] = [
                'discrepancy_type' => 'chip_allocation_issue',
                'description' => "{$chipIssues} chip entries have inconsistent cumulative totals",
                'expected_value' => 0,
                'actual_value' => $chipIssues,
                'impact_amount' => 0,
                'severity' => 'medium',
            ];
        }

        // 5. Check bank transfer matching
        $bankIssues = $this->checkBankTransferMatching($shopId, $date);
        if ($bankIssues > 0) {
            $discrepancies[] = [
                'discrepancy_type' => 'bank_mismatch',
                'description' => "{$bankIssues} bank transfers not reflected in transaction log",
                'expected_value' => $bankIssues,
                'actual_value' => 0,
                'impact_amount' => 0,
                'severity' => 'medium',
            ];
        }

        // Save all discrepancies
        foreach ($discrepancies as $disc) {
            TransactionDiscrepancy::create([
                'balance_id' => $balance->id,
                'shop_id' => $shopId,
                'discrepancy_type' => $disc['discrepancy_type'],
                'description' => $disc['description'],
                'expected_value' => $disc['expected_value'],
                'actual_value' => $disc['actual_value'],
                'impact_amount' => $disc['impact_amount'],
                'severity' => $disc['severity'],
                'account' => $shopId,
            ]);
        }
    }

    /**
     * Get total sales from sales table (for verification).
     */
    private function getTotalSalesFromSalesTable($shopId, $date)
    {
        $total = salsModel::where('account', $shopId)
            ->whereDate('created_at', $date)
            ->sum('totalPrice');

        return (float) $total;
    }

    /**
     * Count missing transactions (in source tables but not in transaction log).
     */
    private function countMissingTransactions($shopId, $date)
    {
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        $missing = 0;

        // Check sales
        $salesCount = salsModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $trackedSales = Transaction::where('shop_id', $shopId)
            ->whereDate('transaction_date', $date)
            ->where('transaction_type', 'sale')
            ->count();
        $missing += max(0, $salesCount - $trackedSales);

        // Check expenses
        $expensesCount = expensesModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $trackedExpenses = Transaction::where('shop_id', $shopId)
            ->whereDate('transaction_date', $date)
            ->where('transaction_type', 'expense')
            ->count();
        $missing += max(0, $expensesCount - $trackedExpenses);

        // Check debt payments
        $paymentsCount = debtsModel::where('account', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $trackedPayments = Transaction::where('shop_id', $shopId)
            ->whereDate('transaction_date', $date)
            ->where('transaction_type', 'debt_payment')
            ->count();
        $missing += max(0, $paymentsCount - $trackedPayments);

        return $missing;
    }

    /**
     * Check chip cumulative total consistency.
     */
    private function checkChipConsistency($shopId, $date)
    {
        $chips = BankingChip::where('shop_id', $shopId)
            ->whereDate('transfer_date', $date)
            ->orderBy('id', 'asc')
            ->get();

        if ($chips->isEmpty()) {
            return 0;
        }

        $runningTotal = 0;
        $issues = 0;

        foreach ($chips as $chip) {
            $runningTotal += $chip->chip_amount;
            if (abs($chip->available_chip - $runningTotal) > 0.01) {
                $issues++;
            }
        }

        return $issues;
    }

    /**
     * Check bank transfer matching with transaction log.
     */
    private function checkBankTransferMatching($shopId, $date)
    {
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        $transfers = BankingTransfer::where('shop_id', $shopId)
            ->whereBetween('transfer_date', [$startDate, $endDate])
            ->count();

        $trackedTransfers = Transaction::where('shop_id', $shopId)
            ->whereDate('transaction_date', $date)
            ->where('transaction_type', 'bank_transfer')
            ->count();

        return max(0, $transfers - $trackedTransfers);
    }

    /**
     * Get comprehensive daily summary for a shop.
     */
    public function getDailySummary($shopId, Carbon $date)
    {
        $balance = TransactionBalance::where('shop_id', $shopId)
            ->where('balance_date', $date->toDateString())
            ->first();

        if (!$balance) {
            $balance = $this->calculateDailyBalance($shopId, $date);
        }

        $discrepancies = TransactionDiscrepancy::where('balance_id', $balance->id)
            ->where('is_resolved', false)
            ->count();

        return [
            'balance' => $balance,
            'unresolved_discrepancies' => $discrepancies,
            'is_balanced' => $balance->is_balanced,
            'cash_difference' => $balance->cash_difference,
        ];
    }

    /**
     * Get all unbalanced shops for a given date.
     */
    public function getUnbalancedShops($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();

        $balances = TransactionBalance::where('balance_date', $date->toDateString())
            ->where('is_balanced', false)
            ->with('shop')
            ->get();

        return $balances;
    }

    /**
     * Generate daily balance report for all shops.
     */
    public function generateDailyReport($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        $shops = accountModel::all();

        $report = [
            'date' => $date->toDateString(),
            'total_shops' => $shops->count(),
            'balanced' => 0,
            'unbalanced' => 0,
            'total_cash_difference' => 0,
            'shops' => [],
        ];

        foreach ($shops as $shop) {
            $summary = $this->getDailySummary($shop->id, $date);
            
            if ($summary['is_balanced']) {
                $report['balanced']++;
            } else {
                $report['unbalanced']++;
                $report['total_cash_difference'] += abs($summary['cash_difference']);
            }

            $report['shops'][] = $summary;
        }

        return $report;
    }
}