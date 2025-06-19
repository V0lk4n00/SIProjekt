<?php

/**
 * Record service.
 */

namespace App\Service;

use App\Entity\Record;
use App\Interface\AuthorServiceInterface;
use App\Interface\GenreServiceInterface;
use App\Interface\RecordServiceInterface;
use App\Repository\RecordRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * Genre service.
     */
    private GenreServiceInterface $genreService;

    /**
     * Author service.
     */
    private AuthorServiceInterface $authorService;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param RecordRepository       $recordRepository Record repository
     * @param AuthorServiceInterface $authorService    Author service
     * @param GenreServiceInterface  $genreService     Genre service
     * @param PaginatorInterface     $paginator        Paginator
     */
    public function __construct(
        RecordRepository $recordRepository,
        AuthorServiceInterface $authorService,
        GenreServiceInterface $genreService,
        PaginatorInterface $paginator,
    ) {
        $this->recordRepository = $recordRepository;
        $this->genreService = $genreService;
        $this->authorService = $authorService;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters
     *
     * @return PaginationInterface<string, mixed> Paginated list
     *
     * @throws NonUniqueResultException
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->recordRepository->queryAll($filters),
            $page,
            RecordRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Record $record Record entity
     */
    public function save(Record $record): void
    {
        $this->recordRepository->save($record);
    }

    /**
     * Delete entity.
     *
     * @param Record $record Record entity
     */
    public function delete(Record $record): void
    {
        $this->recordRepository->delete($record);
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     *
     * @throws NonUniqueResultException
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['genre_id'])) {
            $genre = $this->genreService->findOneById($filters['genre_id']);
            if (null !== $genre) {
                $resultFilters['genre'] = $genre;
            }
        }

        if (!empty($filters['author_id'])) {
            $author = $this->authorService->findOneById($filters['author_id']);
            if (null !== $author) {
                $resultFilters['author'] = $author;
            }
        }

        return $resultFilters;
    }
}
