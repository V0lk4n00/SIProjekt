<?php
/**
 * Genre service.
 */

namespace App\Service;

use App\Entity\Genre;
use App\Interface\GenreServiceInterface;
use App\Repository\GenreRepository;
use App\Repository\RecordRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class GenreService.
 */
class GenreService implements GenreServiceInterface
{
    /**
     * Genre repository.
     */
    private GenreRepository $genreRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;
    /**
     * Record repository.
     */
    private RecordRepository $recordRepository;

    /**
     * Constructor.
     *
     * @param GenreRepository    $genreRepository  Genre repository
     * @param RecordRepository   $recordRepository Record repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(GenreRepository $genreRepository, RecordRepository $recordRepository, PaginatorInterface $paginator)
    {
        $this->genreRepository = $genreRepository;
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
            $this->genreRepository->queryAll(),
            $page,
            GenreRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Genre $genre Genre entity
     */
    public function save(Genre $genre): void
    {
        $this->genreRepository->save($genre);
    }

    /**
     * Can Category be deleted?
     *
     * @param Genre $genre Genre entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Genre $genre): bool
    {
        try {
            $result = $this->recordRepository->countByGenre($genre);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    /**
     * @param Genre $genre Genre entity
     *
     * @return void Result
     */
    public function delete(Genre $genre): void
    {
        $this->genreRepository->delete($genre);
    }

    /**
     * Find by id.
     *
     * @param int $id Category id
     *
     * @return Genre|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Genre
    {
        return $this->genreRepository->findOneById($id);
    }
}
