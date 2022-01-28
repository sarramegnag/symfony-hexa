<?php

namespace App\Application\Command\Question\Create;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Question\Model\Question;
use App\Domain\Question\Repository\QuestionRepositoryInterface;
use App\Domain\Question\Validator\QuestionValidator;

class CreateQuestionCommandHandlerInterface implements CommandHandlerInterface
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository
    ) {
    }

    public function __invoke(CreateQuestionCommandInterface $command): void
    {
        $question = Question::createFromArray($command->getData());

        $this->questionRepository->create($question);
    }
}