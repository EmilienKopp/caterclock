<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LineAuthService
{
    public static function getConfig() {
        return [
            'client_id' => env('LINE_CLIENT_ID'),
            'client_secret' => env('LINE_CLIENT_SECRET'),
            'redirect' => env('LINE_REDIRECT'),
            'auth_url' => env('LINE_BASE_URL'),
            'token_url' => env('LINE_TOKEN_URL'),
            'verify_url' => env('LINE_VERIFY_URL'),
            'response_type' => 'code',
            'scope' => 'profile openid email',
        ];
    }

    private $authUrl;
    private $state;
    private $score;
    private $responseType;
    private $clientId;
    private $clientSecret;
    private $redirect;
    private $scope;
    private $tokenUrl;
    private $verifyUrl;

    private function __construct(string $url, string $state) {
        $config = self::getConfig();
        $this->authUrl = $url;
        $this->state = $state;
        $this->responseType = $config['response_type'];
        $this->clientId = $config['client_id'];
        $this->redirect = $config['redirect'];
        $this->scope = $config['scope'];
        $this->clientSecret = $config['client_secret'];
        $this->tokenUrl = $config['token_url'];
        $this->verifyUrl = $config['verify_url'];
    }

    public static function initiate()
    {
        $state = session('line_oauth_state') ?? bin2hex(random_bytes(12));
        $config = self::getConfig();
        Log::debug("initiating line auth with state: $state");
        // Store state in session or cookie for later CSRF verification
        session(['line_oauth_state' => $state]);

        // Construct the authorization URL
        $params = http_build_query([
            'response_type' => $config['response_type'],
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $config['redirect'],
            'scope' => $config['scope'],
            'state' => $state,
        ]);

        $authUrl = $config['auth_url'] . "?" . $params;
        
        Log::debug("auth url: $authUrl");
        return new LineAuthService($authUrl,$state);
    }

    public function redirect()
    {
        return redirect()->to($this->authUrl);
    }

    public function user() {
        $state = session('line_oauth_state');

        if (!$state || $state !== request('state')) {
            return redirect()->route('login')->with('error', 'Invalid state');
        }

        $response = Http::asForm()
            ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
            ->post($this->tokenUrl, [
                'grant_type' => 'authorization_code',
                'code' => request('code'),
                'redirect_uri' => $this->redirect,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

        if ($response->failed()) {
            return redirect()->route('login')->with('error', 'Failed to authenticate');
        }

        $line_user = $response->json();

        // Fetch user profile
        $profile = Http::withHeaders(['Content-Type' => 'x-www-form-urlencoded'])
            ->asForm()
            ->post($this->verifyUrl, [
                'id_token' => $line_user['id_token'],
                'client_id' => $this->clientId,
            ]);

        if ($profile->failed()) {
            return redirect()->route('login')->with('error', 'Failed to fetch user profile');
        }

        $user = $profile->json();

        return $user;
    }
}