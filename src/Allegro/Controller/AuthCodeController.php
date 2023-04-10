<?php

namespace App\Allegro\Controller;

use App\Allegro\Entity\AllegroAccount;
use App\Allegro\Model\Endpoint;
use App\Allegro\Repository\AllegroAccountRepository;
use App\Allegro\Request\Token\TokenRequest;
use App\Allegro\Service\Auth\AuthService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/allegro/auth/code')]
class AuthCodeController extends AbstractController
{
    public function __construct(
        private readonly AllegroAccountRepository $allegroAccountRepository,
        private readonly TokenRequest $tokenRequest,
    ){
    }

    #[Route('/index', name: 'allegro_auth_code_index', methods: ['GET'])]
    public function index(): Response
    {
        $allegroAccounts = $this->allegroAccountRepository->findAll();

        return $this->render('allegro/auth/account_list.html.twig', [
            'allegroAccounts' => $allegroAccounts,
        ]);
    }

    #[Route('/authorize/account/{account}', name: 'allegro_auth_code_authorize_account', methods: ['GET'])]
    public function authorizeAccount(AllegroAccount $account, AuthService $authService): Response
    {
        // NOT USED FOR THE TIME BEING
        try {
            $codeVerifier = $authService->generateCodeVerifier();
        } catch (Exception $e) {
            return new Response($e->getMessage(), 500);
        }

        $codeChallenge = $authService->generateCodeChallenge($codeVerifier);
        $redirectUri = $this->generateUrl(
            'allegro_auth_redirect_page',
            ['account' => $account->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse([
            'auth_url' => Endpoint::AUTH_URL,
            'response_type' => AuthService::CODE_RESPONSE_TYPE,
            'client_id' => $account->getClientId(),
            'redirect_uri' => $redirectUri,
            'code_challenge_method' => AuthService::CODE_CHALLENGE_METHOD,
            'code_challenge' => $codeChallenge,
        ]);
    }

    #[Route('/redirect/page/{account}', name: 'allegro_auth_code_redirect_page', methods: ['GET'])]
    public function redirectPage(AllegroAccount $account, Request $request): Response
    {
        // NOT USED FOR THE TIME BEING
        if (false === $request->query->has('code')) {
            return new Response('No code provided', 400);
        }

        $code = $request->query->get('code');

        $response = $this->tokenRequest->getRefreshToken($account, $code);

        if (isset($response['refresh_token'])) {
            $account->updateRefreshToken(
                $response['refresh_token'],
                $response['access_token'],
                $response['expires_in'],
            );

            $this->allegroAccountRepository->add($account, true);

            return new JsonResponse(['status' => 'success', 'message' => 'Refresh token has been updated']);
        }

        return new JsonResponse(['status' => 'error', 'message' => $response['error'] ?? 'Unknown error']);
    }
}