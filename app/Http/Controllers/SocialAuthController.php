<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
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
                $provider.'_id' => $socialUser->id,
            ]);

            Auth::login($user);
            
            // Create token for API authentication
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'user' => $user,
                'token' => $token,
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Authentication failed',
                'message' => $e->getMessage()
            ], 422);
        }
    }
} 