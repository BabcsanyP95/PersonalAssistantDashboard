<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SavingsGoal;
use Illuminate\Http\Request;

class SavingsGoalController extends Controller
{
    public function index(Request $request)
    {
        return SavingsGoal::where('user_id', $request->user()->id)->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
            'target_date' => 'required|date',
        ]);

        return SavingsGoal::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'current_amount' => $validated['current_amount'] ?? 0,
        ]);
    }

    public function update(Request $request, SavingsGoal $goal)
    {
        $this->authorizeGoal($request, $goal);

        $goal->update($request->validate([
            'name' => 'required|string',
            'target_amount' => 'required|numeric',
            'current_amount' => 'required|numeric',
            'target_date' => 'required|date',
            'is_completed' => 'boolean',
        ]));

        return $goal;
    }

    public function destroy(Request $request, SavingsGoal $goal)
    {
        $this->authorizeGoal($request, $goal);

        $goal->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function deposit(Request $request, SavingsGoal $goal)
    {
        $this->authorizeGoal($request, $goal);

        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $goal->increment('current_amount', $data['amount']);

        return $goal->fresh();
    }

    private function authorizeGoal($request, $goal)
    {
        abort_if($goal->user_id !== $request->user()->id, 403);
    }
}
