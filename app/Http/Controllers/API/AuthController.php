<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuthLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class AuthController extends Controller
{
    //


    public function register(Request $request)
    { 
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user);
    }

   public function login(Request $request)
{
    // Validate the request
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Attempt to login
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();

        // Create token
        $token = $user->createToken('login', ['*'], now()->addHour())->plainTextToken;

        // Prepare response
        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'name' => $user->name,
            ],
            'message' => 'User login successfully'
        ], 200);
    }

    // If login fails
    return response()->json([
        'success' => false,
        'message' => 'Invalid credentials'
    ], 401);
}

    public function synclogout(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated'
        ], 401);
    }


    $request->user()->currentAccessToken()->delete();

    

    

    return response()->json([
        'success' => true,
        'message' => 'User logged out successfully'
    ], 200);
}


    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
