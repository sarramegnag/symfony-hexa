<?php

namespace App\Infrastructure\Controller\Question;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Question\Create\CreateQuestionCommand;
use Respect\Validation\Exceptions\NestedValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions', name: 'app_question_create', methods: ['POST'])]
class CreateQuestionController
{
    public function __invoke(Request $request, CommandBusInterface $commandBus): JsonResponse
    {
        $questionData = json_decode($request->getContent(), true) ?? [];

        try {
            $commandBus->dispatch(new CreateQuestionCommand($questionData));
        } catch (NestedValidationException $e) {
            return new JsonResponse(
                $e->getMessages(),
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(status: Response::HTTP_CREATED);
    }
}
