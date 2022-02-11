<?php

namespace App\Application\Query\Question;

use App\Application\Query\ViewModelInterface;

class ReadQuestionViewModel implements ViewModelInterface
{
    public function __construct(
        private int $id,
        private string $title
    ) {
    }

    public function normalize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
