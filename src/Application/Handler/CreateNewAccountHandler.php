<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Domain\Contract\AccountFactoryInterface;
use App\Domain\Contract\AccountRepositoryInterface;
use App\Domain\Entity\AccountAction;
use App\Domain\Event\AccountCreateEvent;
use App\Domain\Exception\AccountNotFoundException;
use App\Domain\Message\CreateNewAccountCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsMessageHandler(bus: 'command.bus')]
final class CreateNewAccountHandler
{
    public function __construct(
        private AccountFactoryInterface $accountFactory,
        private AccountRepositoryInterface $accountRepository,
        private EventDispatcherInterface $eventDispatcher,
        private WorkflowInterface $accountStateMachine,
    ) {
    }

    public function __invoke(CreateNewAccountCommand $message): void
    {
        try {
            $this->accountRepository->findOneByEmail($message->email);
            throw new ConflictHttpException(
                message: 'Email address is already associated with another account.',
            );
        } catch (AccountNotFoundException) {
            $account = $this->accountFactory->createCustomAccount(
                email: $message->email,
                password: $message->password,
                roles: $message->roles,
            );
            $this->accountStateMachine->apply($account, transitionName: AccountAction::VERIFY);
            $this->accountRepository->persist($account);
            $this->eventDispatcher->dispatch(new AccountCreateEvent($account));
        }
    }
}
