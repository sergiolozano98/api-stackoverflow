<?php

namespace App\Shared\Infrastructure\Client;

use App\Shared\Domain\Client\ClientException;
use App\Shared\Domain\Client\ClientInterface;
use App\Shared\Domain\Client\EndpointInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


readonly class StackExchangeClient implements ClientInterface
{
    public function __construct(
        private HttpClientInterface $client
    )
    {
    }

    /**
     * @throws ClientException
     */
    public function request(EndpointInterface $endpoint)
    {
        try {
            $response = $this->client->request($endpoint->method(), $endpoint->endpoint(), $endpoint->options());
            return json_decode($response->getContent(), true);
        } catch (\Throwable $e) {
            throw new ClientException($e->getMessage(), 0, $e);
        }
    }
}