<?php

declare(strict_types=1);

namespace App\Application\Handler\GetMainPage;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class GetMainPageHandler
{
    public function __invoke(GetMainPageQuery $message): GetMainPageResponse
    {
        return new GetMainPageResponse('world');
    }
}
