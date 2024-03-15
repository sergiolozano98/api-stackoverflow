<?php

namespace UI\Http\Rest\Controller\Answer;

use App\Answer\Application\GetAnswersService;
use App\Answer\Infrastructure\Client\Endpoint\GetAnswers;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

readonly class GetAllAnswersController
{
    public function __construct(private GetAnswersService $service)
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

            $result = $this->service->__invoke(new GetAnswers($order, $sort, $site, $filter));

            return new JsonResponse($result, Response::HTTP_OK, []);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}