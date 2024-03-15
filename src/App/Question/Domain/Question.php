<?php

namespace App\Question\Domain;

class Question
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