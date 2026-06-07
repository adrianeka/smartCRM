<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

#[OA\Post(
    path: "/api/v1/login",
    summary: "User Login",
    tags: ["Authentication"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "email", type: "string", example: "user@example.com"),
                new OA\Property(property: "password", type: "string", example: "password123"),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Login success"),
        new OA\Response(response: 401, description: "Invalid credentials"),
    ]
)]

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


    #[OA\Post(
    path: "/api/v1/register",
    summary: "User Register",
    tags: ["Authentication"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string", example: "Vonny"),
                new OA\Property(property: "email", type: "string", example: "vonny@gmail.com"),
                new OA\Property(property: "password", type: "string", example: "password123"),
                new OA\Property(property: "password_confirmation", type: "string", example: "password123")
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: "Register success"),
        new OA\Response(response: 422, description: "Validation error")
    ]
)]

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

#[OA\Post(
    path: "/api/v1/logout",
    summary: "User Logout",
    tags: ["Authentication"],
    security: [["sanctum" => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: "Logout success"
        )
    ]
)]

        public function logout(Request $request)
        {
            /** @var \Laravel\Sanctum\PersonalAccessToken $token */
            $token = $request->user()->currentAccessToken();
            $token->delete();

            return response()->json([
                'message' => 'Logout success'
            ]);
        }


#[OA\Get(
    path: "/api/v1/me",
    summary: "Current User Profile",
    tags: ["Authentication"],
    security: [["sanctum" => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: "User profile"
        )
    ]
)]
        public function me(Request $request)
            {
                return response()->json([
                    'user' => $request->user()
                ]);
            }


            #[OA\Post(
    path: "/api/v1/forgot-password",
    summary: "Forgot Password",
    tags: ["Authentication"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "email", type: "string", example: "user@example.com"),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Reset link sent"),
        new OA\Response(response: 422, description: "Validation error"),
    ]
)]


#[OA\Post(
    path: "/api/v1/reset-password",
    summary: "Reset Password",
    tags: ["Authentication"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "token", type: "string"),
                new OA\Property(property: "email", type: "string", example: "user@example.com"),
                new OA\Property(property: "password", type: "string", example: "newpassword123"),
                new OA\Property(property: "password_confirmation", type: "string", example: "newpassword123"),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Password reset success"),
        new OA\Response(response: 422, description: "Validation error"),
    ]
)] 


public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    Password::sendResetLink(
        $request->only('email')
    );

    return response()->json([
        'message' => 'Reset password link sent'
    ]);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::reset(
        $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        ),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        }
    );

    return response()->json([
        'message' => __($status)
    ]);
}

#[OA\Post(
    path: "/api/v1/change-password",
    summary: "Change Password",
    tags: ["Authentication"],
    security: [["sanctum" => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "current_password", type: "string", example: "oldpassword"),
                new OA\Property(property: "new_password", type: "string", example: "newpassword123"),
                new OA\Property(property: "new_password_confirmation", type: "string", example: "newpassword123"),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Password updated"),
        new OA\Response(response: 422, description: "Validation error"),
    ]
)]


public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed'
    ]);

    $user = $request->user();

    if (! Hash::check(
        $request->current_password,
        $user->password
    )) {

        throw ValidationException::withMessages([
            'current_password' => [
                'Current password is incorrect'
            ]
        ]);
    }

    $user->update([
        'password' => Hash::make(
            $request->new_password
        )
    ]);

    return response()->json([
        'message' => 'Password updated successfully'
    ]);
}
}
