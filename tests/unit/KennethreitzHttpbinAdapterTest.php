<?php

declare(strict_types=1);

namespace App\Tests;

use App\Infrastructure\Adapter\KennethreitzHttpbinAdapter;
use Codeception\Test\Unit;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class KennethreitzHttpbinAdapterTest extends Unit
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetJson(): void
    {
        $response = [
            'slideshow' => [
                'author' => 'Yours Truly',
                'title' => 'Sample Slide Show',
            ],
        ];

        $mockResponse = new MockResponse(json_encode($response));
        $mockHttpClient = new MockHttpClient($mockResponse);
        $kennethreitzHttpbinAdapter = new KennethreitzHttpbinAdapter($mockHttpClient);

        $json = $kennethreitzHttpbinAdapter->getJson();

        $this->assertSame($json, $response);
    }
}