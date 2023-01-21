<?php

namespace App\Allegro\Command\Token;

use App\Allegro\Repository\AllegroAccountRepository;
use App\Allegro\Service\Token\TokenService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'allegro:refresh-token')]
class RefreshTokenCommand extends Command
{
    private TokenService $tokenService;
    private AllegroAccountRepository $allegroAccountRepository;

    public function __construct(TokenService $tokenService, AllegroAccountRepository $allegroAccountRepository)
    {
        parent::__construct();
        $this->tokenService = $tokenService;
        $this->allegroAccountRepository = $allegroAccountRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $allegroAccounts = $this->allegroAccountRepository->findBy(['active' => 1]);

        foreach ($allegroAccounts as $allegroAccount) {
            $this->tokenService->refreshAllegroToken($allegroAccount);
        }

        return Command::SUCCESS;
    }
}