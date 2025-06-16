<?php

/**
 * Reservation service test.
 */
namespace App\Tests\Service;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ReservationServiceTest.
 */
class ReservationServiceTest extends TestCase
{
    private ReservationRepository $reservationRepository;
    private ReservationService $reservationService;

    /**
     * Setup function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->reservationRepository = $this->createMock(ReservationRepository::class);
        $paginator = $this->createMock(PaginatorInterface::class);

        $this->reservationService = new ReservationService(
            $this->reservationRepository,
            $paginator
        );
    }

    /**
     * Tests the save method.
     *
     * @return void
     */
    public function testSave(): void
    {
        $reservation = $this->createMock(Reservation::class);

        $this->reservationRepository
            ->expects($this->once())
            ->method('save')
            ->with($reservation);

        $this->reservationService->save($reservation);
    }

    /**
     * Tests the delete method.
     *
     * @return void
     */
    public function testDelete(): void
    {
        $reservation = $this->createMock(Reservation::class);

        $this->reservationRepository
            ->expects($this->once())
            ->method('delete')
            ->with($reservation);

        $this->reservationService->delete($reservation);
    }
}
