<?php

declare(strict_types=1);

namespace App\Tests\Unit\Allegro\Request\Token;

use App\Allegro\Request\Token\TokenRequest;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TokenRequestTest extends TestCase
{
    /**
     * @test
     */
    public function canGetAccessToken(): void
    {
        // Given
        $mockResponseArray = ['access_token' => 'test_token'];
        $mockResponseJson = json_encode($mockResponseArray);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
        ]);
        $httpClient = new MockHttpClient($mockResponse, 'https://example.com');

        // and given
        $tokenRequest = $this->getTokenRequestObject($httpClient);
        $basicToken = 'test_basic_token_x';
        $refreshToken = 'test_refresh_token_x';

        // When
        $response = $tokenRequest->getAccessToken($basicToken, $refreshToken);

        // Then
        $headers = $mockResponse->getRequestOptions()['headers'];
        $query = $mockResponse->getRequestOptions()['query'];

        self::assertSame('POST', $mockResponse->getRequestMethod());
        self::assertSame($response, $mockResponseArray);
        self::assertTrue(str_contains($mockResponse->getRequestUrl(), 'https://example.com/auth/oauth/token'));
        self::assertSame('refresh_token', $query['grant_type']);
        self::assertSame('test_refresh_token_x', $query['refresh_token']);
        self::assertSame('Authorization: Basic test_basic_token_x', $headers[0]);
    }

    public function getTokenRequestObject(MockHttpClient $httpClient): TokenRequest
    {
        $allegroLogger = $this->createMock(LoggerInterface::class);
        $router = $this->createMock(UrlGeneratorInterface::class);

        return new TokenRequest($httpClient, $allegroLogger, $router);
    }
}
