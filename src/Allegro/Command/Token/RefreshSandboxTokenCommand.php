<?php

namespace App\Allegro\Command\Token;

use App\Allegro\Repository\AllegroAccountRepository;
use App\Allegro\Request\Token\TokenRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'allegro:refresh-sandbox-token')]
class RefreshSandboxTokenCommand extends Command
{
    public function __construct(
        private readonly TokenRequest $tokenRequest,
        private readonly AllegroAccountRepository $allegroAccountRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this->allegroAccountRepository->findBy(['active' => 1, 'isSandbox' => 1]);

        foreach ($accounts as $account) {
            $response = $this->tokenRequest->getSandboxAccessToken($account->getBasicToken());

            if (false === array_key_exists('access_token', $response)) {
                $output->writeln(sprintf('Refreshing access token failed for account: %s', $account->getName()));
                continue;
            }

            $account->updateAccessToken(
                $response['access_token'],
                $response['expires_in'],
            );

            $output->writeln(sprintf('Token has been successfully refreshed for account: %s', $account->getName()));
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}