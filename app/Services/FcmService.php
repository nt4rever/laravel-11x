<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Http\Client\Pool;
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
            'iat' => now()->timestamp,
        ];

        $jwt = JWT::encode($payload, $key, 'RS256');

        $response = Http::asJson()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        $_ = function ($c) {
            return $c;
        };

        if ($response->failed()) {
            logger()->error("Oauth2 Google outcome: {$_($response->reason())} ", [$response->json()]);
            throw new \Exception('Error when exchange access token.');
        }

        return $response->json('access_token');
    }

    public function send($token, $data = ['message' => 'Test'])
    {
        // TODO: Invalidate cache when change environment (ex: dev => prod).
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
                    'message' => [
                        'token' => $token,
                        'data' => $data,
                    ],
                ]
            );

        return [
            'token' => $token,
            'outcome' => $response->successful() ? 'SUCCESS' : 'FAIL',
            'response' => $response->json(),
        ];
    }

    public function sendBatchPool(array $tokens, $data = ['message' => 'Test'])
    {
        // TODO: Invalidate cache when change environment (ex: dev => prod).
        $accessToken = Cache::remember('fcm_access_token', 3500, function () {
            return $this->exchangeAccessToken();
        });

        $projectId = config('firebase.credentials.project_id');

        $responses = Http::pool(
            fn(Pool $pool) => collect($tokens)->map(
                fn($token) => $pool->asJson()
                    ->withHeaders([
                        'Authorization' => "Bearer $accessToken",
                    ])
                    ->post(
                        "https://fcm.googleapis.com/v1/projects/$projectId/messages:send",
                        [

                            'message' => [
                                'token' => $token,
                                // 'notification' => [
                                //     'title' => 'CLOCKY',
                                //     'body' => 'Test'
                                // ],
                                'data' => $data,
                            ],
                        ]
                    )
            )
        );

        $results = [];

        foreach ($responses as $index => $response) {
            $results[] = [
                'token' => $tokens[$index],
                'outcome' => $response->successful() ? 'SUCCESS' : 'FAIL',
                'response' => $response->json(),
            ];
        }

        return $results;
    }
}
