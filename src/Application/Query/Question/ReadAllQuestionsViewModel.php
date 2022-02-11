<?php

namespace App\Application\Query\Question;

use App\Application\Query\ViewModelInterface;

class ReadAllQuestionsViewModel implements ViewModelInterface
{
    private array $questions;

    public function __construct(
        array $questions
    ) {
        $this->questions = array_map(
            fn (array $question): ReadQuestionViewModel => new ReadQuestionViewModel($question['id'], $question['title']),
            $questions
        );
    }

    public function normalize(): array
    {
        return array_map(
            fn (ReadQuestionViewModel $question): array => $question->normalize(),
            $this->questions
        );
    }
}
