<?php

namespace App\Warehouse\Message;

class ConfigureLeafItems
{
    private readonly int $nodeId;
    private readonly int $capacity;

    public function __construct(int $nodeId, int $capacity)
    {
        $this->nodeId = $nodeId;
        $this->capacity = $capacity;
    }

    public function getNodeId(): int
    {
        return $this->nodeId;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }
}