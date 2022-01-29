<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\CommandInterface;
use App\Application\Query\QueryBusInterface;
use App\Application\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $queryBus
    ) {
        $this->messageBus = $queryBus;
    }

    public function ask(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
