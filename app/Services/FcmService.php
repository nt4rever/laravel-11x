<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FcmService
{
    private function exchangeAccessToken()
    {
        $key = config('firebase.credentials.private_key');

        $payload = [
            'iss' => config('firebase.credentials.client_email'),
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => now()->addHour()->timestamp,
            'iat' => now()->timestamp
        ];

        $jwt = JWT::encode($payload, $key, 'RS256');

        $response = Http::asJson()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error when exchange access token.');
        }

        return $response->json('access_token');
    }

    public function send($data, $token)
    {
        $accessToken = Cache::remember('fcm_access_token', 3500, function () {
            return $this->exchangeAccessToken();
        });

        $projectId = config('firebase.credentials.project_id');

        $response = Http::asJson()
            ->withHeaders([
                'Authorization' => "Bearer $accessToken",
            ])
            ->post(
                "https://fcm.googleapis.com/v1/projects/$projectId/messages:send",
                [
                    "message" => [
                        "token" => $token,
                        "data" => $data,
                    ]
                ]
            );

        return $response->json();
    }
}
