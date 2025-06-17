<?php

/**
 * Author service test.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\RecordRepository;
use App\Service\AuthorService;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthorServiceTest.
 */
class AuthorServiceTest extends TestCase
{
    private AuthorRepository $authorRepository;
    private RecordRepository $recordRepository;
    private AuthorService $authorService;

    /**
     * Setup function.
     */
    protected function setUp(): void
    {
        $this->authorRepository = $this->createMock(AuthorRepository::class);
        $this->recordRepository = $this->createMock(RecordRepository::class);
        $paginator = $this->createMock(PaginatorInterface::class);

        $this->authorService = new AuthorService(
            $this->authorRepository,
            $this->recordRepository,
            $paginator
        );
    }

    /**
     * Tests the save method.
     */
    public function testSave(): void
    {
        $author = $this->createMock(Author::class);

        $this->authorRepository
            ->expects($this->once())
            ->method('save')
            ->with($author);

        $this->authorService->save($author);
    }

    /**
     * Tests the delete method.
     */
    public function testDelete(): void
    {
        $author = $this->createMock(Author::class);

        $this->authorRepository
            ->expects($this->once())
            ->method('delete')
            ->with($author);

        $this->authorService->delete($author);
    }

    /**
     * Tests if you can delete an author when no records exist.
     * Should return true.
     */
    public function testCanBeDeletedReturnsTrueWhenNoRecords(): void
    {
        $author = $this->createMock(Author::class);

        $this->recordRepository
            ->expects($this->once())
            ->method('countByAuthor')
            ->with($author)
            ->willReturn(0);

        $this->assertTrue($this->authorService->canBeDeleted($author));
    }

    /**
     * Tests if you can delete an author when records exist.
     * Should return false.
     */
    public function testCanBeDeletedReturnsFalseWhenRecordsExist(): void
    {
        $author = $this->createMock(Author::class);

        $this->recordRepository
            ->expects($this->once())
            ->method('countByAuthor')
            ->with($author)
            ->willReturn(5);

        $this->assertFalse($this->authorService->canBeDeleted($author));
    }
}
