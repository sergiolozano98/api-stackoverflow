<?php

namespace App\Question\Application;

use App\Shared\Domain\Client\ClientInterface;
use App\Shared\Domain\Client\EndpointInterface;

class SearchQuestionsService
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    public function __invoke(EndpointInterface $endpoint): array
    {
        $questions = $this->client->request($endpoint);

        return array_map(function (array $question) {
            return new QuestionResponse(
                $question['question_id'],
                $question['title'] ?? null
            );
        }, $questions['items']);
    }
}