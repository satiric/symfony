<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Contract\HealthFactoryInterface;
use App\Domain\Message\GetHealthStatusQuery;
use App\Domain\Response\GetHealthStatusResponse;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class GetHealthStatusHandler
{
    public function __construct(private HealthFactoryInterface $healthFactory)
    {
    }

    public function __invoke(GetHealthStatusQuery $message): GetHealthStatusResponse
    {
        return new GetHealthStatusResponse($this->healthFactory->createAliveHealth());
    }
}
