<?php

namespace App\Tests\src\App\Answer\Application;

use App\Answer\Application\AnswerResponse;
use App\Answer\Application\GetAnswersService;
use App\Shared\Domain\Client\ClientInterface;
use App\Shared\Domain\Client\EndpointInterface;
use PHPUnit\Framework\TestCase;

class GetAnswersServiceTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_return_answers()
    {
        $clientMock = $this->createMock(ClientInterface::class);
        $endpointMock = $this->createMock(EndpointInterface::class);

        $clientMock->expects($this->once())
            ->method('request')
            ->with($endpointMock)
            ->willReturn(['items' => [['answer_id' => 1, 'body' => 'body 1'], ['answer_id' => 2, 'body' => 'body 2']]]);

        $service = new GetAnswersService($clientMock);
        $result = $service->__invoke($endpointMock);

        $this->assertIsArray($result);

        $this->assertCount(2, $result);
        $this->assertInstanceOf(AnswerResponse::class, $result[0]);
        $this->assertEquals(1, $result[0]->id);
        $this->assertEquals('body 1', $result[0]->body);

        $this->assertInstanceOf(AnswerResponse::class, $result[1]);
        $this->assertEquals(2, $result[1]->id);
        $this->assertEquals('body 2', $result[1]->body);
    }
}