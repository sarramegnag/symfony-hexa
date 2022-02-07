<?php

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Question\Model\Question;
use App\Domain\Question\Repository\QuestionRepositoryInterface;
use App\Infrastructure\Entity\Question as QuestionEntity;
use Symfony\Component\Serializer\SerializerInterface;

class QuestionRepository implements QuestionRepositoryInterface
{
    private array $questions = [];

    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    public function create(Question $question): void
    {
        $questionEntity = $this->serializer->deserialize(
            $this->serializer->serialize($question, 'json'),
            QuestionEntity::class,
            'json'
        );

        $maxId = array_reduce(
            $this->questions,
            fn (int $carry, QuestionEntity $q): int => $q->getId() > $carry ? $q->getId() : $carry,
            0
        );
        $questionEntity->setId(++$maxId);

        $this->questions[] = $questionEntity;
    }

    public function readAll(): array
    {
        return array_map(
            fn (QuestionEntity $q): array => [
                'id' => $q->getId(),
                'title' => $q->getTitle(),
            ],
            $this->questions
        );
    }
}
