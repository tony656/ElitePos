<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\accountModel;
use App\Models\logModal;
use Illuminate\Support\Facades\Auth;

class MigrationController extends Controller
{
    /**
     * Show the migration dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        \Log::info("Migration: index() called. User: " . ($user->name ?? 'unknown') . ", level: " . ($user->levelStatus ?? 'unknown'));
        
        // Only admin can access this page
        if ($user->levelStatus !== 'Admin') {
            abort(403, 'Unauthorized');
        }

        // Get all tables that have an 'account' column
        $tablesWithAccount = $this->getTablesWithAccountColumn();
        
        \Log::info("Migration: Found " . count($tablesWithAccount) . " tables with account columns");
        
        // Get account mapping (name => id)
        $accounts = accountModel::all();
        \Log::info("Migration: Found " . count($accounts) . " accounts in accounts table");
        
        $accountMap = [];
        foreach ($accounts as $account) {
            $accountMap[$account->name] = $account->id;
        }

        \Log::info("Migration: Account map: " . json_encode($accountMap));
        \Log::info("Migration: Tables with account: " . json_encode(array_column($tablesWithAccount, 'name')));
        
        return view('migration', compact('tablesWithAccount', 'accountMap'));
    }

    /**
     * Get list of tables that have account-related columns
     */
    private function getTablesWithAccountColumn()
    {
        $database = config('database.connections.mysql.database');
        \Log::info("Migration: Scanning database: $database");
        
        try {
            $tables = DB::select("SHOW TABLES FROM `$database`");
        } catch (\Exception $e) {
            \Log::error("Migration: Failed to get tables: " . $e->getMessage());
            return [];
        }
        
        \Log::info("Migration: Found " . count($tables) . " total tables");
        
        $tablesWithAccount = [];
        $possibleColumnNames = ['account', 'account_id', 'accountId', 'accountname', 'account_name', 'shop', 'shop_id', 'shopId', 'store', 'store_id'];
        
        foreach ($tables as $tableObj) {
            $tableName = array_values((array)$tableObj)[0];
            \Log::debug("Migration: Checking table: $tableName");
            
            // Skip Laravel system tables
            if (in_array($tableName, ['migrations', 'failed_jobs', 'password_reset_tokens', 'personal_access_tokens', 'cache', 'cache_locks', 'jobs', 'job_batches', 'sessions', 'notifications'])) {
                \Log::debug("Migration: Skipping system table: $tableName");
                continue;
            }
            
            // Check for various account-related column names
            $accountColumn = null;
            foreach ($possibleColumnNames as $colName) {
                try {
                    $columns = DB::select("SHOW COLUMNS FROM `$tableName` LIKE '$colName'");
                    if (!empty($columns)) {
                        $accountColumn = $colName;
                        \Log::info("Migration: Found column '$colName' in table '$tableName'");
                        break;
                    }
                } catch (\Exception $e) {
                    \Log::debug("Migration: Error checking column $colName in $tableName: " . $e->getMessage());
                }
            }
            
            if (!$accountColumn) {
                continue; // No account-related column found
            }
            
            try {
                // Get total count of records with non-null account
                $totalCount = DB::table($tableName)->whereNotNull($accountColumn)->count();
                
                if ($totalCount > 0) {
                    // Get sample account values
                    $sample = DB::table($tableName)
                        ->whereNotNull($accountColumn)
                        ->limit(5)
                        ->pluck($accountColumn)
                        ->toArray();
                    
                    \Log::info("Migration: Table $tableName - column: $accountColumn, total: $totalCount, sample: " . json_encode($sample));
                    
                    // Count non-numeric values (strings that need migration)
                    $nonNumericCount = DB::table($tableName)
                        ->where($accountColumn, 'NOT REGEXP', '^[0-9]+$')
                        ->count();
                    
                    \Log::info("Migration: Table $tableName - non-numeric count: $nonNumericCount");
                    
                    $tablesWithAccount[] = [
                        'name' => $tableName,
                        'column' => $accountColumn,
                        'total_records' => $totalCount,
                        'needs_migration' => $nonNumericCount,
                        'sample' => $sample
                    ];
                }
            } catch (\Exception $e) {
                \Log::error("Migration: Error processing table $tableName: " . $e->getMessage());
            }
        }
        
        \Log::info("Migration: Found " . count($tablesWithAccount) . " tables with account columns");
        return $tablesWithAccount;
    }

