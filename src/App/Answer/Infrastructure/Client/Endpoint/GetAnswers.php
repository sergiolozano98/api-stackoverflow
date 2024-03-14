<?php

namespace App\Answer\Infrastructure\Client\Endpoint;

use App\Shared\Domain\Client\EndpointInterface;

class GetAnswers implements EndpointInterface
{
    private const string METHOD = 'GET';
    private const string ENDPOINT = '/2.3/answers';

    public function __construct(
        protected string  $order,
        protected string  $sort,
        protected string  $site,
        protected ?string $filter
    )
    {
    }

    public function method(): string
    {
        return self::METHOD;
    }

    public function endpoint(): string
    {
        return self::ENDPOINT;
    }

    public function options(): array
    {
        return [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'order' => $this->order,
                'sort' => $this->sort,
                'site' => $this->site,
                'filter' => $this->filter
            ]
        ];
    }
}