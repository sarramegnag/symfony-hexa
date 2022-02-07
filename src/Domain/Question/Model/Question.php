<?php

namespace App\Domain\Question\Model;

class Question
{
    public function __construct(
        private string $title
    ) {
    }

    public function saveSnapshot(): QuestionSnapshot
    {
        return new QuestionSnapshot($this->title);
    }

    public static function restoreSnapshot(QuestionSnapshot $snapshot): self
    {
        return new self($snapshot->getTitle());
    }
}
