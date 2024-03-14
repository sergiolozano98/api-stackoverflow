<?php

namespace App\Question\Infrastructure\Client\Endpoint;

use App\Shared\Domain\Client\EndpointInterface;

class SearchQuestion implements EndpointInterface
{
    private const string METHOD = 'GET';
    private const string ENDPOINT = '/2.3/search';

    public function __construct(
        protected string  $order,
        protected string  $sort,
        protected string  $site,
        protected string $title
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
                'intitle' => $this->title
            ]
        ];
    }
}