<?php

declare(strict_types=1);

namespace App\Domain\Contract;

use App\Domain\Entity\Account;

interface AccountFactoryInterface
{
    /**
     * @param string[] $roles
     */
    public function createCustomAccount(string $email, array $roles): Account;

    public function createUserAccount(string $email): Account;
}
