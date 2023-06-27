<?php
/**
 * Record service interface.
 */
namespace App\Interface;

use App\Entity\Record;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TaskServiceInterface.
 */
interface RecordServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int  $page   Page number
     * @param User $rental Rental
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $rental): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Record $record Task entity
     */
    public function save(Record $record): void;

    /**
     * Delete entity.
     *
     * @param Record $record Record entity
     */
    public function delete(Record $record): void;
}
