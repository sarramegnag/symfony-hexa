<?php

namespace App\Application\Query\Question;

use App\Application\Query\QueryHandlerInterface;
use App\Domain\Question\Repository\QuestionRepositoryInterface;

class ReadAllQuestionsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository
    ) {
    }

    public function __invoke(ReadAllQuestionsQuery $query): ReadAllQuestionsViewModel
    {
        return new ReadAllQuestionsViewModel(
            $this->questionRepository->readAll()
        );
    }
}
