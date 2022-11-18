<?php

namespace App\Shared\Service\Config;

use App\Shared\Repository\ConfigIntegerRepository;

class ConfigService
{
    private ConfigIntegerRepository $configIntegerRepository;

    public function __construct(ConfigIntegerRepository $configIntegerRepository)
    {
        $this->configIntegerRepository = $configIntegerRepository;
    }

    public function getConfigValue(string $name): ?int
    {
        if ($configInteger = $this->configIntegerRepository->findOneBy(['name' => $name])) {
            return $configInteger->getValue();
        }

        return null;
    }
}