    /**
     * Migrate a single table: replace account names with IDs
     */
    public function migrateTable(Request $request)
    {
        $user = Auth::user();
        
        if ($user->levelStatus !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tableName = $request->input('table');
        $dryRun = $request->input('dry_run', false);

        // Validate table name (security)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
            return response()->json(['success' => false, 'message' => 'Invalid table name'], 400);
        }

        // Check if table exists and has account column
        $columns = DB::select("SHOW COLUMNS FROM `$tableName` LIKE 'account'");
        $accountColumn = 'account';
        if (empty($columns)) {
            $columns = DB::select("SHOW COLUMNS FROM `$tableName` LIKE 'account_id'");
            if (!empty($columns)) {
                $accountColumn = 'account_id';
            } else {
                return response()->json(['success' => false, 'message' => "Table '$tableName' does not have an 'account' or 'account_id' column"], 400);
            }
        }

        // Get all distinct account values in this table that are not numeric
        $accountValues = DB::table($tableName)
            ->where($accountColumn, 'NOT REGEXP', '^[0-9]+$')
            ->distinct()
            ->pluck($accountColumn)
            ->toArray();

        if (empty($accountValues)) {
            return response()->json([
                'success' => true, 
                'message' => "Table '$tableName' already has numeric account IDs or no records to migrate"
            ]);
        }

        // Get account ID mapping
        $accounts = accountModel::whereIn('name', $accountValues)->get();
        $nameToId = [];
        foreach ($accounts as $account) {
            $nameToId[$account->name] = $account->id;
        }

        // Find account values that don't exist in accounts table
        $unmatched = array_diff($accountValues, array_keys($nameToId));

        if (!empty($unmatched)) {
            return response()->json([
                'success' => false, 
                'message' => "The following account values in '$tableName' do not exist in accounts table: " . implode(', ', $unmatched)
            ], 400);
        }

        if ($dryRun) {
            return response()->json([
                'success' => true,
                'dry_run' => true,
                'message' => "DRY RUN: Would update " . count($accountValues) . " account records in '$tableName'",
                'mappings' => $nameToId,
                'column' => $accountColumn
            ]);
        }

        // Perform the migration
        $updatedCount = 0;
        foreach ($nameToId as $name => $id) {
            $affected = DB::table($tableName)
                ->where($accountColumn, $name)
                ->update([$accountColumn => $id]);
            $updatedCount += $affected;
        }

        // Log the migration
        $log = new logModal();
        $log->title = 'Account Migration';
        $log->description = "Migrated table '$tableName': replaced $updatedCount account name(s) with IDs by " . Auth::user()->name;
        $log->save();

        return response()->json([
            'success' => true,
            'message' => "Successfully migrated $updatedCount records in '$tableName'",
            'updated_count' => $updatedCount,
            'mappings' => $nameToId
        ]);
    }

    /**
     * Migrate all tables at once
     */
    public function migrateAll(Request $request)
    {
        $user = Auth::user();
        
        if ($user->levelStatus !== 'Admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $dryRun = $request->input('dry_run', false);
        $tables = $this->getTablesWithAccountColumn();
        
        $results = [];
        $totalUpdated = 0;

        foreach ($tables as $table) {
            $tableName = $table['name'];
            $accountColumn = $table['column'] ?? 'account';
            
            // Get account values in this table
            $accountValues = DB::table($tableName)
                ->where($accountColumn, 'NOT REGEXP', '^[0-9]+$')
                ->distinct()
                ->pluck($accountColumn)
                ->toArray();

            if (empty($accountValues)) {
                $results[$tableName] = ['skipped' => true, 'message' => 'No non-numeric accounts found'];
                continue;
            }

            // Get account ID mapping
            $accounts = accountModel::whereIn('name', $accountValues)->get();
            $nameToId = [];
            foreach ($accounts as $account) {
                $nameToId[$account->name] = $account->id;
            }

            if ($dryRun) {
                $results[$tableName] = [
                    'dry_run' => true,
                    'would_update' => count($accountValues),
                    'mappings' => $nameToId,
                    'column' => $accountColumn
                ];
                continue;
            }

            // Perform migration
            $updatedCount = 0;
            foreach ($nameToId as $name => $id) {
                $affected = DB::table($tableName)
                    ->where($accountColumn, $name)
                    ->update([$accountColumn => $id]);
                $updatedCount += $affected;
            }
            
            $totalUpdated += $updatedCount;
            $results[$tableName] = ['updated' => $updatedCount];
        }

        if (!$dryRun) {
            $log = new logModal();
            $log->title = 'Mass Account Migration';
            $log->description = "Migrated all tables: $totalUpdated total records updated by " . Auth::user()->name;
            $log->save();
        }

        return response()->json([
            'success' => true,
            'dry_run' => $dryRun,
            'total_updated' => $totalUpdated,
            'results' => $results
        ]);
    }
}