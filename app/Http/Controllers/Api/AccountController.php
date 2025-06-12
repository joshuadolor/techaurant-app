<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\Account\ForgotPasswordRequest;
use App\Http\Requests\Api\Account\ResetPasswordRequest;
use App\Http\Requests\Api\Account\ChangePasswordRequest;
use App\Http\Requests\Api\Account\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ApiResponse;

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->successResponse(null, 'Password reset link sent successfully!');
        }

        return $this->errorResponse('Unable to send reset link', 400);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->successResponse(null, 'Password reset successfully');
        }

        return $this->errorResponse('Unable to reset password', 400);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\Api\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAccount(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // TODO: Dispatch the event
        //event(new UserHasRegistered($user));

        $user->sendEmailVerificationNotification();

        return $this->successResponse([
            'user' => $user
        ], 'Registration successful', 201);
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

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->errorResponse('Current password is incorrect', 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->successResponse(null, 'Password changed successfully');
    }
}
