<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function createToken($user)
    {
        return $user->createToken('authToken')->plainTextToken;
    }

    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);
        $request['password'] = bcrypt($request->password);
        $user = User::create($request->all());
        $token = $this->createToken($user);
        return res_data(['token' => $token], 'User created successfully', 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('authToken')->plainTextToken;
            return res_data(['token' => $token], 'Login successful', 200);
        }
        return response(['error' => 'Unauthenticated'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return res_data([], 'Logout successful', 200);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return res_data(new UserResource($user), 'User profile', 200);
    }
}
