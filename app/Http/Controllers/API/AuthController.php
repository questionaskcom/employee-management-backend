<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuthLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }


       
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User already exists'
            ], 400);
        }


 

  
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
    

 

        $token = $user->createToken('Register', ['*'], now()->addHour())->plainTextToken;



    $success['token'] = $token;

        $success['name'] = $user->name;



        // Get user's IP address
    $ipAddress = $request->ip();

    // Fetch location details based on IP
    $location = $this->getLocationFromIP($ipAddress);

        AuthLog::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'action' => 'login',
            'method' => 'Register',
            'ip_address' => $ipAddress,
        'location' => json_encode($location), // Convert array to JSON string,
        ]);



        return response()->json([
            'success' => true,
            'data' => $success,
            'message' => 'User registered successfully, and tables created.'
        ], 200);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
 
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }



    private function getLocationFromIP($ip)
{
    try {
        $response = file_get_contents("http://ip-api.com/json/{$ip}");
        $data = json_decode($response, true);

        if ($data && $data['status'] === 'success') {
            return [
                'city' => $data['city'] ?? null,
                'state' => $data['regionName'] ?? null,
                'country' => $data['country'] ?? null,
            ];
        }
    } catch (\Exception $e) {
        return [
            'city' => null,
            'state' => null,
            'country' => null,
        ];
    }

    return [
        'city' => null,
        'state' => null,
        'country' => null,
    ];
}
}
