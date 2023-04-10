<?php

namespace App\Allegro\Service\Token;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Request\Token\TokenRequest;

class TokenService
{
    private TokenRequest $tokenRequest;

    public function __construct(TokenRequest $tokenRequest)
    {
        $this->tokenRequest = $tokenRequest;
    }
    
    public function refreshAllegroToken(AllegroAccount $allegroAccount): void
    {
        $this->tokenRequest->getAccessToken($allegroAccount);
    }
}
