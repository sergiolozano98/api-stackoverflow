<?php

namespace App\Tests\src\Shared\Infrastructure\Client;

use App\Shared\Domain\Client\ClientException;
use App\Shared\Domain\Client\EndpointInterface;
use App\Shared\Infrastructure\Client\StackExchangeClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class StackExchangeClientTest extends TestCase
{

    /**
     * @test
     */
    public function its_should_return_data()
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getContent')->willReturn('{"result": "fake data"}');

        $httpClientMock->method('request')->willReturn($responseMock);

        $stackExchangeClient = new StackExchangeClient($httpClientMock);
        $fakeEndpoint = $this->createMock(EndpointInterface::class);

        $result = $stackExchangeClient->request($fakeEndpoint);

        $this->assertEquals(['result' => 'fake data'], $result);
    }

    /**
     * @test
     */
    public function its_should_return_exception_when_client_return_error(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('Fake error'));

        $stackExchangeClient = new StackExchangeClient($httpClientMock);
        $fakeEndpoint = $this->createMock(EndpointInterface::class);

        $this->expectException(ClientException::class);

        $stackExchangeClient->request($fakeEndpoint);
    }
}