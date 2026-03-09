<?php
namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class FcmV1Service
{
    protected function getAccessToken()
    {
        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            storage_path('app/firebase/service-account.json')
        );

        $token = $credentials->fetchAuthToken();
        return $token['access_token'];
    }

    public function sendToToken(string $token, string $title, string $body, string $url)
    {
        $accessToken = $this->getAccessToken();
        $projectId = config('services.firebase.project_id');

        $response = Http::withToken($accessToken)
            ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $title,
                        "body"  => $body,
                    ],
                    "data" => [
                        "url" => $url
                    ],
                    "webpush" => [
                        "fcm_options" => [
                            "link" => $url
                        ]
                    ]
                ]
            ]);
        /** @var Response $response */
        return $response->json();
    }
}