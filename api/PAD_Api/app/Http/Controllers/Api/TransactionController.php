<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        return Transaction::with('category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);
    }

    public function store(Request $request)
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
        ]);

        return response()->json($transaction);
    }

    public function show(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($request, $transaction);

        return $transaction->load('category');
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($request, $transaction);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($validated);

        return response()->json($transaction);
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $this->authorizeTransaction($request, $transaction);

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