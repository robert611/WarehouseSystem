<?php

namespace App\Allegro\Controller;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Model\Endpoint;
use App\Allegro\Repository\AllegroAccountRepository;
use App\Allegro\Service\Auth\AuthService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/allegro/auth')]
class AuthController extends AbstractController
{
    private AllegroAccountRepository $allegroAccountRepository;

    public function __construct(AllegroAccountRepository $allegroAccountRepository)
    {
        $this->allegroAccountRepository = $allegroAccountRepository;
    }

    #[Route('/index', name: 'allegro_auth_index', methods: ['GET'])]
    public function index(): Response
    {
        $allegroAccounts = $this->allegroAccountRepository->findAll();

        return $this->render('allegro/auth/account_list.html.twig', [
            'allegroAccounts' => $allegroAccounts,
        ]);
    }

    #[Route('/authorize/account/{allegroAccount}', name: 'allegro_auth_authorize_account', methods: ['GET'])]
    public function authorizeAccount(AllegroAccount $allegroAccount, AuthService $authService): Response
    {
        try {
            $codeVerifier = $authService->generateCodeVerifier();
        } catch (Exception $e) {
            return new Response($e->getMessage(), 500);
        }

        $codeChallenge = $authService->generateCodeChallenge($codeVerifier);

        return new JsonResponse([
            'auth_url' => Endpoint::AUTH_URL,
            'response_type' => AuthService::RESPONSE_TYPE,
            'client_id' => $allegroAccount->getClientId(),
            'redirect_uri' => $this->generateUrl('allegro_auth_redirect_page'),
            'code_challenge_method' => AuthService::CODE_CHALLENGE_METHOD,
            'code_challenge' => $codeChallenge,
        ]);
    }

    #[Route('/redirect/page', name: 'allegro_auth_redirect_page', methods: ['GET'])]
    public function redirectPage(Request $request)
    {
        $hey = 2;
    }
}