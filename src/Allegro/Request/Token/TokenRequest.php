<?php

namespace App\Allegro\Request\Token;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Model\Endpoint;
use App\Allegro\Service\Auth\AuthService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class TokenRequest
{
    public function __construct(
        private readonly HttpClientInterface $allegroClient,
        private readonly UrlGeneratorInterface $router,
        private readonly LoggerInterface $allegroLogger,
    ){
    }

    public function getAccessToken(AllegroAccount $allegroAccount): void
    {
        $parameters = [
            'grant_type' => 'authorization_code',
            'code' => '',
            'redirect_uri' => '',
            'code_verifier' => '',
        ];

        try {
            $response = $this->allegroClient->request('POST', Endpoint::TOKEN, $parameters);
        } catch (TransportExceptionInterface $e) {

        }
    }

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
            $content = json_decode($response->getContent(false), true); // False makes it return true response instead of exception!
        } catch (Throwable $e) {
            $this->allegroLogger->critical($e->getMessage());

            return ['error' => 'Internal error'];
        }

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

    public function getSandboxAccessToken(AllegroAccount $account)
    {
        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . $account->getBasicToken(),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'client_credentials',
            ],
        ];

        try {
            $response = $this->allegroClient->request('POST', Endpoint::TOKEN, $options);
            $content = json_decode($response->getContent(false), true); // False makes it return true response instead of exception!
        } catch (Throwable $e) {
            $this->allegroLogger->critical($e->getMessage());

            return ['error' => 'Internal error'];
        }

        return $content;
    }
}