<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponseHelper;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function createToken($user)
    {
        return $user->createToken('authToken')->plainTextToken;
    }

    public function register(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string',
                'phone' => 'required|string|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:6'
            ]);

            $validated['password'] = bcrypt($request->password);
            $user = User::create($validated);
            $token = $this->createToken($user);
            return ApiResponseHelper::resData(['token' => $token], 'User created successfully', 201);

        } catch (ValidationException $e) {
            return ApiResponseHelper::resData($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            \Log::error('Registration error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }

    public function login(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Attempt to authenticate
            if (Auth::attempt($validated)) {
                // Generate token
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;
                return ApiResponseHelper::resData(['token' => $token], 'Login successful', 200);
            } else {
                // Return 401 Unauthorized response
                return ApiResponseHelper::resData([], 'Invalid credentials provided.', 401);
            }
        } catch (ValidationException $e) {
            return ApiResponseHelper::resData($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            \Log::error('Login error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }



    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return ApiResponseHelper::resData([], 'Logout successful', 200);
        } catch (\Exception $e) {
            \Log::error('Logout error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = Auth::user();
            return ApiResponseHelper::resData(new UserResource($user), 'User profile', 200);
        } catch (\Exception $e) {
            \Log::error('Profile error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }
}
