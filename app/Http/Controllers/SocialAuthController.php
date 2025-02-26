<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = User::updateOrCreate([
                'email' => $socialUser->email,
            ], [
                'name' => $socialUser->name,
                'password' => bcrypt(str_random(16)),
                $provider.'_id' => $socialUser->id,
            ]);

            Auth::login($user);
            
            return redirect('/dashboard');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Social login failed');
        }
    }
} 