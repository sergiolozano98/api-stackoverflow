<?php

namespace App\Answer\Application;

use App\Shared\Domain\Client\ClientInterface;
use App\Shared\Domain\Client\EndpointInterface;

class GetAnswersService
{

    public function __construct(private readonly ClientInterface $client)
    {
    }

    public function __invoke(EndpointInterface $endpoint): array
    {
        $result = $this->client->request($endpoint);

        return array_map(function (array $answer) {
            return new AnswerResponse(
                $answer['answer_id'],
                $answer['body'] ?? null
            );
        }, $result['items']);
    }
}