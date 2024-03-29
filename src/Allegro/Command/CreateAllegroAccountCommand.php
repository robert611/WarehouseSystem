<?php

namespace App\Allegro\Command;

use App\Allegro\Entity\AllegroAccount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'allegro:create-allegro-account')]
class CreateAllegroAccountCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the allegro account')
            ->addArgument('clientId', InputArgument::REQUIRED, 'Client id of the allegro account')
            ->addArgument('clientSecret', InputArgument::REQUIRED, 'Client secret of the allegro account')
            ->addArgument('isSandbox', InputArgument::REQUIRED, 'Is a sandbox account?');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        [$name, $clientId, $clientSecret, $isSandbox] = [
            $input->getArgument('name'),
            $input->getArgument('clientId'),
            $input->getArgument('clientSecret'),
            $input->getArgument('isSandbox'),
        ];

        $allegroAccount = AllegroAccount::from($name, $clientId, $clientSecret, $isSandbox, true);

        $this->entityManager->persist($allegroAccount);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}