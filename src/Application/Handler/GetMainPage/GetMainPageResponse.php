<?php

declare(strict_types=1);

namespace App\Application\Handler\GetMainPage;

final class GetMainPageResponse
{
    public readonly string $hello;
    public function __construct(string $hello)
    {
        $this->hello = $hello;
    }
}
