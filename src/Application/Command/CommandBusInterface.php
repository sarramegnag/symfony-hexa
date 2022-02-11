<?php

namespace App\Application\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
