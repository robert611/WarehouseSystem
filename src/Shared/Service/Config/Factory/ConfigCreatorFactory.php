<?php

namespace App\Shared\Service\Config\Factory;

use App\Shared\Service\Config\Creator\AbstractConfigCreator;
use App\Shared\Service\Config\Creator\ConfigIntegerCreator;

class ConfigCreatorFactory
{
    private ConfigIntegerCreator $configIntegerCreator;

    public function __construct(ConfigIntegerCreator $configIntegerCreator)
    {
        $this->configIntegerCreator = $configIntegerCreator;
    }

    /* @return array<AbstractConfigCreator> */
    public function getConfigCreators(): array
    {
        return [
            $this->configIntegerCreator,
        ];
    }
}