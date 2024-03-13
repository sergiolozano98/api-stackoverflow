<?php

namespace App\Answer\Application;

readonly class AnswerResponse
{
    public function __construct(public int $id, public ?string $body)
    {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function body(): ?string
    {
        return $this->body;
    }
}