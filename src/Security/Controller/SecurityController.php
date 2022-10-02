<?php

namespace App\Security\Controller;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[NoReturn] #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(TranslatorInterface $translator): void
    {
       exit($translator->trans('activate_logout_in_security'));
    }
}
