<?php

declare(strict_types=1);

namespace App\Application\Handler\GetAccountsByCriteria;

use App\Domain\Entity\AccountStatus;
use Symfony\Component\Validator\Constraints as Assert;

final class GetAccountsByCriteriaQuery
{
    public function __construct(
        #[Assert\Length(max: 320)]
        public readonly ?string $email = null,

        #[Assert\Choice(choices: AccountStatus::LIST)]
        public readonly ?string $status = null,

        #[Assert\Choice(choices: ['created_at', 'email', 'status'])]
        public readonly string $sort = 'created_at',

        #[Assert\Choice(choices: ['asc', 'desc'])]
        public readonly string $order = 'desc',

        #[Assert\DivisibleBy(value: 1)]
        #[Assert\Positive]
        public readonly int $limit = 10,

        #[Assert\DivisibleBy(value: 1)]
        #[Assert\PositiveOrZero]
        public readonly int $offset = 0,
    ) {
    }
}
