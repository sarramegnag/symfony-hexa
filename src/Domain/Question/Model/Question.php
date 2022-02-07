<?php

namespace App\Domain\Question\Model;

class Question
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
