<?php

namespace App\Infrastructure\Repository;

use App\Domain\Question\Model\Question;
use App\Domain\Question\Repository\QuestionRepositoryInterface;
use App\Infrastructure\Entity\Question as QuestionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

class QuestionRepository extends ServiceEntityRepository implements QuestionRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private SerializerInterface $serializer
    ) {
        parent::__construct($registry, QuestionEntity::class);
    }

    public function create(Question $question): void
    {
        $questionEntity = $this->serializer->deserialize(
            $this->serializer->serialize($question, 'json'),
            QuestionEntity::class,
            'json'
        );

        $this->_em->persist($questionEntity);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function readAll(): array
    {
        return $this->_em->getConnection()->fetchAllAssociative('SELECT * FROM question');
    }
}
