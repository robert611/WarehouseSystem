<?php

namespace App\Allegro\Request\Token;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Model\Endpoint;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TokenRequest
{
    private HttpClientInterface $allegroClient;

    public function __construct(HttpClientInterface $allegroClient)
    {
        $this->allegroClient = $allegroClient;
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
}