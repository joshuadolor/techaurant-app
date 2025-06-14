<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\SendsTokenResponses;
use App\Traits\ApiResponse;

class SocialAuthController extends Controller
{
    use SendsTokenResponses, ApiResponse;

    public function redirect($provider)
    {
        return response()->json([
            'url' => Socialite::driver($provider)
                ->stateless()
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();

            $user = User::updateOrCreate([
                'email' => $socialUser->email,
            ], [
                'name' => $socialUser->name,
                'password' => bcrypt(Str::random(16)),
                $provider . '_id' => $socialUser->id,
            ]);

            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            Auth::login($user);

            $tokens = $this->generateTokens($user);

            return $this->sendResponseWithTokens($tokens, [
                'user' => $user,
                'status' => 'success'
            ]);

            
        } catch (\Exception $e) {
            return $this->errorResponse('Authentication failed', 422, $e->getMessage());
        }
    }
}
