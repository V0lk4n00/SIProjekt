<?php
/**
 * Author service.
 */

namespace App\Service;

use App\Entity\Author;
use App\Interface\AuthorServiceInterface;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AuthorService.
 */
class AuthorService implements AuthorServiceInterface
{
    /**
     * Author repository.
     */
    private AuthorRepository $authorRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param AuthorRepository   $authorRepository Author repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(AuthorRepository $authorRepository, PaginatorInterface $paginator)
    {
        $this->authorRepository = $authorRepository;
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
            $this->authorRepository->queryAll(),
            $page,
            AuthorRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Author $author Author entity
     */
    public function save(Author $author): void
    {
        $this->authorRepository->save($author);
    }
}
