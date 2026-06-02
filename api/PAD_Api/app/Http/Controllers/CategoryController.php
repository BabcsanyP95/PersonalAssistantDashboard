<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return Category::where('user_id', $request->user()->id)
            ->withCount('transactions')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'color' => 'nullable|string|max:7',
        ]);

        return Category::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $this->authorizeCategory($request, $category);

        $category->update($request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]));

        return $category;
    }

    public function destroy(Request $request, Category $category)
    {
        $this->authorizeCategory($request, $category);

        if ($category->transactions()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category with transactions'
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function authorizeCategory($request, $category)
    {
        abort_if($category->user_id !== $request->user()->id, 403);
    }
}