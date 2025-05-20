<?php

/**
 * Reservation service interface.
 */

namespace App\Interface;

use App\Entity\Reservation;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TaskServiceInterface.
 */
interface ReservationServiceInterface
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
     * @param Reservation $reservation Reservation entity
     */
    public function save(Reservation $reservation): void;

    /**
     * @param Reservation $reservation Reservation entity
     *
     * @return void Result
     */
    public function delete(Reservation $reservation): void;
}
