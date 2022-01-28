<?php

namespace App\Domain\Question\Model;

use App\Domain\Question\Validator\QuestionValidator;

class Question
{
    public function __construct(
        private string $title
    ) {
    }

    public static function createFromArray(array $data): self
    {
        (new QuestionValidator())->validate($data);

        return new self($data['title']);
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}