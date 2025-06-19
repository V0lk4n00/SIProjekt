<?php

/**
 * Author service test.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Interface\AuthorServiceInterface;
use App\Service\AuthorService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class AuthorServiceTest.
 */
class AuthorServiceTest extends KernelTestCase
{
    /**
     * Author repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Author service.
     */
    private ?AuthorServiceInterface $authorService;

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
        $this->authorService = $container->get(AuthorService::class);
    }

    /**
     * Test save.
     *
     * @throws ORMException
     */
    public function testSave(): void
    {
        // given
        $expectedAuthor = new Author();
        $expectedAuthor->setAlias('Test Author');

        // when
        $this->authorService->save($expectedAuthor);

        // then
        $expectedAuthorId = $expectedAuthor->getId();
        $resultAuthor = $this->entityManager->createQueryBuilder()
            ->select('author')
            ->from(Author::class, 'author')
            ->where('author.id = :id')
            ->setParameter(':id', $expectedAuthorId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedAuthor, $resultAuthor);
    }

    /**
     * Test delete.
     *
     * @throws ORMException
     */
    public function testDelete(): void
    {
        // given
        $authorToDelete = new Author();
        $authorToDelete->setAlias('Test Author');
        $this->entityManager->persist($authorToDelete);
        $this->entityManager->flush();
        $deletedAuthorId = $authorToDelete->getId();

        // when
        $this->authorService->delete($authorToDelete);

        // then
        $resultAuthor = $this->entityManager->createQueryBuilder()
            ->select('author')
            ->from(Author::class, 'author')
            ->where('author.id = :id')
            ->setParameter(':id', $deletedAuthorId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultAuthor);
    }

    /**
     * Test pagination empty list.
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $author = new Author();
            $author->setAlias('Test Author #'.$counter);
            $this->authorService->save($author);
            ++$counter;
        }

        // when
        $result = $this->authorService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Tests if you can delete an author when no records exist.
     * Should return true.
     */
    public function testCanBeDeletedReturnsTrueWhenNoRecords(): void
    {
        // given
        $author = new Author();
        $author->setAlias('Test Author');
        $this->entityManager->persist($author);
        $this->entityManager->flush();

        // when
        $result = $this->authorService->canBeDeleted($author);

        // then
        $this->assertTrue($result);
    }

    // There also should be a test that returns false if a record exists...
}
