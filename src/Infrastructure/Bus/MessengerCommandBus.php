<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {
    }

    /**
     * @throws Throwable
     */
    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
        }
    }
}
