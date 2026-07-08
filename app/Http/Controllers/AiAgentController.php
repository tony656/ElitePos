<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\SqlAgentService;

class AiAgentController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500'
        ]);

        $agent = new SqlAgentService();

        try {
            $sql = $agent->generateSql($request->question);

            $agent->validateSql($sql);

            $results = DB::select($sql);

            $humanized = $agent->humanizeResults($request->question, $results, $sql);

            return response()->json([
                'question' => $request->question,
                'sql' => $sql,
                'results' => $results,
                'humanized' => $humanized
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'question' => $request->question
            ], 400);
        }
    }

    public function index(Request $request)
    {
        $agent = new SqlAgentService();
        $suggestedQuestions = $agent->getSuggestedQuestions();

        return view('notification', compact('suggestedQuestions'));
    }
}
