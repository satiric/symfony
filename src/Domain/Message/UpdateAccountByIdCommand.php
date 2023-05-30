<?php

declare(strict_types=1);

namespace App\Domain\Message;

use App\Domain\Contract\MessageInterface;
use App\Domain\Entity\AccountRole;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateAccountByIdCommand implements MessageInterface
{
    public const GROUP_EDITABLE = __CLASS__ . ':editable';

    /**
     * @param string[]|null $roles
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $uuid = '',

        #[Assert\Email]
        #[Groups(self::GROUP_EDITABLE)]
        public readonly ?string $email = null,

        #[Assert\Length(min: 8, max: 32)]
        #[Assert\NotCompromisedPassword]
        #[Groups(self::GROUP_EDITABLE)]
        public readonly ?string $password = null,

        #[Assert\Choice(choices: AccountRole::LIST, multiple: true)]
        #[Groups(self::GROUP_EDITABLE)]
        public readonly ?array $roles = null,
    ) {
    }
}