<?php
/**
 * Reservation service.
 */

namespace App\Service;

use App\Entity\Reservation;
use App\Interface\ReservationServiceInterface;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ReservationService.
 */
class ReservationService implements ReservationServiceInterface
{
    /**
     * Reservation repository.
     */
    private ReservationRepository $reservationRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param ReservationRepository $reservationRepository Record repository
     * @param PaginatorInterface    $paginator             Paginator
     */
    public function __construct(ReservationRepository $reservationRepository, PaginatorInterface $paginator)
    {
        $this->reservationRepository = $reservationRepository;
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
            $this->reservationRepository->queryAll(),
            $page,
            ReservationRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Reservation $reservation Reservation entity
     */
    public function save(Reservation $reservation): void
    {
        $this->reservationRepository->save($reservation);
    }

    public function delete(Reservation $reservation): void
    {
        $this->reservationRepository->delete($reservation);
    }
}
