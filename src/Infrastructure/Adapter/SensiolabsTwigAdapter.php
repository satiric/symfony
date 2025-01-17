<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Contract\TwigAdapterInterface;
use App\Domain\Exception\TwigAdapterException;
use Twig\Environment;
use Twig\Error\Error;

final class SensiolabsTwigAdapter implements TwigAdapterInterface
{
    public function __construct(private Environment $environment)
    {
    }

    public function render(string $name, array $context = []): string
    {
        try {
            return $this->environment->render($name, $context);
        } catch (Error $e) {
            throw new TwigAdapterException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
