<?php

namespace App\Application\Command\Question\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Question\Model\Question;
use App\Domain\Question\Repository\QuestionRepositoryInterface;
use App\Domain\Question\Validator\QuestionValidator;

class CreateQuestionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository
    ) {
    }

    public function __invoke(CreateQuestionCommand $command): void
    {
        $question = new Question($command->getTitle());

        (new QuestionValidator())->validate($question);

        $this->questionRepository->create($question);
    }
}
