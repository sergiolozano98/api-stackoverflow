<?php

namespace App\Answer\Application;

readonly class AnswerResponse
{
    public function __construct
    (
        public int     $id,
        public ?string $body
    )
    {
    }
}