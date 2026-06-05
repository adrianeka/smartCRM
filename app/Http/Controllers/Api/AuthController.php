<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (
            !$user ||
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user
            ->createToken('mobile-token')
            ->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function register(Request $request)
        {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'min:8'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'user',
            ]);

            $token = $user
                ->createToken('mobile-token')
                ->plainTextToken;

            return response()->json([
                'message' => 'Register success',
                'token' => $token,
                'user' => $user,
            ], 201);
        }

        public function logout(Request $request)
        {
            /** @var \Laravel\Sanctum\PersonalAccessToken $token */
            $token = $request->user()->currentAccessToken();
            $token->delete();

            return response()->json([
                'message' => 'Logout success'
            ]);
        }

        public function me(Request $request)
            {
                return response()->json([
                    'user' => $request->user()
                ]);
            }
}
