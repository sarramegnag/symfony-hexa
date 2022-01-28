<?php

namespace App\Application\Command\Question\Create;

use App\Application\Command\CommandInterface;

class CreateQuestionCommandInterface implements CommandInterface
{
    public function __construct(
        private array $data
    ) {
    }

    public function getData(): array
    {
        return $this->data;
    }
}