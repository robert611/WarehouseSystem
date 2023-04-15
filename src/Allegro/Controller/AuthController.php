<?php

namespace App\Allegro\Controller;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Repository\AllegroAccountRepository;
use App\Allegro\Request\Token\TokenRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/allegro/auth')]
class AuthController extends AbstractController
{
    public function __construct(
        private readonly AllegroAccountRepository $allegroAccountRepository,
        private readonly TokenRequest $tokenRequest,
    ){
    }

    #[Route('/index', name: 'allegro_auth_index', methods: ['GET'])]
    public function index(): Response
    {
        $allegroAccounts = $this->allegroAccountRepository->findBy(['isSandbox' => false]);

        return $this->render('allegro/auth/account_list.html.twig', [
            'allegroAccounts' => $allegroAccounts,
        ]);
    }

    #[Route('/authorize/account/{account}', name: 'allegro_auth_authorize_account', methods: ['GET'])]
    public function authorizeAccount(AllegroAccount $account): Response
    {
        $response = $this->tokenRequest->getDeviceCode($account->getClientId(), $account->getBasicToken());

        if (!isset($response['device_code'])) {
            return new JsonResponse(['status' => 'error', 'message' => $response['error'] ?? 'Unknown error']);
        }

        $account->updateDeviceCode($response['device_code'], $response['expires_in']);
        $this->allegroAccountRepository->add($account, true);

        return new JsonResponse([
            'verification_uri' => $response['verification_uri_complete'],
        ]);
    }

    #[Route('/get/refresh/token/{account}', name: 'allegro_auth_get_refresh_token', methods: ['GET'])]
    public function getRefreshToken(AllegroAccount $account): Response
    {
        $response = $this->tokenRequest->getRefreshToken($account->getDeviceCode(), $account->getBasicToken());

        if (!isset($response['refresh_token'])) {
            return new JsonResponse(['status' => 'error']);
        }

        $account->updateRefreshToken(
            $response['refresh_token'],
            $response['access_token'],
            $response['expires_in'],
        );

        $this->allegroAccountRepository->add($account, true);

        return new JsonResponse(['status' => 'success']);
    }
}