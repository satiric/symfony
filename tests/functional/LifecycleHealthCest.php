<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\HealthStatus;

final class LifecycleHealthCest
{
    public function checkHealthStatus(FunctionalTester $i): void
    {
        $i->haveHttpHeader(name: 'Content-Type', value: 'application/json');
        $i->haveHttpHeader(name: 'X-Request-Id', value: 'none');
        $i->sendGet(url: '/api/health');
        $i->seeResponseCodeIsSuccessful();
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(['status' => HealthStatus::OK->value]);
        $i->seeHttpHeader(name: 'X-Request-Id');
    }

    public function checkHealthStatusUsingInvalidMethod(FunctionalTester $i): void
    {
        $i->haveHttpHeader(name: 'Content-Type', value: 'application/json');
        $i->sendPost(url: '/api/health');
        $i->seeResponseCodeIsClientError();
    }
}
