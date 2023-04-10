<?php

namespace App\Allegro\Service\Auth;

use Exception;

class AuthService
{
    public const CODE_CHALLENGE_METHOD = 'S256';
    public const CODE_RESPONSE_TYPE = 'code';
    public const DEVICE_GRANT_TYPE = 'urn:ietf:params:oauth:grant-type:device_code';

    /**
     * @throws Exception
     */
    public function generateCodeVerifier(): string
    {
        $verifierBytes = random_bytes(80);

        return rtrim(strtr(base64_encode($verifierBytes), "+/", "-_"), "=");
    }

    public function generateCodeChallenge(string $codeVerifier): string
    {
        $challengeBytes = hash("sha256", $codeVerifier, true);

        return rtrim(strtr(base64_encode($challengeBytes), "+/", "-_"), "=");
    }
}