<?php

namespace App\Shared\Domain\Client;

interface EndpointInterface
{
    public function method(): string;

    public function endpoint(): string;

    public function options(): array;
}