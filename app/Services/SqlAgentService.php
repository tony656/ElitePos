<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;

class SqlAgentService
{
    private const BLOCKED_KEYWORDS = ['DELETE', 'UPDATE', 'INSERT', 'DROP', 'TRUNCATE', 'ALTER', 'CREATE', 'REPLACE', 'MERGE'];

    private function groqChat(array $messages, float $temperature = 0, int $maxTokens = 500): string
    {
        $apiKey = config('services.groq.api_key');

        $response = Http::withToken($apiKey)
            ->withoutVerifying()
            ->timeout(60)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => $messages,
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->failed()) {
                throw new \Exception('Groq API error: ' . $response->body());
            }

        return trim($response->json('choices.0.message.content'));
    }

    public function getSchema()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $schemaParts = [];

        $businessTables = [
            'products', 'sales', 'customers', 'vendors', 'expenses',
            'receivings', 'orders', 'coupons', 'debts', 'item_requests',
            'notifications', 'banking_transfers', 'banking_chips',
            'transaction_balances', 'offers', 'stock'
        ];

        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_{$dbName}"};
            if (!in_array($tableName, $businessTables)) {
                continue;
            }

            $columns = Schema::getColumnListing($tableName);

            $schemaParts[] = $tableName . "(\n        " . implode(", ", $columns) . "\n    )";
        }

        return implode("\n\n", $schemaParts);
    }

    public function generateSql(string $question): string
    {
        $schema = $this->getSchema();

        $prompt = "
You are a SQL expert for a Point-of-Sale (POS) system called LERUMA POS.

Database schema:
{$schema}

RULES:
1. Return ONLY a single SELECT statement. No explanations.
2. Do NOT use DELETE, UPDATE, INSERT, DROP, TRUNCATE, ALTER, CREATE, REPLACE, or MERGE.
3. Always use LIMIT 200.
4. Use table aliases for readability.
5. If joining tables, use meaningful alias names (e.g., p for products, s for sales).";

        $content = $this->groqChat([
            ['role' => 'system', 'content' => $prompt],
            ['role' => 'user', 'content' => $question],
        ], temperature: 0, maxTokens: 500);

        $content = preg_replace('/^```sql\s*/i', '', $content);
        $content = preg_replace('/^```\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        $sql = trim($content);

        $sql = rtrim($sql, ';');
        $sql = preg_replace('/\s+LIMIT\s+\d+\s*$/i', '', $sql);
        $sql .= ' LIMIT 200';

        return $sql;
    }

    public function validateSql(string $sql): void
    {
        $upperSql = strtoupper($sql);

        foreach (self::BLOCKED_KEYWORDS as $keyword) {
            if (preg_match('/\b' . $keyword . '\b/', $upperSql)) {
                throw new \Exception("Blocked keyword detected: " . $keyword . ". Only read-only queries are allowed.");
            }
        }

        $upperSql = trim($upperSql);
        if (!str_starts_with($upperSql, 'SELECT') && !str_starts_with($upperSql, 'WITH')) {
            throw new \Exception("Only SELECT queries are allowed.");
        }

        if (preg_match('/\bINTO\s+OUTFILE\b/i', $upperSql)) {
            throw new \Exception("File operations are not allowed in queries.");
        }
    }

    public function humanizeResults(string $question, array $results, string $sql): string
    {
        $resultSummary = '';
        if (empty($results)) {
            $resultSummary = 'No results found for this query.';
        } else {
            $sample = array_slice($results, 0, 5);
            $resultSummary = "Found " . count($results) . " row(s). ";
            $resultSummary .= "Sample rows: " . json_encode($sample) . ". ";
        }

        $prompt = "
You are a helpful business assistant for LERUMA POS, a Point-of-Sale system.

User asked: {$question}

SQL executed: {$sql}

Query results: {$resultSummary}

Business context:
- products: Items in inventory with names, barcodes, prices (bPrice=buying, sPrice=selling), quantities, suppliers, expiry dates
- sales: Sales/transaction records with total, saleDate, cash/credit payment, discount, served_by (user)
- customers: Customer information with contact details
- vendors / suppliers: Shop/vendor accounts and credit relationships
- receivings: Stock incoming/receiving records
- orders: Sales order data linked to products
- expenses: Business expense records
- item_requests: Inter-store item requests
- transaction_balances: Financial transaction balances
- offers: Discount/promotional offers on products

Instructions:
- Convert the raw results into clear, easy-to-understand business language.
- Use business terms: revenue, profit, stock, sales, customers, expenses, debt, etc.
- If null or zero values appear, say 'no data' or 'not recorded'.
- Keep the response concise but informative. Use bullet points.
- Do NOT mention SQL or technical database terms to the user.
- If results are empty, suggest what they might want to check instead.
";

        return $this->groqChat([
            ['role' => 'user', 'content' => $prompt],
        ], temperature: 0.7, maxTokens: 800);
    }

    public function getSuggestedQuestions(): array
    {
        return [
            'What is my total revenue today?',
            'Show me my top 10 best selling products this month',
            'Which products are running low on stock?',
            'How much total sales did we make today?',
            'Who are my top 5 customers by total purchases?',
            'Show me today\'s sales summary by payment type',
            'Which products have expired or are expiring soon?',
            'What is my current total inventory value?',
            'Show me sales records for the last 7 days',
            'Which supplier has the highest total receivings?',
            'List all customers who still have outstanding debts',
            'What is today\'s total expense amount?',
            'Show me the most recent sales transactions',
            'How many sales were cash vs credit today?',
            'List products with wholesale price above 5000'
        ];
    }
}
