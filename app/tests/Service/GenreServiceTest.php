<?php

/**
 * Genre service test.
 */
namespace App\Tests\Service;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Repository\RecordRepository;
use App\Service\GenreService;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class GenreServiceTest.
 */
class GenreServiceTest extends TestCase
{
    private GenreRepository $genreRepository;
    private RecordRepository $recordRepository;
    private GenreService $genreService;

    /**
     * Setup function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->genreRepository = $this->createMock(GenreRepository::class);
        $this->recordRepository = $this->createMock(RecordRepository::class);
        $paginator = $this->createMock(PaginatorInterface::class);

        $this->genreService = new GenreService(
            $this->genreRepository,
            $this->recordRepository,
            $paginator
        );
    }

    /**
     * Tests the save method.
     *
     * @return void
     */
    public function testSave(): void
    {
        $genre = $this->createMock(Genre::class);

        $this->genreRepository
            ->expects($this->once())
            ->method('save')
            ->with($genre);

        $this->genreService->save($genre);
    }

    /**
     * Tests the delete method.
     *
     * @return void
     */
    public function testDelete(): void
    {
        $genre = $this->createMock(Genre::class);

        $this->genreRepository
            ->expects($this->once())
            ->method('delete')
            ->with($genre);

        $this->genreService->delete($genre);
    }

    /**
     * Tests if you can delete a category when no records exist.
     * Should return true.
     *
     * @return void
     */
    public function testCanBeDeletedReturnsTrueWhenNoRecords(): void
    {
        $genre = $this->createMock(Genre::class);

        $this->recordRepository
            ->expects($this->once())
            ->method('countByGenre')
            ->with($genre)
            ->willReturn(0);

        $this->assertTrue($this->genreService->canBeDeleted($genre));
    }

    /**
     * Tests if you can delete a category when records exist.
     * Should return false.
     *
     * @return void
     */
    public function testCanBeDeletedReturnsFalseWhenRecordsExist(): void
    {
        $genre = $this->createMock(Genre::class);

        $this->recordRepository
            ->expects($this->once())
            ->method('countByGenre')
            ->with($genre)
            ->willReturn(3);

        $this->assertFalse($this->genreService->canBeDeleted($genre));
    }
}
