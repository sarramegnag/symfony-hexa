<?php

namespace App\Domain\Question\Repository;

use App\Domain\Question\Model\Question;

interface QuestionRepositoryInterface
{
    public function create(Question $question): void;

    public function readAll(): array;
}