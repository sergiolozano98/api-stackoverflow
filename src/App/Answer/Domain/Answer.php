<?php

namespace App\Answer\Domain;

class Answer
{
    public function __construct(protected string $id, protected string $body)
    {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function body(): string
    {
        return $this->body;
    }
}