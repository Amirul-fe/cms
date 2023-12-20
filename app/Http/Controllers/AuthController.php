<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Traits\ApiTraits;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiTraits;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (! $token) {
            return response()->json([
                'status' => 0,
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'status' => 1,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);

    }

    public function register(UserRegisterRequest $request)
    {

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return $this->apiResponse(1, 'User created successfully', $user);
        } catch (Exception $e) {

            return $this->apiResponse(0, $e->getMessage());

        }
    }

    public function logout()
    {
        Auth::logout();

        return $this->apiResponse(1, 'User logged out successfully');
    }

    public function getProfile()
    {
        return response()->json([
            'status' => 1,
            'user' => Auth::user(),
        ]);
    }
}
