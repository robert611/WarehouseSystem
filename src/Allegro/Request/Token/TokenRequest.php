<?php

namespace App\Allegro\Request\Token;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Model\Endpoint;
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
            'redirect_uri' => $allegroAccount->getRedirectUri(),
            'code_verifier' => '',
        ];

        try {
            $response = $this->allegroClient->request('POST', Endpoint::TOKEN_URL, $parameters);
        } catch (TransportExceptionInterface $e) {

        }
    }

    /**
     * Requires special "code" that is returned by allegro after account owner accepted third party application
     * to manage his account
     * Returns json array including refresh token valid for three months, that can be used to update access token
     */
    public function getRefreshToken(AllegroAccount $account, string $code): array
    {
        $parameters = [
            'grant_type' => 'authorization_code',
            'code' => $code,
//            'redirect_uri' => $this->router->generate(
//                'allegro_auth_redirect_page',
//                ['account' => $account->getId()],
//                UrlGeneratorInterface::ABSOLUTE_URL,
//            ),
        ];

        $parameters['redirect_uri'] = Endpoint::AUTH_REDIRECT_URL; // For some reason allegro does not accept uris

        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . $account->getBasicToken(),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'query' => $parameters,
        ];

        try {
            $response = $this->allegroClient->request('POST', Endpoint::TOKEN_URL, $options);
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