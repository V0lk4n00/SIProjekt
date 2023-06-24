<?php
/**
 * Genre service.
 */

namespace App\Service;

use App\Entity\Genre;
use App\Interface\GenreServiceInterface;
use App\Repository\GenreRepository;
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
     * Constructor.
     *
     * @param GenreRepository    $genreRepository Genre repository
     * @param PaginatorInterface $paginator       Paginator
     */
    public function __construct(GenreRepository $genreRepository, PaginatorInterface $paginator)
    {
        $this->genreRepository = $genreRepository;
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
}
