<?php

namespace App\Allegro\Controller;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Repository\AllegroAccountRepository;
use App\Allegro\Request\Token\TokenRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/allegro/auth/device')]
class AuthDeviceController extends AbstractController
{
    public function __construct(
        private readonly AllegroAccountRepository $allegroAccountRepository,
        private readonly TokenRequest $tokenRequest,
    ){
    }

    #[Route('/index', name: 'allegro_auth_device_index', methods: ['GET'])]
    public function index(): Response
    {
        $allegroAccounts = $this->allegroAccountRepository->findBy(['isSandbox' => false]);

        return $this->render('allegro/auth/account_list.html.twig', [
            'allegroAccounts' => $allegroAccounts,
        ]);
    }

    #[Route('/authorize/account/{account}', name: 'allegro_auth_device_authorize_account', methods: ['GET'])]
    public function authorizeAccount(AllegroAccount $account): Response
    {
        $response = $this->tokenRequest->getDeviceCode($account);

        if (!isset($response['device_code'])) {
            return new JsonResponse(['status' => 'error', 'message' => $response['error'] ?? 'Unknown error']);
        }

        $account->setDeviceCode($response['device_code']);
        $this->allegroAccountRepository->add($account, true);

        return new JsonResponse([
            'verification_uri' => $response['verification_uri_complete'],
        ]);
    }
}