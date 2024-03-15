<?php

namespace App\Tests\src\App\Question\Application;

use App\Question\Application\QuestionResponse;
use App\Question\Application\SearchQuestionsService;
use App\Shared\Domain\Client\ClientInterface;
use App\Shared\Domain\Client\EndpointInterface;
use PHPUnit\Framework\TestCase;

class SearchQuestionServiceTest extends TestCase
{

    /**
     * @test
     */
    public function it_should_return_questions()
    {
        $clientMock = $this->createMock(ClientInterface::class);
        $endpointMock = $this->createMock(EndpointInterface::class);

        $clientMock->expects($this->once())
            ->method('request')
            ->with($endpointMock)
            ->willReturn(['items' => [['question_id' => 1, 'title' => 'Title 1'], ['question_id' => 2, 'title' => 'Title 2']]]);

        $service = new SearchQuestionsService($clientMock);
        $result = $service->__invoke($endpointMock);

        $this->assertIsArray($result);

        $this->assertCount(2, $result);
        $this->assertInstanceOf(QuestionResponse::class, $result[0]);
        $this->assertEquals(1, $result[0]->id);
        $this->assertEquals('Title 1', $result[0]->title);

        $this->assertInstanceOf(QuestionResponse::class, $result[1]);
        $this->assertEquals(2, $result[1]->id);
        $this->assertEquals('Title 2', $result[1]->title);
    }
}