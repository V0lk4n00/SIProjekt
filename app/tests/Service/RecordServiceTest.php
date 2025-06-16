<?php

/**
 * Record service test.
 */

namespace App\Tests\Service;

use App\Entity\Record;
use App\Interface\AuthorServiceInterface;
use App\Interface\GenreServiceInterface;
use App\Repository\RecordRepository;
use App\Service\RecordService;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class RecordServiceTest.
 */
class RecordServiceTest extends TestCase
{
    private RecordRepository $recordRepository;
    private RecordService $recordService;

    /**
     * Setup function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->recordRepository = $this->createMock(RecordRepository::class);
        $authorService = $this->createMock(AuthorServiceInterface::class);
        $genreService = $this->createMock(GenreServiceInterface::class);
        $paginator = $this->createMock(PaginatorInterface::class);

        $this->recordService = new RecordService(
            $this->recordRepository,
            $authorService,
            $genreService,
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
        $record = $this->createMock(Record::class);

        $this->recordRepository
            ->expects($this->once())
            ->method('save')
            ->with($record);

        $this->recordService->save($record);
    }

    /**
     * Tests the delete method.
     *
     * @return void
     */
    public function testDelete(): void
    {
        $record = $this->createMock(Record::class);

        $this->recordRepository
            ->expects($this->once())
            ->method('delete')
            ->with($record);

        $this->recordService->delete($record);
    }
}
