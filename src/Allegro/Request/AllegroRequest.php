<?php

namespace App\Allegro\Request;

use App\Shared\Enum\HttpMethodEnum;
use Throwable;

abstract class AllegroRequest
{
    protected function makeRequest(HttpMethodEnum $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->allegroClient->request($method->toString(), $endpoint, $options);
            $content = $response->toArray(false);
        } catch (Throwable $e) {
            $this->allegroLogger->critical($e->getMessage());

            return ['error' => 'Internal error'];
        }

        $this->logResponseErrors($content, $endpoint, $options);

        return $content;
    }

    protected function logResponseErrors(array $response, string $endpoint, array $options): void
    {
        if (array_key_exists('error', $response) || array_key_exists('errors', $response)) {
            $stringify = var_export($response, true);
            $stringifyOptions = var_export($options, true);
            $this->allegroLogger->critical(sprintf(
                'Request returned errors: %s, for endpoint: %s, %s',
                $stringify,
                $endpoint,
                $stringifyOptions,
            ));
        }
    }
}