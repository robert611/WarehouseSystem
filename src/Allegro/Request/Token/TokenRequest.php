<?php

namespace App\Allegro\Request\Token;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Model\Endpoint;
use App\Allegro\Request\AllegroRequest;
use App\Allegro\Service\Auth\AuthService;
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
        private readonly HttpClientInterface $allegroClient,
        private readonly UrlGeneratorInterface $router,
        protected readonly LoggerInterface $allegroLogger,
    ){
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
    public function getRefreshToken(AllegroAccount $account): array
    {
        $parameters = [
            'grant_type' => AuthService::DEVICE_GRANT_TYPE,
            'device_code' => $account->getDeviceCode(),
        ];

        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . $account->getBasicToken(),
            ],
            'query' => $parameters,
        ];

        try {
            $response = $this->allegroClient->request('POST', Endpoint::TOKEN, $options);
            $content = json_decode($response->getContent(false), true);
        } catch (Throwable $e) {
            $this->allegroLogger->critical($e->getMessage());

            return ['error' => 'Internal error'];
        }

        $this->logResponseErrors($content);

        return $content;
    }

    public function getDeviceCode(AllegroAccount $account): array
    {
        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . $account->getBasicToken(),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'query' => [
                'client_id' => $account->getClientId(),
            ],
        ];

        try {
            $response = $this->allegroClient->request('POST', Endpoint::DEVICE_AUTH, $options);
            $content = json_decode($response->getContent(false), true); // False makes it return true response instead of exception!
        } catch (Throwable $e) {
            $this->allegroLogger->critical($e->getMessage());

            return ['error' => 'Internal error'];
        }

        return $content;
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

        try {
            $response = $this->allegroClient->request('POST', Endpoint::TOKEN, $options);
            $content = json_decode($response->getContent(false), true);
        } catch (Throwable $e) {
            $this->allegroLogger->critical($e->getMessage());

            return ['error' => 'Internal error'];
        }

        return $content;
    }
}