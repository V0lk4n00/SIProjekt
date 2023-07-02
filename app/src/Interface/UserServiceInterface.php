<?php
/**
 * This is the license block.
 * It can contain licensing information, copyright notices, etc.
 */

namespace App\Interface;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * User service interface.
 */
interface UserServiceInterface
{
    /**
     * Save user.
     *
     * @param User $user User entity
     */
    public function save(User $user): void;

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;
}
