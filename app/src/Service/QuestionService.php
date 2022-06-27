<?php
/**
 * Question service.
 */

namespace App\Service;

use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class QuestionService.
 */
class QuestionService implements QuestionServiceInterface
{
    /**
     * Question repository.
     */
    private QuestionRepository $questionRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Answer repository.
     */
    private AnswerRepository $answerRepository;

    /**
     * Constructor.
     *
     * @param QuestionRepository $questionRepository
     * @param AnswerRepository   $answerRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(QuestionRepository $questionRepository, AnswerRepository $answerRepository, PaginatorInterface $paginator)
    {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->questionRepository->queryAll(),
            $page,
            QuestionRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Question $question
     */
    public function save(Question $question): void
    {
        $this->questionRepository->save($question);
    }

    /**
     * @param Question $question
     *
     * @return bool
     */
    public function canBeDeleted(Question $question): bool
    {
        try {
            $result = $this->answerRepository->countByQuestion($question);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    /**
     * Delete entity.
     *
     * @param Question $question
     */
    public function delete(Question $question): void
    {
        $this->questionRepository->delete($question);
    }
}
