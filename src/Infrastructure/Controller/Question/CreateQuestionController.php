<?php

namespace App\Infrastructure\Controller\Question;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\Question\Create\CreateQuestionCommandInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions', name: 'app_question_create', methods: ['POST'])]
class CreateQuestionController
{
    public function __invoke(Request $request, CommandBusInterface $commandBus): JsonResponse
    {
        $questionData = json_decode($request->getContent(), true);

        try {
            $commandBus->dispatch(new CreateQuestionCommandInterface($questionData));
        } catch (HandlerFailedException $e) {
            $previous = $e->getPrevious();

            if (!$previous instanceof NestedValidationException) {
                throw $previous;
            }

            return new JsonResponse(
                $previous->getMessages(),
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(status: Response::HTTP_CREATED);
    }
}