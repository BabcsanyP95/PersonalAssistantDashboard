<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Transaction;
use Carbon\Carbon;

class BudgetService
{
    public function applyExpense(Transaction $transaction): void
    {
        $date = Carbon::parse($transaction->transaction_date);

        $budget = Budget::where('user_id', $transaction->user_id)
            ->where('category_id', $transaction->category_id)
            ->where('month', $transaction->month)
            ->where('year', $transaction->year)
            ->first();


        if ($budget) {
            $budget->decrement('current_amount', $transaction->amount);
        }
    }

    public function revertExpense(Transaction $transaction): void
    {
        $date = Carbon::parse(
            $transaction->transaction_date
        );

        $budget = Budget::where('user_id', $transaction->user_id)
            ->where('category_id', $transaction->category_id)
            ->where('month', $transaction->month)
            ->where('year', $transaction->year)
            ->first();

        if ($budget) {
            $budget->increment(
                'current_amount',
                $transaction->amount
            );
        }
    }
}
