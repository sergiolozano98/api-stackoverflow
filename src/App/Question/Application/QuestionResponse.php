<?php

namespace App\Question\Application;

class QuestionResponse
{
    public function __construct(
        public int    $id,
        public string $title
    )
    {
    }
}