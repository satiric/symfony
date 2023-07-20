<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Attribute\MapRequestMessage;
use App\Application\Handler\GetMainPage\GetMainPageQuery;
use App\Application\Handler\GetMainPage\GetMainPageResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[AsController]
final class MainController extends AbstractController
{
    #[OA\Get(
        summary: 'Get main page',
        tags: ['Health'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'OK',
                content: new OA\JsonContent(ref: new Model(type: GetMainPageResponse::class)),
            ),
        ],
    )]
    #[Route(
        path: '/',
        name: __CLASS__,
        methods: Request::METHOD_GET,
        format: JsonEncoder::FORMAT,
    )]
    public function __invoke(#[MapRequestMessage] GetMainPageQuery $message): Envelope
    {
        return $this->queryBus->dispatch($message);
    }
}
