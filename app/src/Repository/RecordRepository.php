<?php
/**
 * Record repository.
 */
namespace App\Repository;

use App\Entity\Author;
use App\Entity\Genre;
use App\Entity\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Record>
 *
 * @method Record|null find($id, $lockMode = null, $lockVersion = null)
 * @method Record|null findOneBy(array $criteria, array $orderBy = null)
 * @method Record[]    findAll()
 * @method Record[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    /**
     * Query all records.
     *
     * @param array $filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial record.{id, author, title, genre, inStock}',
                'partial genre.{id, genreName}',
                'partial author.{id, name, surname, alias}'
            )
            ->join('record.genre', 'genre')
            ->join('record.author', 'author')
            ->orderBy('record.id', 'ASC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Count records by genre.
     *
     * @param Genre $genre Genre
     *
     * @return int Number of records with genre
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByGenre(Genre $genre): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('record.id'))
            ->where('record.genre = :genre')
            ->setParameter(':genre', $genre)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Count records by author.
     *
     * @param Author $author Author
     *
     * @return int Number of records with author
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByAuthor(Author $author): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('record.id'))
            ->where('record.author = :author')
            ->setParameter(':author', $author)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save entity.
     *
     * @param Record $record Record entity
     */
    public function save(Record $record): void
    {
        $this->_em->persist($record);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Record $record Record entity
     */
    public function delete(Record $record): void
    {
        $this->_em->remove($record);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('record');
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param array<string, object> $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['genre']) && $filters['genre'] instanceof Genre) {
            $queryBuilder->andWhere('genre = :genre')
                ->setParameter('genre', $filters['genre']);
        }

        if (isset($filters['author']) && $filters['author'] instanceof Author) {
            $queryBuilder->andWhere('author IN (:author)')
                ->setParameter('author', $filters['author']);
        }

        return $queryBuilder;
    }
}
