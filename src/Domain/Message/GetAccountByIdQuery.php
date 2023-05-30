<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\Contract\MessageInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class GetAccountByIdQuery implements MessageInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $uuid = '',
    ) {
    }
}