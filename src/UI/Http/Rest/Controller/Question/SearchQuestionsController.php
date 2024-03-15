<?php

namespace UI\Http\Rest\Controller\Question;

use App\Question\Application\QuestionResponse;
use App\Question\Application\SearchQuestionsService;
use App\Question\Infrastructure\Client\Endpoint\SearchQuestion;
use App\Shared\Domain\Client\ClientInterface;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

readonly class SearchQuestionsController
{
    public function __construct(private SearchQuestionsService $service)
    {
    }

    #[Route('/api/questions', name: 'search_questions', methods: ['GET'])]
    #[OA\Get(
        path: "/api/question",
        summary: "search based by title.",
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
        name: 'title',
        description: 'Title of the question you are looking for',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    public function __invoke(Request $request): JsonResponse
    {
        try {

            $order = $request->get('order');
            $sort = $request->get('sort');
            $site = $request->get('site');
            $title = $request->get('title');

            Assertion::notEmpty($order, '<order> can not be empty.');
            Assertion::notEmpty($sort, '<sort> can not be empty.');
            Assertion::notEmpty($site, '<site> can not be empty.');
            Assertion::notEmpty($title, '<title> can not be empty.');


            $result = $this->service->__invoke(new SearchQuestion($order, $sort, $site, $title));

            return new JsonResponse($result, Response::HTTP_OK, []);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}