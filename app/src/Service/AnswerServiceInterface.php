<?php
/**
 * Answer service interface.
 */

namespace App\Service;

use App\Entity\Answer;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface AnswerServiceInterface.
 */
interface AnswerServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * @param Answer $answer answer
     */
    public function save(Answer $answer): void;

    /**
     * @param Answer $answer answer
     */
    public function delete(Answer $answer): void;
}
