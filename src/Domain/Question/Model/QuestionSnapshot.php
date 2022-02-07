<?php

namespace App\Domain\Question\Model;

class QuestionSnapshot
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
