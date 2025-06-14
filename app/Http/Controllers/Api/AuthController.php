<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exceptions\UnverifiedUserException;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\AccountLockedException;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Sanctum\PersonalAccessToken;
use App\Traits\SendsTokenResponses;

class AuthController extends Controller
{
    use ApiResponse, SendsTokenResponses;

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw new InvalidCredentialsException();
        }

        $user = User::where('email', $request->email)->first();

        // Check if account is locked
        if ($user->is_locked) {
            throw new AccountLockedException();
        }
        $tokens = $this->generateTokens($user);

        $data = [
            'user' => $user,
            'token' => $tokens['accessToken']
        ];

        if (!$user->hasVerifiedEmail()) {
            throw new UnverifiedUserException('', $data);
        }

        return $this->sendResponseWithTokens($tokens, ['user' => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logged out successfully');
    }

    public function handleSocialCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();

            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                // Check if account is locked
                if ($existingUser->is_locked) {
                    throw new AccountLockedException();
                }
            } else {
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(), // Mark SSO users as verified
                ]);
            }
            $currentUser = $existingUser ?? $newUser;
            $tokens = $this->generateTokens($currentUser);

            return $this->sendResponseWithTokens($tokens, ['user' => $currentUser]);
        } catch (AccountLockedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new InvalidCredentialsException();
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return $this->successResponse($user, 'User fetched successfully');
    }

    public function refresh(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the token in the database
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $user = $accessToken->tokenable;

        // Revoke the current token
        $accessToken->delete();
        $tokens = $this->generateTokens($user);
        return $this->sendResponseWithTokens($tokens, ['user' => $user]);
    }
}
