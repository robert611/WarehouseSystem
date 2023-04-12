<?php

namespace App\Allegro\Service\Token;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Request\Token\TokenRequest;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

class TokenService
{
    public function __construct(
        private readonly TokenRequest $tokenRequest,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $allegroLogger,
    ) {
    }

    /**
     * @throws Throwable
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function refreshAccessToken(AllegroAccount $account): void
    {
        $basicToken = $account->getBasicToken();

        if ($account->isSandbox()) {
            $response = $this->tokenRequest->getSandboxAccessToken($basicToken);
        } else {
            $response = $this->tokenRequest->getAccessToken($basicToken, $account->getRefreshToken());
        }

        if (false === array_key_exists('access_token', $response)) {
            $stringify = var_export($response, true);
            $this->allegroLogger->critical(
                sprintf('Failed refreshing token: %s, %s', $account->getName(), $stringify)
            );

            throw new Exception();
        }

        $account->updateRefreshToken(
            $response['refresh_token'],
            $response['access_token'],
            $response['expires_in'],
        );

        $this->entityManager->flush();
    }
}
