<?php
/**
 * Question service interface.
 */

namespace App\Service;

use App\Entity\Question;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface QuestionServiceInterface.
 */
interface QuestionServiceInterface
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
     * @param Question $question question
     */
    public function save(Question $question): void;

    /**
     * @param Question $question question
     */
    public function delete(Question $question): void;
}
