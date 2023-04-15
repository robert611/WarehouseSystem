<?php

namespace App\Allegro\Request\Token;

use App\Allegro\Model\Endpoint;
use App\Allegro\Request\AllegroRequest;
use App\Shared\Enum\HttpMethodEnum;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TokenRequest extends AllegroRequest
{
    public function __construct(
        protected readonly HttpClientInterface $allegroClient,
        protected readonly LoggerInterface $allegroLogger,
        private readonly UrlGeneratorInterface $router,
    ) {
    }

    /**
     * @return array{access_token: string, refresh_token: string, expires_in: int}
     */
    public function getAccessToken(string $basicToken, string $refreshToken): array
    {
        $parameters = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'redirect_uri' => $this->router->generate('app_index'),
        ];

        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . $basicToken,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'query' => $parameters,
        ];

        return $this->makeRequest(HttpMethodEnum::POST, Endpoint::TOKEN, $options);
    }

    /**
     * @return array{access_token: string, refresh_token: string, expires_in: int}
     */
    public function getRefreshToken(string $deviceCode, string $basicToken): array
    {
        $parameters = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:device_code',
            'device_code' => $deviceCode,
        ];

        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . $basicToken,
            ],
            'query' => $parameters,
        ];

        return $this->makeRequest(HttpMethodEnum::POST, Endpoint::TOKEN, $options);
    }

    public function getDeviceCode(string $clientId, string $basicToken): array
    {
        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . $basicToken,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => null,
                'ContentType' => null,
            ],
            'body' => [
                'client_id' => $clientId,
            ],
        ];

        return $this->makeRequest(HttpMethodEnum::POST, Endpoint::DEVICE_AUTH, $options);
    }

    /**
     * @return array{access_token: string, refresh_token: string, expires_in: int}
     */
    public function getSandboxAccessToken(string $basicToken): array
    {
        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . $basicToken,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'client_credentials',
            ],
        ];

        return $this->makeRequest(HttpMethodEnum::POST, Endpoint::TOKEN, $options);
    }
}