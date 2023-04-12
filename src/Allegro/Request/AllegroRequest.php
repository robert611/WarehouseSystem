<?php

namespace App\Allegro\Request;

abstract class AllegroRequest
{
    protected function logResponseErrors(array $response): void
    {
        if (array_key_exists('error', $response) || array_key_exists('errors', $response)) {
            $stringify = var_export($response, true);
            $this->allegroLogger->critical(
                sprintf('Failed refreshing token: %s', $stringify)
            );
        }
    }
}