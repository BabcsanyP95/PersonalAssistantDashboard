<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'currency' => 'required|string|max:3',
            'timezone' => 'required|string',
            'dark_mode' => 'required|boolean',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated',
            'user' => $user->fresh(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $request->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json(['message' => 'Password updated']);
    }
}