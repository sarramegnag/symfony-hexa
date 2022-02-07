<?php

namespace App\Application\Command\Question\Create;

use App\Application\Command\CommandInterface;

class CreateQuestionCommand implements CommandInterface
{
    public function __construct(
        private string $title
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
