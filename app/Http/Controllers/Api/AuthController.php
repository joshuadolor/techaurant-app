<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exceptions\UnverifiedUserException;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\AccountLockedException;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Laravel\Socialite\Facades\Socialite;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    use ApiResponse;

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

        $token = $user->createToken('auth-token')->plainTextToken;

        $data = [
            'token' => $token,
            'user' => $user
        ];

        if (!$user->hasVerifiedEmail()) {
            throw new UnverifiedUserException('', $data);
        }

        return $this->successResponse($data, 'Login successful');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logged out successfully');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->successResponse(null, 'Password reset link sent to your email');
        }

        return $this->errorResponse(trans($status), 400);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->successResponse(null, 'Password reset successfully');
        }

        return $this->errorResponse(trans($status), 400);
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

                $token = $existingUser->createToken('auth-token')->plainTextToken;
            } else {
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(), // Mark SSO users as verified
                ]);

                $token = $newUser->createToken('auth-token')->plainTextToken;
            }

            return $this->successResponse([
                'token' => $token,
                'user' => $existingUser ?? $newUser
            ], 'Social login successful');
        } catch (AccountLockedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new InvalidCredentialsException();
        }
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verifyEmail(Request $request, $token)
    {
        try {
            // Decode and verify the JWT token
            $decoded = JWT::decode($token, new Key(config('app.key'), 'HS256'));

            // Find user by email
            $user = User::where('email', $decoded->email)->first();

            if (!$user) {
                return redirect(config('app.url') . '/email/resend-verification?msg=Invalid+verification+link');
            }

            if ($user->hasVerifiedEmail()) {
                return redirect(config('app.url') . '?msg=Already+verified');
            }

            $user->markEmailAsVerified();

            return redirect(config('app.url') . '?msg=Email+verified');
        } catch (\Exception $e) {
            return $this->errorResponse('Invalid verification link', 400);
        }
    }

    /**
     * Resend the email verification notification.
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->successResponse(null, 'Email already verified');
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->successResponse(null, 'Verification link sent');
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return $this->successResponse($user, 'User fetched successfully');
    }
}
