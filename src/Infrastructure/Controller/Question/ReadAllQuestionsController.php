<?php

namespace App\Infrastructure\Controller\Question;

use App\Application\Query\Question\ReadAllQuestionsQuery;
use App\Application\Query\QueryBusInterface;
use App\Application\Query\ViewModelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions', name: 'app_question_read_all', methods: ['GET'])]
class ReadAllQuestionsController
{
    public function __invoke(Request $request, QueryBusInterface $queryBus): JsonResponse
    {
        /** @var ViewModelInterface $viewModel */
        $viewModel = $queryBus->ask(new ReadAllQuestionsQuery());

        return new JsonResponse($viewModel->normalize());
    }
}