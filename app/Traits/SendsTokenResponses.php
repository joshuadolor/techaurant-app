<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use App\Enums\TokenAbility;
use App\Traits\ApiResponse;

trait SendsTokenResponses
{
    use ApiResponse;
    /**
     * @return array{
     *     accessToken: string,
     *     refreshToken: string,
     * }
     */
    public function generateTokens($user): array
    {
        $atExpireTime = now()->addMinutes(config('sanctum.expiration'));
        $rtExpireTime = now()->addMinutes(config('sanctum.refresh_token_expiration'));

        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API], $atExpireTime);
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN], $rtExpireTime);

        return [
            'accessToken' => $accessToken->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
        ];
    }

    protected function sendResponseWithTokens(array $tokens, $body = []): JsonResponse
    {
        $rtExpireTime = config('sanctum.refresh_token_expiration');
        $cookie = cookie('refreshToken', $tokens['refreshToken'], $rtExpireTime, secure: true);

        return $this->successResponse(array_merge($body, [
            'token' => $tokens['accessToken']
        ]))->withCookie($cookie);
    }
} 