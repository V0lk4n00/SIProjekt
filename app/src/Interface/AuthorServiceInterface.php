<?php

/**
 * Author service interface.
 */

namespace App\Interface;

use App\Entity\Author;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TaskServiceInterface.
 */
interface AuthorServiceInterface
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
     * Save entity.
     *
     * @param Author $author Author entity
     */
    public function save(Author $author): void;

    /**
     * @param Author $author Author entity
     *
     * @return void Result
     */
    public function delete(Author $author): void;

    /**
     * Can Category be deleted?
     *
     * @param Author $author Author entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Author $author): bool;
}
