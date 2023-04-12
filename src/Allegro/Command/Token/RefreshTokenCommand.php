<?php

namespace App\Allegro\Command\Token;

use App\Allegro\Repository\AllegroAccountRepository;
use App\Allegro\Service\Token\TokenService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(name: 'allegro:refresh-token')]
class RefreshTokenCommand extends Command
{
    public function __construct(
        private readonly TokenService $tokenService,
        private readonly AllegroAccountRepository $allegroAccountRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this->allegroAccountRepository->findBy(['active' => 1]);

        foreach ($accounts as $account) {
            try {
                $this->tokenService->refreshAccessToken($account);
            } catch (Throwable) {
                $output->writeln(sprintf('Refreshing access token failed for account: %s', $account->getName()));

                continue;
            }

            $output->writeln(sprintf('Token has been successfully refreshed for account: %s', $account->getName()));
        }

        return Command::SUCCESS;
    }
}