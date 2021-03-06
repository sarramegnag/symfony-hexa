<?php

namespace App\Infrastructure\Repository;

use App\Domain\Question\Model\Question;
use App\Domain\Question\Repository\QuestionRepositoryInterface;
use App\Infrastructure\Entity\Question as QuestionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class QuestionRepository extends ServiceEntityRepository implements QuestionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionEntity::class);
    }

    public function create(Question $question): void
    {
        $questionEntity = QuestionEntity::createFromSnapshot($question->saveSnapshot());

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
