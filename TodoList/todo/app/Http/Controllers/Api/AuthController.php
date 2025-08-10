<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register new user
     */
   public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'device_name' => 'required|string' // Tetap required tapi beri nilai default
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    $deviceName = $request->device_name ?? 'Flutter_App';

    return response()->json([
        'token' => $user->createToken($deviceName)->plainTextToken,
        'user' => $user
    ]);
}

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
            $request->validate([
        'email' => 'required|email',  // Harus format email
        'password' => 'required|min:8',
        'device_name' => 'required|string' // Wajib ada
    ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken,
            'user' => $user
        ]);
    }

    /**
     * Logout user (Revoke the token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get authenticated user details
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}