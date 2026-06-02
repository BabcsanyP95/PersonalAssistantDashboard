<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RecurringTransaction;
use Illuminate\Http\Request;

class RecurringTransactionController extends Controller
{
    public function index(Request $request)
    {
        return RecurringTransaction::with('category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'next_run_date' => 'required|date',
        ]);

        return RecurringTransaction::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);
    }

    public function show(Request $request, RecurringTransaction $recurring)
    {
        $this->authorize($request, $recurring);

        return $recurring->load('category');
    }

    public function update(Request $request, RecurringTransaction $recurring)
    {
        $this->authorize($request, $recurring);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'next_run_date' => 'required|date',
        ]);

        $recurring->update($validated);

        return $recurring;
    }

    public function destroy(Request $request, RecurringTransaction $recurring)
    {
        $this->authorize($request, $recurring);

        $recurring->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function authorize($request, $recurring)
    {
        abort_if(
            $recurring->user_id !== $request->user()->id,
            403
        );
    }
}