<?php

namespace App\Tests\UI\Http\Rest\Controller\Question;

use App\Question\Application\QuestionResponse;
use App\Question\Application\SearchQuestionsService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SearchQuestionControllerTest extends WebTestCase
{
    private SearchQuestionsService|MockObject $service;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->getMockBuilder(SearchQuestionsService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->client = static::createClient(['environment' => 'test']);
        $this->client->getContainer()->set(SearchQuestionsService::class, $this->service);
    }

    /**
     * @test
     */
    public function it_should_return_question(): void
    {
        $this->service->expects($this->once())
            ->method('__invoke')
            ->willReturn([[new QuestionResponse(1, 'title')]]);

        $this->client->request('GET', '/api/questions', ['order' => 'desc', 'sort' => 'date', 'site' => 'stackoverflow', 'title' => 'title']);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(1, $responseData[0]);
        $this->assertEquals(1, $responseData[0][0]['id']);
        $this->assertEquals('title', $responseData[0][0]['title']);
    }

    /**
     * @test
     */
    public function it_should_return_assert_exception_when_not_set_site(): void
    {
        $this->service->expects($this->never())
            ->method('__invoke');

        $this->client->request('GET', '/api/questions', ['order' => 'desc', 'sort' => 'date', 'title' => 'testing']);

        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertBadRequestResponse('<site> can not be empty.');
    }

    /**
     * @test
     */
    public function it_should_return_assert_exception_when_not_set_order(): void
    {
        $this->service->expects($this->never())
            ->method('__invoke');

        $this->client->request('GET', '/api/questions', ['sort' => 'date', 'site' => 'stackoverflow', 'title' => 'testing']);

        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertBadRequestResponse('<order> can not be empty.');
    }

    /**
     * @test
     */
    public function it_should_return_assert_exception_when_not_set_sort(): void
    {
        $this->service->expects($this->never())
            ->method('__invoke');

        $this->client->request('GET', '/api/questions', ['order' => 'desc', 'site' => 'stackoverflow', 'title' => 'testing']);

        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertBadRequestResponse('<sort> can not be empty.');
    }

    /**
     * @test
     */
    public function it_should_return_assert_exception_when_not_set_title(): void
    {
        $this->service->expects($this->never())
            ->method('__invoke');

        $this->client->request('GET', '/api/questions', ['order' => 'desc', 'sort' => 'date', 'site' => 'stackoverflow']);

        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertBadRequestResponse('<title> can not be empty.');
    }

    private function assertBadRequestResponse(string $errorMessage): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($errorMessage, json_decode($this->client->getResponse()->getContent(), true)['error']);
    }
}