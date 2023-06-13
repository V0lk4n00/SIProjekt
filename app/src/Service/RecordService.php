<?php
/**
 * Record service.
 */

namespace App\Service;

use App\Interface\RecordServiceInterface;
use App\Repository\RecordRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RecordService.
 */
class RecordService implements RecordServiceInterface
{
    /**
     * Record repository.
     */
    private RecordRepository $recordRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param RecordRepository   $recordRepository Record repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(RecordRepository $recordRepository, PaginatorInterface $paginator)
    {
        $this->recordRepository = $recordRepository;
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
            $this->recordRepository->queryAll(),
            $page,
            RecordRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
