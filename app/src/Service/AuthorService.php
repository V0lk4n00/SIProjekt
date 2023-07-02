<?php
/**
 * Author service.
 */

namespace App\Service;

use App\Entity\Author;
use App\Interface\AuthorServiceInterface;
use App\Repository\AuthorRepository;
use App\Repository\RecordRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
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
     * Record repository.
     */
    private RecordRepository $recordRepository;

    /**
     * Constructor.
     *
     * @param AuthorRepository   $authorRepository Author repository
     * @param RecordRepository   $recordRepository Record repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(AuthorRepository $authorRepository, RecordRepository $recordRepository, PaginatorInterface $paginator)
    {
        $this->authorRepository = $authorRepository;
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

    /**
     * Can Category be deleted?
     *
     * @param Author $author Author entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Author $author): bool
    {
        try {
            $result = $this->recordRepository->countByAuthor($author);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    /**
     * @param Author $author Author
     */
    public function delete(Author $author): void
    {
        $this->authorRepository->delete($author);
    }

    /**
     * Find by id.
     *
     * @param int $id Category id
     *
     * @return Author|null Category entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Author
    {
        return $this->authorRepository->findOneById($id);
    }
}
