<?php

namespace App\Shared\Domain\Client;

interface ClientInterface
{
    public function request(EndpointInterface $endpoint);

}