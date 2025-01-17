<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Auth;

use App\Application\Attribute\MapRequestMessage;
use App\Application\Handler\GetSigninAccount\GetSigninAccountQuery;
use App\Application\Handler\GetSigninAccount\GetSigninAccountResponse;
use App\Presentation\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[AsController]
final class GetSigninAccountController extends AbstractController
{
    #[OA\Get(
        summary: 'Get signin account information',
        security: [['bearer' => []]],
        tags: ['Authorization'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'OK',
                content: new OA\JsonContent(ref: new Model(type: GetSigninAccountResponse::class)),
            ),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'Unauthorized'),
        ],
    )]
    #[Route(
        path: '/api/auth/me',
        name: __CLASS__,
        methods: Request::METHOD_GET,
        format: JsonEncoder::FORMAT,
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED, message: 'Not privileged to request the resource.')]
    public function __invoke(#[MapRequestMessage] GetSigninAccountQuery $message): Envelope
    {
        return $this->queryBus->dispatch($message);
    }
}
