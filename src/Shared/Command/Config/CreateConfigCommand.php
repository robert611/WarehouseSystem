<?php

namespace App\Shared\Command\Config;

use App\Shared\Service\Config\Factory\ConfigCreatorFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'shared:create-config')]
class CreateConfigCommand extends Command
{
    private ConfigCreatorFactory $configCreatorFactory;

    public function __construct(ConfigCreatorFactory $configCreatorFactory)
    {
        parent::__construct();
        $this->configCreatorFactory = $configCreatorFactory;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configCreators = $this->configCreatorFactory->getConfigCreators();

        foreach ($configCreators as $configCreator) {
            $configCreator->createConfigEntriesIfNotExists();
        }

        return Command::SUCCESS;
    }
}