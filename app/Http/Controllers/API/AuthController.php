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
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user =Auth::user();
    
    
            
    
     
    
        $token = $user->createToken('login', ['*'], now()->addHour())->plainTextToken;
    
    
    
    
        $success['token'] = $token;
    
    
        $success['name'] =$user->name;
        $response=[
        'success' => true,
        'data' => $success,
        'message' => 'User login successfully'
    
        ];
    
        
    
    
        return response()->json($response, 200);
    }
    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401); // Invalid password
    }
    else {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401); 
    }
    
    
    
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
