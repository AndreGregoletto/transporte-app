<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OlhoVivoServices
{
    private $apiToken;
    private $baseUrl;
    private $urlLogin;
    private $urlItinerarios;
    private $isAuthenticated = false;
    private $httpClient;

    public function __construct()
    {
        $this->apiToken       = env('OLHO_VIVO_TOKEN');
        $this->baseUrl        = env('OLHO_VIVO_URL');
        $this->urlLogin       = env('OLHO_VIVO_LOGIN');
        $this->urlItinerarios = env('OLHO_VIVO_ITINERARIO');

        $this->httpClient = Http::withOptions([
            'cookies' => new \GuzzleHttp\Cookie\CookieJar(),
        ]);

        $this->authenticate();
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }

    private function authenticate()
    {
        $response = $this->httpClient->post("{$this->baseUrl}{$this->urlLogin}?token={$this->apiToken}");

        if ($response->json() === true) {
            $this->isAuthenticated = true;
        } else {
            throw new \Exception('Falha na autenticação com a API Olho Vivo');
        }
    }

    public function getLines($iLine)
    {
        if (!$this->isAuthenticated) {
            $this->authenticate();
        }

        $response = $this->httpClient->get("{$this->baseUrl}{$this->urlItinerarios}?termosBusca={$iLine}");

        if ($response->status() !== 200) {
            throw new \Exception('Failed to fetch data from Olho Vivo API: ' . $response->body());
        }

        return $response->json();
    }
}