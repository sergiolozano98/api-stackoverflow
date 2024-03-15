<?php

namespace App\Tests\UI\Http\Rest\Controller\Answer;

use App\Answer\Application\AnswerResponse;
use App\Answer\Application\GetAnswersService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetAllAnswersControllerTest extends WebTestCase
{
    private GetAnswersService|MockObject $service;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->getMockBuilder(GetAnswersService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->client = static::createClient(['environment' => 'test']);
        $this->client->getContainer()->set(GetAnswersService::class, $this->service);
    }

    /**
     * @test
     */
    public function it_should_return_answers(): void
    {
        $this->service->expects($this->once())
            ->method('__invoke')
            ->willReturn([[new AnswerResponse(1, null)]]);

        $this->client->request('GET', '/api/answers', ['order' => 'desc', 'sort' => 'creation', 'site' => 'stackoverflow']);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(1, $responseData[0]);
        $this->assertEquals(1, $responseData[0][0]['id']);
        $this->assertEquals(null, $responseData[0][0]['body']);
    }

    /**
     * @test
     */
    public function it_should_return_assert_exception_when_not_set_site(): void
    {
        $this->service->expects($this->never())
            ->method('__invoke');

        $this->client->request('GET', '/api/answers', ['order' => 'desc', 'sort' => 'date']);

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

        $this->client->request('GET', '/api/answers', ['sort' => 'date', 'site' => 'stackoverflow']);
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

        $this->client->request('GET', '/api/answers', ['order' => 'desc', 'site' => 'stackoverflow']);
        $this->assertJson($this->client->getResponse()->getContent());

        $this->assertBadRequestResponse('<sort> can not be empty.');
    }


    private function assertBadRequestResponse(string $errorMessage): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($errorMessage, json_decode($this->client->getResponse()->getContent(), true)['error']);
    }
}