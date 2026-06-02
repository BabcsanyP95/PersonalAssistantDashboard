<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        return Budget::with('category')
            ->where('user_id', $request->user()->id)
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'amount' => 'required|numeric|min:0',
        ]);

        return Budget::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorizeBudget($request, $budget);

        $budget->update($request->validate([
            'amount' => 'required|numeric|min:0',
        ]));

        return $budget;
    }

    public function destroy(Request $request, Budget $budget)
    {
        $this->authorizeBudget($request, $budget);

        $budget->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function authorizeBudget($request, $budget)
    {
        abort_if($budget->user_id !== $request->user()->id, 403);
    }
}