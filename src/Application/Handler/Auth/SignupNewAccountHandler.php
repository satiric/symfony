<?php

declare(strict_types=1);

namespace App\Application\Handler\Auth;

use App\Application\Factory\AccountFactory;
use App\Domain\Contract\Repository\AccountRepositoryInterface;
use App\Domain\Event\AccountCreateEvent;
use App\Domain\Exception\AccountNotFoundException;
use App\Domain\Message\Auth\SignupNewAccountCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler(bus: 'command.bus')]
final class SignupNewAccountHandler
{
    public function __construct(
        private AccountFactory $accountFactory,
        private AccountRepositoryInterface $accountRepository,
        private EventDispatcherInterface $eventDispatcher,
        private TranslatorInterface $translator,
    ) {
    }

    public function __invoke(SignupNewAccountCommand $message): void
    {
        try {
            $this->accountRepository->findOneByEmail($message->email);
            throw new ConflictHttpException(
                message: 'Email address is already associated with another account.',
            );
        } catch (AccountNotFoundException) {
            $account = $this->accountFactory->createUserAccount(
                email: $message->email,
                password: $message->password,
                locale: $this->translator->getLocale(),
            );
            $this->accountRepository->persist($account);
            $this->eventDispatcher->dispatch(new AccountCreateEvent($account));
        }
    }
}
