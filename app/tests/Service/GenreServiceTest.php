<?php

/**
 * Genre service tests.
 */

namespace App\Tests\Service;

use App\Entity\Genre;
use App\Interface\GenreServiceInterface;
use App\Service\GenreService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class GenreServiceTest.
 */
class GenreServiceTest extends KernelTestCase
{
    /**
     * Genre repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Genre service.
     */
    private ?GenreServiceInterface $genreService;

    /**
     * Set up test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->genreService = $container->get(GenreService::class);
    }

    /**
     * Test save.
     *
     * @throws ORMException
     */
    public function testSave(): void
    {
        // given
        $expectedGenre = new Genre();
        $expectedGenre->setGenreName('Test Genre');

        // when
        $this->genreService->save($expectedGenre);

        // then
        $expectedGenreId = $expectedGenre->getId();
        $resultGenre = $this->entityManager->createQueryBuilder()
            ->select('genre')
            ->from(Genre::class, 'genre')
            ->where('genre.id = :id')
            ->setParameter(':id', $expectedGenreId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedGenre, $resultGenre);
    }

    /**
     * Test delete.
     *
     * @throws ORMException
     */
    public function testDelete(): void
    {
        // given
        $genreToDelete = new Genre();
        $genreToDelete->setGenreName('Test Genre');
        $this->entityManager->persist($genreToDelete);
        $this->entityManager->flush();
        $deletedGenreId = $genreToDelete->getId();

        // when
        $this->genreService->delete($genreToDelete);

        // then
        $resultGenre = $this->entityManager->createQueryBuilder()
            ->select('genre')
            ->from(Genre::class, 'genre')
            ->where('genre.id = :id')
            ->setParameter(':id', $deletedGenreId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultGenre);
    }

    /**
     * Test pagination empty list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 10;
        $expectedResultSize = 10;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $genre = new Genre();
            $genre->setGenreName('Test Genre #'.$counter);
            $this->genreService->save($genre);
            ++$counter;
        }

        // when
        $result = $this->genreService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Tests if you can delete a genre when no records exist.
     * Should return true.
     */
    public function testCanBeDeletedReturnsTrueWhenNoRecords(): void
    {
        // given
        $genre = new Genre();
        $genre->setGenreName('Test Genre');
        $this->entityManager->persist($genre);
        $this->entityManager->flush();

        // when
        $result = $this->genreService->canBeDeleted($genre);

        // then
        $this->assertTrue($result);
    }

    // There also should be a test that returns false if a record exists...
}
