<?php

namespace App\Tests\UI\Http\Rest\Controller\Answer;

use App\Shared\Domain\Client\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetAllAnswersControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ClientInterface|MockObject $clientServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient(['environment' => 'test']);
        $this->clientServiceMock = $this->createMock(ClientInterface::class);
        $this->client->getContainer()->set(ClientInterface::class, $this->clientServiceMock);
    }

    /**
     * @test
     */
    public function it_should_return_answers(): void
    {
        $this->clientServiceMock->expects($this->once())
            ->method('request')
            ->willReturn(['items' => [['answer_id' => 1, 'body' => 'Answer body']]]);

        $this->makeRequest('/api/answers', ['order' => 'desc', 'sort' => 'date', 'site' => 'stackoverflow']);

        $this->assertSuccessResponse();
        $this->assertJsonResponse();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertCount(1, $responseData);
        $this->assertEquals(1, $responseData[0]['id']);
        $this->assertEquals('Answer body', $responseData[0]['body']);
    }

    /**
     * @test
     */
    public function it_should_return_assert_exception_when_not_set_site(): void
    {
        $this->clientServiceMock->expects($this->never())
            ->method('request');

        $this->makeRequest('/api/answers', ['order' => 'desc', 'sort' => 'date']);

        $this->assertBadRequestResponse('<site> can not be empty.');
    }

    /**
     * @test
     */
    public function it_should_return_assert_exception_when_not_set_order(): void
    {
        $this->clientServiceMock->expects($this->never())
            ->method('request');

        $this->makeRequest('/api/answers', ['sort' => 'date', 'site' => 'stackoverflow']);

        $this->assertBadRequestResponse('<order> can not be empty.');
    }

    /**
     * @test
     */
    public function it_should_return_assert_exception_when_not_set_sort(): void
    {
        $this->clientServiceMock->expects($this->never())
            ->method('request');

        $this->makeRequest('/api/answers', ['order' => 'desc', 'site' => 'stackoverflow']);

        $this->assertBadRequestResponse('<sort> can not be empty.');
    }

    private function makeRequest(string $uri, array $parameters): void
    {
        $this->client->request('GET', $uri, $parameters);
    }

    private function assertSuccessResponse(): void
    {
        $this->assertResponseIsSuccessful();
    }

    private function assertJsonResponse(): void
    {
        $this->assertJson($this->client->getResponse()->getContent());
    }

    private function assertBadRequestResponse(string $errorMessage): void
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($errorMessage, json_decode($this->client->getResponse()->getContent(), true)['error']);
    }
}