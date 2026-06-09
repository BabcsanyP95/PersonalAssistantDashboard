<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Services\BudgetService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        return Transaction::with('category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);
    }

    public function store(Request $request, BudgetService $budgetService)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction = Transaction::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'month' => Carbon::parse($validated['transaction_date'])->month,
            'year' => Carbon::parse($validated['transaction_date'])->year,
        ]);


        if ($transaction->type === \App\Enums\TransactionType::EXPENSE) {
            $budgetService->applyExpense($transaction);
        }

        return $transaction->load('category');
    }

    public function show(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($request, $transaction);

        return $transaction->load('category');
    }

    public function update(Request $request, Transaction $transaction, BudgetService $budgetService)
    {
        $this->authorizeTransaction($request, $transaction);

        $oldTransaction = $transaction->replicate();

        $oldTransaction->id = $transaction->id;
        $oldTransaction->user_id = $transaction->user_id;

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($validated);

        if ($oldTransaction->type === 'expense') {
            $budgetService->revertExpense($oldTransaction);
        }

        if ($transaction->type === 'expense') {
            $budgetService->applyExpense($transaction);
        }

        return $transaction->load('category');
    }

    public function destroy(Request $request, Transaction $transaction, BudgetService $budgetService)
    {
        $this->authorizeTransaction($request, $transaction);

        if ($transaction->type === 'expense') {
            $budgetService->revertExpense($transaction);
        }

        $transaction->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function authorizeTransaction($request, $transaction)
    {
        abort_if(
            $transaction->user_id !== $request->user()->id,
            403
        );
    }
}
