<?php

namespace App\Tests\Behat;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Question\Create\CreateQuestionCommand;
use App\Domain\Question\Repository\QuestionRepositoryInterface;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Respect\Validation\Exceptions\NestedValidationException;

class QuestionContext implements Context
{
    private ?\Throwable $throwable = null;

    public function __construct(
        private CommandBusInterface $commandBus,
        private QuestionRepositoryInterface $questionRepository
    ) {
    }

    /**
     * @When I create a question with title :title
     */
    public function iCreateAQuestionWithTitle(string $title): void
    {
        $this->commandBus->dispatch(new CreateQuestionCommand($title));
    }

    /**
     * @Then I must have :count question
     * @Then I must have :count questions
     */
    public function iMustHaveQuestions(int $count): void
    {
        Assert::assertCount($count, $this->questionRepository->readAll());
    }

    /**
     * @Then its title must be :title
     */
    public function itsTitleMustBe(string $title): void
    {
        Assert::assertSame($title, $this->questionRepository->readAll()[0]['title']);
    }

    /**
     * @When I create a question with an empty title
     */
    public function iCreateAQuestionWithAnEmptyTitle(): void
    {
        try {
            $this->commandBus->dispatch(new CreateQuestionCommand(''));
        } catch (\Throwable $t) {
            $this->throwable = $t;
        }
    }

    /**
     * @Then I must have a NestedValidationException exception
     */
    public function iMustHaveANestedvalidationexceptionException(): void
    {
        Assert::assertInstanceOf(NestedValidationException::class, $this->throwable);
    }
}
