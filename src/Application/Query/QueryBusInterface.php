<?php

namespace App\Application\Query;

interface QueryBusInterface
{
    public function ask(QueryInterface $query): mixed;
}