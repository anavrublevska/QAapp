<?php
/**
 * Answer service.
 */

namespace App\Service;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AnswerService.
 */
class AnswerService implements AnswerServiceInterface
{
    /**
     * Answer repository.
     */
    private AnswerRepository $answerRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param AnswerRepository   $answerRepository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(AnswerRepository $answerRepository, PaginatorInterface $paginator)
    {
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
            $this->answerRepository->queryAll(),
            $page,
            AnswerRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Answer $answer
     */
    public function save(Answer $answer): void
    {
        $this->answerRepository->save($answer);
    }

    /**
     * Delete entity.
     *
     * @param Answer $answer
     */
    public function delete(Answer $answer): void
    {
        $this->answerRepository->delete($answer);
    }
}
