<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\SendsTokenResponses;
use App\Traits\ApiResponse;
use App\Enums\SocialProvider;

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

    public function callback(SocialProvider $provider)
    {
        try {
            $provider = $provider->value;
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();

            $user = User::where('email', $socialUser->email)->first();

            $data = [
                'name' => $socialUser->name,
            ];

            if (!$user) {
                $data['password'] = bcrypt(Str::random(16));
                $data[$provider . '_id'] = $socialUser->id;
                
                $user = User::create($data);
            } else {

                $currentPassword = $user->password;
                if(!$currentPassword) {
                    $data['password'] = bcrypt(Str::random(16));
                }
                $hasProviderId = $user->{$provider . '_id'};

                if(!$hasProviderId) {
                    $data[$provider . '_id'] = $socialUser->id;
                }

                $data['email'] = $socialUser->email;
                
                $user->update($data);
            }
           

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
