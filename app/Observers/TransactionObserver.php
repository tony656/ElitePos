<?php

namespace App\Observers;

use App\Models\salsModel;
use App\Models\debtsModel;
use App\Models\expensesModel;
use App\Models\recevingModel;
use App\Models\madeni;
use App\Models\BankingTransfer;
use App\Models\BankingChip;
use App\Models\cashSubmitModel;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionObserver
{
    /**
     * Handle the salsModel "created" event.
     */
    public function createdSalsModel(salsModel $sale)
    {
        $this->recordTransaction($sale, 'sale', $sale->totalPrice, $sale->paid, $sale->credit);
    }

    /**
     * Handle the salsModel "updated" event.
     */
    public function updatedSalsModel(salsModel $sale)
    {
        // Update related transaction if exists
        $transaction = Transaction::where('transaction_id', $sale->sales_id)
            ->where('transaction_type', 'sale')
            ->first();
        
        if ($transaction) {
            $transaction->update([
                'amount' => $sale->totalPrice,
                'balance_after' => $this->recalculateBalance($transaction->shop_id, $transaction->transaction_date),
            ]);
        }
    }

    /**
     * Handle the salsModel "deleted" event.
     */
    public function deletedSalsModel(salsModel $sale)
    {
        // Mark related transaction as deleted (soft reference)
        Transaction::where('transaction_id', $sale->sales_id)
            ->where('transaction_type', 'sale')
            ->update(['status' => 'cancelled']);
    }

    /**
     * Handle the debtsModel "created" event.
     */
    public function createdDebtsModel(debtsModel $payment)
    {
        $this->recordDebtPayment($payment);
    }

    /**
     * Handle the expensesModel "created" event.
     */
    public function createdExpensesModel(expensesModel $expense)
    {
        $this->recordExpense($expense);
    }

    /**
     * Handle the recevingModel "created" event.
     */
    public function createdRecevingModel(recevingModel $receiving)
    {
        $this->recordReceiving($receiving);
    }

    /**
     * Handle the madeni "created" event (supplier payment).
     */
    public function createdMadeni(madeni $payment)
    {
        $this->recordSupplierPayment($payment);
    }

    /**
     * Handle the BankingTransfer "created" event.
     */
    public function createdBankingTransfer(BankingTransfer $transfer)
    {
        $this->recordBankTransfer($transfer);
    }

    /**
     * Handle the BankingChip "created" event.
     */
    public function createdBankingChip(BankingChip $chip)
    {
        $this->recordChipDeposit($chip);
    }

    /**
     * Handle the cashSubmitModel "created" event.
     */
    public function createdCashSubmitModel(cashSubmitModel $submission)
    {
        $this->recordCashSubmission($submission);
    }

    /**
     * Record a sale transaction.
     */
    private function recordTransaction($model, $type, $totalAmount, $paidAmount, $creditAmount)
    {
        $shopId = $model->account;
        $transactionDate = $model->created_at ?? now();
        $salesName = $model->salesName ?? 'Sale';

        // Record cash portion
        if ($paidAmount > 0) {
            Transaction::create([
                'transaction_id' => $model->sales_id ?? Str::random(10),
                'shop_id' => $shopId,
                'transaction_type' => $type,
                'transaction_date' => $transactionDate,
                'reference_type' => get_class($model),
                'reference_id' => $model->id,
                'amount' => $paidAmount,
                'chip_amount' => 0,
                'payment_method' => 'cash',
                'description' => "Cash sale: {$salesName}",
                'status' => 'completed',
                'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
                'created_by' => $model->served_by ?? Auth::id(),
                'account' => $shopId,
            ]);
        }

        // Record credit portion
        if ($creditAmount > 0) {
            Transaction::create([
                'transaction_id' => $model->sales_id ?? Str::random(10) . '-CR',
                'shop_id' => $shopId,
                'transaction_type' => $type,
                'transaction_date' => $transactionDate,
                'reference_type' => get_class($model),
                'reference_id' => $model->id,
                'amount' => $creditAmount,
                'chip_amount' => 0,
                'payment_method' => 'credit',
                'description' => "Credit sale: {$salesName}",
                'status' => 'completed',
                'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
                'created_by' => $model->served_by ?? Auth::id(),
                'account' => $shopId,
            ]);
        }
    }

    /**
     * Record a debt payment transaction.
     */
    private function recordDebtPayment(debtsModel $payment)
    {
        $shopId = $payment->account;
        $transactionDate = $payment->created_at ?? now();
        $transactionId = 'DEBT-' . $payment->orderId . '-' . time();

        $chipAmount = $payment->chip_amount ?? 0;
        $cashAmount = $payment->amount - $chipAmount;

        // Record cash portion
        if ($cashAmount > 0) {
            Transaction::create([
                'transaction_id' => $transactionId . '-CASH',
                'shop_id' => $shopId,
                'transaction_type' => 'debt_payment',
                'transaction_date' => $transactionDate,
                'reference_type' => get_class($payment),
                'reference_id' => $payment->id,
                'amount' => $cashAmount,
                'chip_amount' => 0,
                'payment_method' => 'cash',
                'description' => "Debt payment for invoice: {$payment->orderId}",
                'status' => 'completed',
                'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
                'created_by' => Auth::id(),
                'account' => $shopId,
            ]);
        }

        // Record chip portion
        if ($chipAmount > 0) {
            Transaction::create([
                'transaction_id' => $transactionId . '-CHIP',
                'shop_id' => $shopId,
                'transaction_type' => 'debt_payment',
                'transaction_date' => $transactionDate,
                'reference_type' => get_class($payment),
                'reference_id' => $payment->id,
                'amount' => $chipAmount,
                'chip_amount' => $chipAmount,
                'payment_method' => 'chip',
                'description' => "Chip payment for invoice: {$payment->orderId}",
                'status' => 'completed',
                'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
                'created_by' => Auth::id(),
                'account' => $shopId,
            ]);
        }
    }

    /**
     * Record an expense transaction.
     */
    private function recordExpense(expensesModel $expense)
    {
        $shopId = $expense->account;
        $transactionDate = $expense->created_at ?? now();

        Transaction::create([
            'transaction_id' => 'EXP-' . $expense->id,
            'shop_id' => $shopId,
            'transaction_type' => 'expense',
            'transaction_date' => $transactionDate,
            'reference_type' => get_class($expense),
            'reference_id' => $expense->id,
            'amount' => -$expense->amount, // Negative for outflow
            'chip_amount' => 0,
            'payment_method' => 'cash',
            'description' => $expense->description ?? 'Expense',
            'status' => 'completed',
            'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
            'created_by' => Auth::id(),
            'account' => $shopId,
        ]);
    }

    /**
     * Record a receiving transaction.
     */
    private function recordReceiving(recevingModel $receiving)
    {
        $shopId = $receiving->account;
        $transactionDate = $receiving->created_at ?? now();
        $amount = $receiving->price * ($receiving->quantity ?? 1);
        $isPaid = $receiving->isPaid ?? false;
        $productName = $receiving->productName ?? 'Item';

        Transaction::create([
            'transaction_id' => 'RCV-' . $receiving->id,
            'shop_id' => $shopId,
            'transaction_type' => $isPaid ? 'receiving' : 'credit_receiving',
            'transaction_date' => $transactionDate,
            'reference_type' => get_class($receiving),
            'reference_id' => $receiving->id,
            'amount' => $isPaid ? $amount : 0,
            'chip_amount' => 0,
            'payment_method' => $isPaid ? 'cash' : 'credit',
            'description' => "Receiving: {$productName}",
            'status' => 'completed',
            'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
            'created_by' => Auth::id(),
            'account' => $shopId,
        ]);
    }

    /**
     * Record a supplier payment (madeni).
     */
    private function recordSupplierPayment(madeni $payment)
    {
        $shopId = $payment->account;
        $transactionDate = $payment->created_at ?? now();

        Transaction::create([
            'transaction_id' => 'PAY-' . $payment->id,
            'shop_id' => $shopId,
            'transaction_type' => 'supplier_payment',
            'transaction_date' => $transactionDate,
            'reference_type' => get_class($payment),
            'reference_id' => $payment->id,
            'amount' => -$payment->amount, // Negative for outflow
            'chip_amount' => 0,
            'payment_method' => 'cash',
            'description' => $payment->description ?? 'Supplier payment',
            'status' => 'completed',
            'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
            'created_by' => Auth::id(),
            'account' => $shopId,
        ]);
    }

    /**
     * Record a bank transfer.
     */
    private function recordBankTransfer(BankingTransfer $transfer)
    {
        $shopId = $transfer->shop_id;
        $transactionDate = $transfer->transfer_date ?? $transfer->created_at ?? now();
        $beneficiaryName = $transfer->beneficiary ? ($transfer->beneficiary->name ?? 'Unknown') : 'Unknown';

        Transaction::create([
            'transaction_id' => 'BANK-' . $transfer->id,
            'shop_id' => $shopId,
            'transaction_type' => 'bank_transfer',
            'transaction_date' => $transactionDate,
            'reference_type' => get_class($transfer),
            'reference_id' => $transfer->id,
            'amount' => -$transfer->amount, // Negative (cash outflow to bank)
            'chip_amount' => 0,
            'payment_method' => 'bank',
            'description' => "Bank transfer to: {$beneficiaryName}",
            'status' => 'completed',
            'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
            'created_by' => $transfer->created_by ?? Auth::id(),
            'account' => $transfer->account,
        ]);
    }

    /**
     * Record a chip deposit.
     */
    private function recordChipDeposit(BankingChip $chip)
    {
        $shopId = $chip->shop_id;
        $transactionDate = $chip->transfer_date ?? $chip->created_at ?? now();

        Transaction::create([
            'transaction_id' => 'CHIP-' . $chip->id,
            'shop_id' => $shopId,
            'transaction_type' => 'chip_deposit',
            'transaction_date' => $transactionDate,
            'reference_type' => get_class($chip),
            'reference_id' => $chip->id,
            'amount' => $chip->chip_amount,
            'chip_amount' => $chip->chip_amount,
            'payment_method' => 'chip',
            'description' => "Chip deposit",
            'status' => 'completed',
            'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
            'created_by' => $chip->created_by ?? Auth::id(),
            'account' => $chip->account,
        ]);
    }

    /**
     * Record a cash submission.
     */
    private function recordCashSubmission(cashSubmitModel $submission)
    {
        $shopId = $submission->account;
        $transactionDate = $submission->report_date ?? $submission->created_at ?? now();

        Transaction::create([
            'transaction_id' => 'CSH-' . $submission->id,
            'shop_id' => $shopId,
            'transaction_type' => 'cash_submission',
            'transaction_date' => $transactionDate,
            'reference_type' => get_class($submission),
            'reference_id' => $submission->id,
            'amount' => -$submission->submitted_cash, // Negative (cash outflow)
            'chip_amount' => 0,
            'payment_method' => 'cash',
            'description' => 'Cash submission to main office',
            'status' => 'completed',
            'balance_after' => $this->recalculateBalance($shopId, $transactionDate),
            'created_by' => Auth::id(),
            'account' => $shopId,
        ]);
    }

    /**
     * Recalculate balance after transaction.
     */
    private function recalculateBalance($shopId, $transactionDate)
    {
        $date = Carbon::parse($transactionDate)->toDateString();
        
        // Get all transactions up to this point on the same day
        $transactions = Transaction::where('shop_id', $shopId)
            ->whereDate('transaction_date', $date)
            ->where('created_at', '<=', now())
            ->orderBy('created_at', 'asc')
            ->get();

        $balance = 0;
        foreach ($transactions as $tx) {
            $balance += $tx->amount;
        }

        return $balance;
    }
}