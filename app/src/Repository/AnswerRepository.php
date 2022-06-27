<?php
/**
 * Answer Repository.
 */
namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 *
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial answer.{id, createdAt, updatedAt, content, email, nickname, best}',
                'partial question.{id, title}'
            )
            ->join('answer.question', 'question')
            ->orderBy('answer.updatedAt', 'DESC');
    }

    /**
     * Count answers by question.
     *
     * @param Question $question
     *
     * @return int Number of questions in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByQuestion(Question $question): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('answer.id'))
            ->where('answer.question = :question')
            ->setParameter(':question', $question)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save entity.
     *
     * @param Answer $answer
     */
    public function save(Answer $answer): void
    {
        $this->_em->persist($answer);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Answer $answer
     */
    public function delete(Answer $answer): void
    {
        $this->_em->remove($answer);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('answer');
    }

}
