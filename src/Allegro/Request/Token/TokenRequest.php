<?php

namespace App\Allegro\Request\Token;

use App\Allegro\Model\Endpoint;
use App\Allegro\Request\AllegroRequest;
use App\Allegro\Service\Auth\AuthService;
use App\Shared\Enum\HttpMethodEnum;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class TokenRequest extends AllegroRequest
{
    public function __construct(
        protected readonly HttpClientInterface $allegroClient,
        protected readonly LoggerInterface $allegroLogger,
        private readonly UrlGeneratorInterface $router,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Throwable
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
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

        try {
            $response = $this->allegroClient->request('POST', Endpoint::TOKEN, $options);
            $content = json_decode($response->getContent(false), true);
        } catch (Throwable $e) {
            $this->allegroLogger->critical($e->getMessage());

            throw new $e;
        }

        return $content;
    }

    /**
     * @return array{access_token: string, refresh_token: string, expires_in: int}
     */
    public function getRefreshToken(string $deviceCode, string $basicToken): array
    {
        $parameters = [
            'grant_type' => AuthService::DEVICE_GRANT_TYPE,
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