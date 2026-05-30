<?php

namespace App\Http\Controllers;

use App\Models\BankingSupplier;
use App\Models\BankingBeneficiary;
use App\Models\BankingTransfer;
use App\Models\BankingTransferAllocation;
use App\Models\BankingAccount;
use App\Models\BankingChip;
use App\Models\accountModel;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\logModal;
use function getSessionAccountId;

class bankingController extends Controller
{
    /**
     * Display banking suppliers list
     */
    public function suppliers()
    {
        $user = Auth::user();

        $suppliers = BankingSupplier::with('accounts')
                        ->get();

        $data = compact('suppliers');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.banking-suppliers', $data);
        }

        if (!empty($user->levelStatus)) {
            return view('user.banking-suppliers', $data);
        }
    }

    /**
     * Display banking partners (suppliers & beneficiaries) list
     */
    public function partners(Request $req)
    {
        $user = Auth::user();

        // Get all shops (accounts) for filter dropdown based on permission
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $shops = collect();
            } else {
                $shops = accountModel::whereIn('id', $assignedAccountIds)
                            ->orderBy('name', 'asc')
                            ->get();
            }
        }

        // Build supplier query with optional shop filter
        $supplierQuery = BankingSupplier::with('accounts');
        if ($req->has('shop_id') && $req->shop_id) {
            $supplierQuery->whereHas('accounts', function ($q) use ($req) {
                $q->where('account', $req->shop_id);
            });
        }
        $suppliers = $supplierQuery->get();

        // Build beneficiary query with optional shop filter
        $beneficiaryQuery = BankingBeneficiary::with('accounts');
        if ($req->has('shop_id') && $req->shop_id) {
            $beneficiaryQuery->whereHas('accounts', function ($q) use ($req) {
                $q->where('account', $req->shop_id);
            });
        }
        $beneficiaries = $beneficiaryQuery->get();

        $shopId = $req->input('shop_id');

        $data = compact('suppliers', 'beneficiaries', 'shops', 'shopId');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.banking-partners', $data)->with($req->all());
        }

        if (!empty($user->levelStatus)) {
            return view('user.banking-partners', $data)->with($req->all());
        }
    }

    /**
     * Display banking beneficiaries list
     */
    public function beneficiaries()
    {
        $user = Auth::user();

        $beneficiaries = BankingBeneficiary::with('accounts')
                            ->get();

        $data = compact('beneficiaries');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.banking-beneficiaries', $data);
        }

        if (!empty($user->levelStatus)) {
            return view('user.banking-beneficiaries', $data);
        }
    }

    /**
     * Store a new banking supplier
     */
    public function storeSupplier(Request $req)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $supplier = new BankingSupplier();
        $supplier->name = $req->name;
        $supplier->bank_name = $req->bank_name;
        $supplier->account_number = $req->account_number;
        $supplier->branch = $req->branch;
        $supplier->swift_code = $req->swift_code;
        $supplier->contact = $req->contact;
        $supplier->address = $req->address;
        $supplier->description = $req->description;
        $supplier->created_by = session('username');
        $supplier->account = $Account;
        $supplier->save();

        // Create the primary bank account for this supplier
        $account = new BankingAccount();
        $account->accountable()->associate($supplier);
        $account->bank_name = $req->bank_name;
        $account->account_number = $req->account_number;
        $account->branch = $req->branch;
        $account->swift_code = $req->swift_code;
        $account->contact = $req->contact;
        $account->address = $req->address;
        $account->description = $req->description;
        $account->created_by = session('username');
        $account->account = $Account;
        $account->is_primary = true;
        $account->save();

        if ($supplier && $account) {
            $log = new logModal();
            $log->title = 'Banking Supplier Created';
            $log->description = $req->name . ' (Banking Supplier) created by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Banking supplier added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add banking supplier');
    }

    /**
     * Store a new banking beneficiary
     */
    public function storeBeneficiary(Request $req)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $beneficiary = new BankingBeneficiary();
        $beneficiary->name = $req->name;
        $beneficiary->bank_name = $req->bank_name;
        $beneficiary->account_number = $req->account_number;
        $beneficiary->branch = $req->branch;
        $beneficiary->swift_code = $req->swift_code;
        $beneficiary->contact = $req->contact;
        $beneficiary->address = $req->address;
        $beneficiary->description = $req->description;
        $beneficiary->created_by = session('username');
        $beneficiary->account = $Account;
        $beneficiary->save();

        // Create the primary bank account for this beneficiary
        $account = new BankingAccount();
        $account->accountable()->associate($beneficiary);
        $account->bank_name = $req->bank_name;
        $account->account_number = $req->account_number;
        $account->branch = $req->branch;
        $account->swift_code = $req->swift_code;
        $account->contact = $req->contact;
        $account->address = $req->address;
        $account->description = $req->description;
        $account->created_by = session('username');
        $account->account = $Account;
        $account->is_primary = true;
        $account->save();

        if ($beneficiary && $account) {
            $log = new logModal();
            $log->title = 'Banking Beneficiary Created';
            $log->description = $req->name . ' (Banking Beneficiary) created by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Banking beneficiary added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add banking beneficiary');
    }

    /**
     * Update banking supplier
     */
    public function updateSupplier(Request $req, $id)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $supplier = BankingSupplier::where('id', $id)->where('account', $Account)->first();

        if (!$supplier) {
            return redirect()->back()->with('error', 'Supplier not found');
        }

        $supplier->name = $req->name;
        $supplier->bank_name = $req->bank_name;
        $supplier->account_number = $req->account_number;
        $supplier->branch = $req->branch;
        $supplier->swift_code = $req->swift_code;
        $supplier->contact = $req->contact;
        $supplier->address = $req->address;
        $supplier->description = $req->description;
        $supplier->save();

        // Update or create primary bank account
        $primaryAccount = $supplier->accounts()->where('is_primary', true)->first();
        if ($primaryAccount) {
            $primaryAccount->bank_name = $req->bank_name;
            $primaryAccount->account_number = $req->account_number;
            $primaryAccount->branch = $req->branch;
            $primaryAccount->swift_code = $req->swift_code;
            $primaryAccount->contact = $req->contact;
            $primaryAccount->address = $req->address;
            $primaryAccount->description = $req->description;
            $primaryAccount->save();
        } else {
            $account = new BankingAccount();
            $account->accountable()->associate($supplier);
            $account->bank_name = $req->bank_name;
            $account->account_number = $req->account_number;
            $account->branch = $req->branch;
            $account->swift_code = $req->swift_code;
            $account->contact = $req->contact;
            $account->address = $req->address;
            $account->description = $req->description;
            $account->created_by = session('username');
            $account->account = $Account;
            $account->is_primary = true;
            $account->save();
        }

        if ($supplier) {
            $log = new logModal();
            $log->title = 'Banking Supplier Updated';
            $log->description = $req->name . ' (Banking Supplier) updated by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Banking supplier updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update banking supplier');
    }

    /**
     * Update banking beneficiary
     */
    public function updateBeneficiary(Request $req, $id)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $beneficiary = BankingBeneficiary::where('id', $id)->where('account', $Account)->first();

        if (!$beneficiary) {
            return redirect()->back()->with('error', 'Beneficiary not found');
        }

        $beneficiary->name = $req->name;
        $beneficiary->bank_name = $req->bank_name;
        $beneficiary->account_number = $req->account_number;
        $beneficiary->branch = $req->branch;
        $beneficiary->swift_code = $req->swift_code;
        $beneficiary->contact = $req->contact;
        $beneficiary->address = $req->address;
        $beneficiary->description = $req->description;
        $beneficiary->save();

        // Update or create primary bank account
        $primaryAccount = $beneficiary->accounts()->where('is_primary', true)->first();
        if ($primaryAccount) {
            $primaryAccount->bank_name = $req->bank_name;
            $primaryAccount->account_number = $req->account_number;
            $primaryAccount->branch = $req->branch;
            $primaryAccount->swift_code = $req->swift_code;
            $primaryAccount->contact = $req->contact;
            $primaryAccount->address = $req->address;
            $primaryAccount->description = $req->description;
            $primaryAccount->save();
        } else {
            $account = new BankingAccount();
            $account->accountable()->associate($beneficiary);
            $account->bank_name = $req->bank_name;
            $account->account_number = $req->account_number;
            $account->branch = $req->branch;
            $account->swift_code = $req->swift_code;
            $account->contact = $req->contact;
            $account->address = $req->address;
            $account->description = $req->description;
            $account->created_by = session('username');
            $account->account = $Account;
            $account->is_primary = true;
            $account->save();
        }

        if ($beneficiary) {
            $log = new logModal();
            $log->title = 'Banking Beneficiary Updated';
            $log->description = $req->name . ' (Banking Beneficiary) updated by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Banking beneficiary updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update banking beneficiary');
    }

    /**
     * Delete banking supplier
     */
    public function deleteSupplier($id)
    {
        $supplier = BankingSupplier::where('id', $id)->where('account', getSessionAccountId())->first();

        if (!$supplier) {
            return redirect()->back()->with('error', 'Supplier not found');
        }

        $name = $supplier->name;
        // Delete associated bank accounts first
        $supplier->accounts()->delete();
        $supplier->delete();

        $log = new logModal();
        $log->title = 'Banking Supplier Deleted';
        $log->description = $name . ' (Banking Supplier) deleted by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Banking supplier deleted successfully');
    }

    /**
     * Delete banking beneficiary
     */
    public function deleteBeneficiary($id)
    {
        $beneficiary = BankingBeneficiary::where('id', $id)->where('account', getSessionAccountId())->first();

        if (!$beneficiary) {
            return redirect()->back()->with('error', 'Beneficiary not found');
        }

        $name = $beneficiary->name;
        // Delete associated bank accounts first
        $beneficiary->accounts()->delete();
        $beneficiary->delete();

        $log = new logModal();
        $log->title = 'Banking Beneficiary Deleted';
        $log->description = $name . ' (Banking Beneficiary) deleted by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Banking beneficiary deleted successfully');
    }

    /**
     * Display banking transfers list
     */
    public function transfers(Request $req)
    {
        $user = Auth::user();
        $accountName = getSessionAccountId();
        $today = date('Y-m-d');
        
        // Build base query - for admins, show all transfers; for regular users, filter by account
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $query = BankingTransfer::with(['supplier', 'beneficiary', 'shop']);
        } else {
            $query = BankingTransfer::with(['supplier', 'beneficiary', 'shop'])
                        ->where('account', $accountName);
        }

        // Date range filter
        if ($req->has('date_from') && $req->date_from) {
            $query->whereDate('transfer_date', '>=', $req->date_from);
        } else if ($req->has('date_to') && $req->date_to) {
            $query->whereDate('transfer_date', '<=', $req->date_to);
        } else {
            $query->whereDate('transfer_date', $today);
        }
        
        // Shop filter - applies to both admins and regular users
        if ($req->has('shop_id') && $req->shop_id) {
            $query->where('shop_id', $req->shop_id);
        }
          
        // Sorting
        $sortBy = $req->get('sort_by', 'transfer_date');
        $sortDirection = $req->get('sort_direction', 'desc');
        $validSortColumns = ['transfer_date', 'amount', 'created_at'];
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'transfer_date';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
        $query->orderBy($sortBy, $sortDirection);

        $transfers = $query->get();

        // Calculate statistics
        $totalDeposits = $transfers->sum('amount');
        $depositCount = $transfers->count();
        $averageDeposit = $depositCount > 0 ? $transfers->avg('amount') : 0;
        $maxDeposit = $transfers->max('amount') ?? 0;
        $minDeposit = $transfers->min('amount') ?? 0;

        $suppliers = BankingSupplier::with('accounts')->get();
        $beneficiaries = BankingBeneficiary::with('accounts')->get();
        
        // Get all shops (accounts) for allocation dropdown
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $shops = collect();
            } else {
                $shops = accountModel::whereIn('id', $assignedAccountIds)
                            ->orderBy('name', 'asc')
                            ->get();
            }
        }

        $data = compact('transfers', 'suppliers', 'beneficiaries', 'shops', 'totalDeposits', 'depositCount', 'averageDeposit', 'maxDeposit', 'minDeposit');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.banking-transfers', $data)->with($req->all());
        }

        if (!empty($user->levelStatus)) {
            return view('user.banking-transfers', $data)->with($req->all());
        }
    }

    /**
     * Store a new banking transfer
     */
    public function storeTransfer(Request $req)
    {
        // Check permission for adding banking transfer
        $user = Auth::user();
        $permissions = $user->permissions;
        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?: [];
        }
        if (!in_array('add_banking_transfer', $permissions)) {
            return redirect()->back()->with('error', 'You do not have permission to add banking transfers');
        }

        $Account = getSessionAccountId();

        $req->validate([
            'transfer_date' => 'required|date',
            'supplier_id' => 'required|exists:banking_suppliers,id',
            'beneficiary_id' => 'required|exists:banking_beneficiaries,id',
            'supplier_account_id' => 'nullable|exists:banking_accounts,id',
            'beneficiary_account_id' => 'nullable|exists:banking_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'chip_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'shop_id' => 'required|exists:accounts,id',
        ], [
            'shop_id.required' => 'Please select a shop for allocation',
        ]);

        // Verify supplier and beneficiary belong to the same account
        $supplier = BankingSupplier::where('id', $req->supplier_id)
                        ->first();
        $beneficiary = BankingBeneficiary::where('id', $req->beneficiary_id)
                        ->first();

        if (!$supplier || !$beneficiary) {
            return redirect()->back()->with('error', 'Invalid supplier or beneficiary selected');
        }

        // Verify selected accounts belong to the supplier/beneficiary
        if ($req->supplier_account_id) {
            $supplierAccount = BankingAccount::where('id', $req->supplier_account_id)
                ->where('accountable_type', BankingSupplier::class)
                ->where('accountable_id', $supplier->id)
                ->first();
            if (!$supplierAccount) {
                return redirect()->back()->with('error', 'Invalid supplier account selected');
            }
        }

        if ($req->beneficiary_account_id) {
            $beneficiaryAccount = BankingAccount::where('id', $req->beneficiary_account_id)
                ->where('accountable_type', BankingBeneficiary::class)
                ->where('accountable_id', $beneficiary->id)
                ->first();
            if (!$beneficiaryAccount) {
                return redirect()->back()->with('error', 'Invalid beneficiary account selected');
            }
        }

        // Check user's access to shop (non-admin users)
        $user = Auth::user();
        if ($user->levelStatus !== 'Admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            
            if (!in_array($req->shop_id, $assignedAccountIds)) {
                return redirect()->back()
                    ->with('error', 'You do not have permission to allocate to the selected shop')
                    ->withInput();
            }
        }

        DB::transaction(function () use ($req, $Account, $supplier, $beneficiary) {
            $transfer = new BankingTransfer();
            $transfer->transfer_date = $req->transfer_date;
            $transfer->supplier_id = $req->supplier_id;
            $transfer->beneficiary_id = $req->beneficiary_id;
            $transfer->supplier_account_id = $req->supplier_account_id;
            $transfer->beneficiary_account_id = $req->beneficiary_account_id;
            $transfer->amount = $req->amount;
            $transfer->description = $req->description;
            $transfer->shop_id = $req->shop_id;
            $transfer->created_by = auth::user()->name;
            $transfer->account = $Account;
            $transfer->save();

            // Create chip entry if chip_amount is provided
            $chipAmount = $req->chip_amount ?? 0;
            if ($chipAmount > 0) {
                $chipEntry = new BankingChip();
                $chipEntry->shop_id = $req->shop_id;
                $chipEntry->transfer_id = $transfer->id;
                $chipEntry->chip_amount = $chipAmount;
                $chipEntry->transfer_date = $req->transfer_date;
                $chipEntry->created_by = auth::user()->name;
                $chipEntry->account = $Account;
                
                // Calculate available_chip as cumulative sum
                $lastChip = BankingChip::where('shop_id', $req->shop_id)
                    ->orderBy('id', 'desc')
                    ->first();
                $previousChipTotal = $lastChip ? $lastChip->available_chip : 0;
                $chipEntry->available_chip = $previousChipTotal + $chipAmount;
                
                $chipEntry->save();
            }
        });

        $log = new logModal();
        $log->title = 'Banking Transfer Created';
        $log->description = 'Transfer of ' . number_format($req->amount, 2) .
                          ' from ' . $supplier->name . ' to ' . $beneficiary->name .
                          ' allocated to shop ID: ' . $req->shop_id .
                          ' created by ' . auth::user()->name;
        $log->save();

        return redirect()->back()->with('success', 'Banking transfer created successfully with shop allocation');
    }

    /**
     * Delete banking transfer
     */
    public function deleteTransfer($id)
    {
        // Check permission for deleting banking transfer
        $user = Auth::user();
        $permissions = $user->permissions;
        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?: [];
        }
        if (!in_array('delete_banking_transfer', $permissions)) {
            return redirect()->back()->with('error', 'You do not have permission to delete banking transfers');
        }

        $transfer = BankingTransfer::where('id', $id)->where('account', getSessionAccountId())->first();

        if (!$transfer) {
            return redirect()->back()->with('error', 'Transfer not found');
        }

        $transfer->delete();

        $log = new logModal();
        $log->title = 'Banking Transfer Deleted';
        $log->description = 'Transfer ID: ' . $id . ' deleted by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Banking transfer deleted successfully');
    }

    /**
     * Store a new bank account for a supplier
     */
    public function storeSupplierAccount(Request $req, $supplierId)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $supplier = BankingSupplier::where('id', $supplierId)
                        ->where('account', $Account)
                        ->first();

        if (!$supplier) {
            return redirect()->back()->with('error', 'Supplier not found');
        }

        // If this is set as primary, unset other primaries
        $isPrimary = $req->has('is_primary');
        if ($isPrimary) {
            $supplier->accounts()->update(['is_primary' => false]);
        }

        $account = new BankingAccount();
        $account->accountable()->associate($supplier);
        $account->bank_name = $req->bank_name;
        $account->account_number = $req->account_number;
        $account->branch = $req->branch;
        $account->swift_code = $req->swift_code;
        $account->contact = $req->contact;
        $account->address = $req->address;
        $account->description = $req->description;
        $account->created_by = session('username');
        $account->account = $Account;
        $account->is_primary = $isPrimary;
        $account->save();

        if ($account) {
            $log = new logModal();
            $log->title = 'Bank Account Added to Supplier';
            $log->description = 'New bank account added to ' . $supplier->name . ' by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Bank account added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add bank account');
    }

    /**
     * Store a new bank account for a beneficiary
     */
    public function storeBeneficiaryAccount(Request $req, $beneficiaryId)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $beneficiary = BankingBeneficiary::where('id', $beneficiaryId)
                            ->where('account', $Account)
                            ->first();

        if (!$beneficiary) {
            return redirect()->back()->with('error', 'Beneficiary not found');
        }

        // If this is set as primary, unset other primaries
        $isPrimary = $req->has('is_primary');
        if ($isPrimary) {
            $beneficiary->accounts()->update(['is_primary' => false]);
        }

        $account = new BankingAccount();
        $account->accountable()->associate($beneficiary);
        $account->bank_name = $req->bank_name;
        $account->account_number = $req->account_number;
        $account->branch = $req->branch;
        $account->swift_code = $req->swift_code;
        $account->contact = $req->contact;
        $account->address = $req->address;
        $account->description = $req->description;
        $account->created_by = session('username');
        $account->account = $Account;
        $account->is_primary = $isPrimary;
        $account->save();

        if ($account) {
            $log = new logModal();
            $log->title = 'Bank Account Added to Beneficiary';
            $log->description = 'New bank account added to ' . $beneficiary->name . ' by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Bank account added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add bank account');
    }

    /**
     * Update a bank account
     */
    public function updateAccount(Request $req, $id)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'branch' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $account = BankingAccount::where('id', $id)
                    ->where('account', $Account)
                    ->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Account not found');
        }

        // If this is set as primary, unset other primaries for this accountable
        $isPrimary = $req->has('is_primary');
        if ($isPrimary) {
            $account->accountable->accounts()->update(['is_primary' => false]);
        }

        $account->bank_name = $req->bank_name;
        $account->account_number = $req->account_number;
        $account->branch = $req->branch;
        $account->swift_code = $req->swift_code;
        $account->contact = $req->contact;
        $account->address = $req->address;
        $account->description = $req->description;
        $account->is_primary = $isPrimary;
        $account->save();

        $log = new logModal();
        $log->title = 'Bank Account Updated';
        $log->description = 'Bank account updated by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Bank account updated successfully');
    }

    /**
     * Delete a bank account
     */
    public function deleteAccount($id)
    {
        $Account = getSessionAccountId();

        $account = BankingAccount::where('id', $id)
                    ->where('account', $Account)
                    ->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Account not found');
        }

        // Check if it's the primary account
        if ($account->is_primary) {
            return redirect()->back()->with('error', 'Cannot delete primary account. Set another account as primary first.');
        }

        $account->delete();

        $log = new logModal();
        $log->title = 'Bank Account Deleted';
        $log->description = 'Bank account deleted by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Bank account deleted successfully');
    }

    /**
     * Display banking chips list
     */
    public function chips(Request $req)
    {
        $user = Auth::user();
        $sessionAccountId = getSessionAccountId();

        // Check permission for viewing all chips
        $permissions = $user->permissions;
        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?: [];
        }
        $canViewAllChips = in_array('view_all_banking_chips', $permissions);

        // Get all accounts (shops) the user can access
        if (strtolower(trim($user->levelStatus)) === 'admin' || $canViewAllChips) {
            $allAccounts = accountModel::orderBy('name', 'asc')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $allAccounts = collect();
            } else {
                $allAccounts = accountModel::whereIn('id', $assignedAccountIds)
                            ->orderBy('name', 'asc')
                            ->get();
            }
        }

        // Build query - filter by selected account or default to session account
        $query = BankingChip::with(['shop', 'transfer'])->whereHas('shop');

        // Determine which account to filter by
        $selectedAccountId = $req->has('account_id') && $req->account_id ? $req->account_id : $sessionAccountId;

        // Apply account filter to the query
        $query->where('account', $selectedAccountId);

        // If user cannot view all chips, restrict to assigned shops only
        if (strtolower(trim($user->levelStatus)) !== 'admin' && !$canViewAllChips) {
            if (!empty($assignedAccountIds)) {
                $query->whereIn('shop_id', $assignedAccountIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Apply shop filter from request
        if ($req->has('shop_id') && $req->shop_id) {
            $query->where('shop_id', $req->shop_id);
        }

        // Date range filter
        if ($req->has('date_from') && $req->date_from) {
            $query->whereDate('transfer_date', '>=', $req->date_from);
        }
        if ($req->has('date_to') && $req->date_to) {
            $query->whereDate('transfer_date', '<=', $req->date_to);
        }

        // Sorting
        $sortBy = $req->get('sort_by', 'transfer_date');
        $sortDirection = $req->get('sort_direction', 'desc');
        $validSortColumns = ['transfer_date', 'chip_amount', 'available_chip', 'created_at'];
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'transfer_date';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
        $query->orderBy($sortBy, $sortDirection);

        $chips = $query->get();

        // Calculate total deposits (sum of chip_amount)
        $totalDeposit = $chips->sum('chip_amount');

        // Determine shops for filter dropdown
        // For admins: always show all accounts as shop options (since shops = accounts in this system)
        // For regular users: show only their assigned accounts
        if (strtolower(trim($user->levelStatus)) === 'admin' || $canViewAllChips) {
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            $shops = $allAccounts;
        }

        $data = compact('chips', 'shops', 'allAccounts', 'totalDeposit', 'selectedAccountId');

        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.banking-chips', $data)->with($req->all());
        }

        if (!empty($user->levelStatus)) {
            return view('user.banking-chips', $data)->with($req->all());
        }
    }

    /**
     * Store a new banking chip entry
     */
    public function storeChip(Request $req)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'shop_id' => 'required|exists:accounts,id',
            'chip_amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
        ]);

        // Check user's access to shop (non-admin users)
        $user = Auth::user();
        if ($user->levelStatus !== 'Admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            
            if (!in_array($req->shop_id, $assignedAccountIds)) {
                return redirect()->back()
                    ->with('error', 'You do not have permission to add chip for the selected shop')
                    ->withInput();
            }
        }

        // Get the last chip entry for this shop and account to calculate cumulative total
        $lastChip = BankingChip::where('shop_id', $req->shop_id)
                    ->where('account', $Account)
                    ->orderBy('id', 'desc')
                    ->first();
        
        $previousAvailable = $lastChip ? $lastChip->available_chip : 0;
        $newAvailable = $previousAvailable + $req->chip_amount;

        $chip = new BankingChip();
        $chip->shop_id = $req->shop_id;
        $chip->chip_amount = $req->chip_amount;
        $chip->available_chip = $newAvailable;
        $chip->transfer_date = $req->transfer_date;
        $chip->created_by = session('username');
        $chip->account = $Account;
        $chip->save();

        if ($chip) {
            $log = new logModal();
            $log->title = 'Banking Chip Created';
            $log->description = 'Chip entry of ' . number_format($req->chip_amount, 2) .
                              ' for shop ID: ' . $req->shop_id .
                              ' (Available: ' . number_format($newAvailable, 2) . ')' .
                              ' created by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Chip entry created successfully');
        }

        return redirect()->back()->with('error', 'Failed to create chip entry');
    }

    /**
     * Update banking chip entry
     */
    public function updateChip(Request $req, $id)
    {
        $Account = getSessionAccountId();

        $req->validate([
            'chip_amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
        ]);

        $chip = BankingChip::where('id', $id)
                ->where('account', $Account)
                ->first();

        if (!$chip) {
            return redirect()->back()->with('error', 'Chip entry not found');
        }

        // Check user's access to shop (non-admin users)
        $user = Auth::user();
        if ($user->levelStatus !== 'Admin') {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            
            if (!in_array($chip->shop_id, $assignedAccountIds)) {
                return redirect()->back()
                    ->with('error', 'You do not have permission to edit this chip entry')
                    ->withInput();
            }
        }

        $oldAmount = $chip->chip_amount;
        $chip->chip_amount = $req->chip_amount;
        $chip->transfer_date = $req->transfer_date;
        $chip->save();

        // Recalculate cumulative available_chip for all entries after this one
        $chip->recalculateCumulativeChip();

        if ($chip) {
            $log = new logModal();
            $log->title = 'Banking Chip Updated';
            $log->description = 'Chip entry changed from ' . number_format($oldAmount, 2) .
                              ' to ' . number_format($req->chip_amount, 2) .
                              ' for shop ID: ' . $chip->shop_id .
                              ' by ' . session('username');
            $log->save();

            return redirect()->back()->with('success', 'Chip entry updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update chip entry');
    }

    /**
     * Delete banking chip entry
     */
    public function deleteChip($id)
    {
        $Account = getSessionAccountId();
        $chip = BankingChip::where('id', $id)
                ->where('account', $Account)
                ->first();

        if (!$chip) {
            return redirect()->back()->with('error', 'Chip entry not found');
        }

        $shopId = $chip->shop_id;
        $chipAmount = $chip->chip_amount;
        $chip->delete();

        // Recalculate cumulative available_chip for all entries after deletion
        $remainingChips = BankingChip::where('shop_id', $shopId)
                            ->where('account', $Account)
                            ->orderBy('id', 'asc')
                            ->get();
        
        $runningTotal = 0;
        foreach ($remainingChips as $remainingChip) {
            $runningTotal += $remainingChip->chip_amount;
            $remainingChip->available_chip = $runningTotal;
            $remainingChip->saveQuietly();
        }

        $log = new logModal();
        $log->title = 'Banking Chip Deleted';
        $log->description = 'Chip entry of ' . number_format($chipAmount, 2) .
                          ' for shop ID: ' . $shopId .
                          ' deleted by ' . session('username');
        $log->save();

        return redirect()->back()->with('success', 'Chip entry deleted successfully');
    }

    /**
     * Display supplier deposit report grouped by supplier
     */
    public function supplierDepositReport(Request $req)
    {
        $user = Auth::user();
        
        // Date range filter
        $dateFrom = $req->input('date_from', date('Y-m-d')); // Default to first day of current month
        $dateTo = $req->input('date_to', date('Y-m-d')); // Default to today
        $shopId = $req->input('shop_id');
        
        // Get all shops (accounts) for filter dropdown based on permission
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $shops = accountModel::orderBy('name', 'asc')->get();
        } else {
            $assignedAccountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
            if (empty($assignedAccountIds)) {
                $shops = collect();
            } else {
                $shops = accountModel::whereIn('id', $assignedAccountIds)
                            ->orderBy('name', 'asc')
                            ->get();
            }
        }
        
        // Build query with filters - NO account filter to show all data across accounts
        $query = BankingTransfer::with(['supplier', 'beneficiary', 'shop'])
                        ->orderBy('transfer_date', 'desc');
        
        if ($dateFrom) {
            $query->whereDate('transfer_date', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('transfer_date', '<=', $dateTo);
        }
        
        // Apply shop filter
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }

        // Apply supplier filter
        if ($req->has('supplier_id') && $req->supplier_id) {
            $query->where('supplier_id', $req->supplier_id);
        }
        
        $transfers = $query->get();
        
        // Group transfers by supplier
        $supplierGroups = $transfers->groupBy('supplier_id');
        
        // Calculate totals per supplier
        $supplierTotals = [];
        foreach ($supplierGroups as $supplierId => $group) {
            $supplier = $group->first()->supplier;
            $supplierTotals[$supplierId] = (object)[
                'supplier' => $supplier,
                'total_amount' => $group->sum('amount'),
                'transfer_count' => $group->count(),
                'transfers' => $group,
            ];
        }
        
        // Sort by total amount descending
        uasort($supplierTotals, function($a, $b) {
            return $b->total_amount <=> $a->total_amount;
        });
        
        // Grand totals
        $grandTotal = $transfers->sum('amount');
        $grandCount = $transfers->count();
        $averageDeposit = $grandCount > 0 ? $transfers->avg('amount') : 0;
        
        // Get suppliers for filter dropdown based on active filters
        // Build a single query that applies ALL current filters (shop + dates) - NO account filter
        $relevantSupplierQuery = BankingTransfer::where('supplier_id', '!=', null);
        
        // Apply same filters as the main report query
        if ($shopId) {
            $relevantSupplierQuery->where('shop_id', $shopId);
        }
        if ($dateFrom) {
            $relevantSupplierQuery->whereDate('transfer_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $relevantSupplierQuery->whereDate('transfer_date', '<=', $dateTo);
        }
        
        $supplierIds = $relevantSupplierQuery->distinct()->pluck('supplier_id')->toArray();
        
        $allSuppliers = BankingSupplier::whereIn('id', $supplierIds)
                            ->orderBy('name')
                            ->get();
        
        $data = compact(
            'supplierTotals',
            'grandTotal',
            'grandCount',
            'averageDeposit',
            'allSuppliers',
            'dateFrom',
            'dateTo',
            'shops',
            'shopId'
        );
        
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            return view('admin.banking-supplier-deposit-report', $data);
        }
        
        if (!empty($user->levelStatus)) {
            return view('user.banking-supplier-deposit-report', $data);
        }
    }

    /**
     * Get suppliers filtered by shop (AJAX endpoint)
     */
    public function getSuppliersByShop(Request $req)
    {
        $shopId = $req->input('shop_id');
        
        // Build query to get suppliers that have transfers for the selected shop
        // NO account filter to show all suppliers across all accounts
        $query = BankingTransfer::where('supplier_id', '!=', null);
        
        // Apply shop filter if provided
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }
        
        // Apply date filters if provided (optional - to match current report filters)
        if ($req->has('date_from') && $req->date_from) {
            $query->whereDate('transfer_date', '>=', $req->date_from);
        }
        if ($req->has('date_to') && $req->date_to) {
            $query->whereDate('transfer_date', '<=', $req->date_to);
        }
        
        // Get distinct supplier IDs
        $supplierIds = $query->distinct()->pluck('supplier_id')->toArray();
        
        // Get suppliers - NO account filter
        $suppliers = BankingSupplier::whereIn('id', $supplierIds)
                            ->orderBy('name', 'asc')
                            ->get(['id', 'name', 'bank_name', 'account_number', 'branch']);
        
        return response()->json([
            'success' => true,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Export supplier deposit report to Excel
     */
    public function exportSupplierDepositReport(Request $req)
    {
        $user = Auth::user();

        // Date range filter
        $dateFrom = $req->input('date_from');
        $dateTo = $req->input('date_to');
        $shopId = $req->input('shop_id');
        $supplierId = $req->input('supplier_id');

        // Determine which accounts the user can access
        if (strtolower(trim($user->levelStatus)) === 'admin') {
            $accountIds = accountModel::pluck('id')->toArray();
        } else {
            $accountIds = UserAccount::where('user_id', $user->id)->pluck('account')->toArray();
        }

        // Build query with filters - restrict to user's accessible accounts
        $query = BankingTransfer::with(['supplier', 'beneficiary', 'shop'])
                        ->whereIn('account', $accountIds);

        if ($dateFrom) {
            $query->whereDate('transfer_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('transfer_date', '<=', $dateTo);
        }

        // Apply shop filter - admins can filter by shop, non-admins already restricted by their access
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }

        // Apply supplier filter
        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        $transfers = $query->get();
        
        // Group transfers by supplier
        $supplierGroups = $transfers->groupBy('supplier_id');
        
        $supplierTotals = [];
        foreach ($supplierGroups as $supplierId => $group) {
            $supplier = $group->first()->supplier;
            $supplierTotals[$supplierId] = (object)[
                'supplier' => $supplier,
                'total_amount' => $group->sum('amount'),
                'transfer_count' => $group->count(),
                'transfers' => $group,
            ];
        }
        
        // Sort by total amount descending
        uasort($supplierTotals, function($a, $b) {
            return $b->total_amount <=> $a->total_amount;
        });
        
        // Prepare data for export
        $exportData = [];
        $rowNum = 1;
        
        foreach ($supplierTotals as $supplierTotal) {
            $supplier = $supplierTotal->supplier;
            
            // First row for supplier header
            $exportData[] = [
                'row' => $rowNum++,
                'supplier' => $supplier->name ?? 'N/A',
                'bank_name' => $supplier->bank_name ?? 'N/A',
                'account_number' => $supplier->account_number ?? 'N/A',
                'branch' => $supplier->branch ?? 'N/A',
                'transfer_date' => '',
                'beneficiary' => '',
                'shop' => '',
                'amount' => number_format($supplierTotal->total_amount, 2),
                'reference' => '',
                'description' => "Total Deposits: {$supplierTotal->transfer_count} transaction(s)",
            ];
            
            // Individual transfer rows
            foreach ($supplierTotal->transfers as $transfer) {
                $exportData[] = [
                    'row' => $rowNum++,
                    'supplier' => '',
                    'bank_name' => '',
                    'account_number' => '',
                    'branch' => '',
                    'transfer_date' => $transfer->transfer_date ? $transfer->transfer_date->format('Y-m-d') : 'N/A',
                    'beneficiary' => $transfer->beneficiary->name ?? 'N/A',
                    'shop' => $transfer->shop->name ?? 'N/A',
                    'amount' => number_format($transfer->amount, 2),
                    'reference' => $transfer->reference_number ?? 'N/A',
                    'description' => $transfer->description ?? '',
                ];
            }
            
            // Add blank row between suppliers
            $exportData[] = [
                'row' => '',
                'supplier' => '',
                'bank_name' => '',
                'account_number' => '',
                'branch' => '',
                'transfer_date' => '',
                'beneficiary' => '',
                'shop' => '',
                'amount' => '',
                'reference' => '',
                'description' => '',
            ];
        }
        
        // Generate CSV
        $fileName = 'supplier-deposit-report-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        $callback = function() use ($exportData) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                '#',
                'Supplier Name',
                'Bank Name',
                'Account Number',
                'Branch',
                'Transfer Date',
                'Beneficiary',
                'Shop',
                'Amount (Tsh)',
                'Reference',
                'Description'
            ]);
            
            foreach ($exportData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
