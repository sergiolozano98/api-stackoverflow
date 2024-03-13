<?php

namespace UI\Http\Rest\Controller\Answer;

use App\Answer\Application\AnswerResponse;
use App\Answer\Infrastructure\Client\Endpoint\GetAnswers;
use App\Shared\Domain\Client\ClientInterface;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

readonly class GetAllAnswersController
{
    public function __construct(private ClientInterface $client)
    {
    }

    #[Route('/api/answers', name: 'all_answers', methods: ['GET'])]
    #[OA\Get(
        path: "/api/answers",
        summary: "Get answers based on specified parameters.",
    )]
    #[OA\Parameter(
        name: 'order',
        description: 'Specify order (desc, asc)',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'sort',
        description: 'The field used specify order',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'site',
        description: 'The specify site of data',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'filter',
        description: 'The optional field used for set a custom filter (ex: withBody)',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {

            $order = $request->get('order');
            $sort = $request->get('sort');
            $site = $request->get('site');
            $filter = $request->get('filter') ?? null;

            Assertion::notEmpty($order, '<order> can not be empty.');
            Assertion::notEmpty($sort, '<sort> can not be empty.');
            Assertion::notEmpty($site, '<site> can not be empty.');

            $result = $this->client->request(new GetAnswers($order, $sort, $site, $filter));

            $response = array_map(function (array $answer) {
                return new AnswerResponse(
                    $answer['answer_id'],
                    $answer['body'] ?? null
                );
            }, $result['items']);

            return new JsonResponse($response, Response::HTTP_OK, []);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}