<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Query\QueryBusInterface;
use App\Application\Query\QueryInterface;
use App\Application\Query\ViewModelInterface;
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

    public function ask(QueryInterface $query): ViewModelInterface
    {
        return $this->handle($query);
    }
}
