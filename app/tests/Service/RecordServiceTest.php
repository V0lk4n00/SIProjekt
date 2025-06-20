<?php

/**
 * Record service tests.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Entity\Enum\UserRole;
use App\Entity\Genre;
use App\Entity\Record;
use App\Entity\User;
use App\Interface\RecordServiceInterface;
use App\Repository\UserRepository;
use App\Service\RecordService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RecordServiceTest.
 */
class RecordServiceTest extends KernelTestCase
{
    /**
     * Record repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Record service.
     */
    private ?RecordServiceInterface $recordService;

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
        $this->recordService = $container->get(RecordService::class);
    }

    /**
     * Test save.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testSave(): void
    {
        // Make fake data
        $user = $this->createUser();

        $genre = new Genre();
        $genre->setGenreName('Test Genre');
        $this->entityManager->persist($genre);

        $author = new Author();
        $author->setAlias('Test Author');
        $this->entityManager->persist($author);

        $this->entityManager->flush();

        // given
        $expectedRecord = new Record();
        $expectedRecord->setTitle('Test Record');
        $expectedRecord->setGenre($genre);
        $expectedRecord->setAuthor($author);
        $expectedRecord->setRental($user);
        $expectedRecord->setInStock('1');

        $this->entityManager->persist($expectedRecord);
        $this->entityManager->flush();

        // when
        $this->recordService->save($expectedRecord);

        // then
        $expectedRecordId = $expectedRecord->getId();
        $resultRecord = $this->entityManager->createQueryBuilder()
            ->select('record')
            ->from(Record::class, 'record')
            ->where('record.id = :id')
            ->setParameter(':id', $expectedRecordId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedRecord, $resultRecord);
    }

    /**
     * Test delete.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testDelete(): void
    {
        // Make fake data
        $user = $this->createUser();

        $genre = new Genre();
        $genre->setGenreName('Test Genre');
        $this->entityManager->persist($genre);

        $author = new Author();
        $author->setAlias('Test Author');
        $this->entityManager->persist($author);

        $this->entityManager->flush();

        // given
        $recordToDelete = new Record();
        $recordToDelete->setTitle('Test Record');
        $recordToDelete->setGenre($genre);
        $recordToDelete->setAuthor($author);
        $recordToDelete->setRental($user);
        $recordToDelete->setInStock('1');

        $this->entityManager->persist($recordToDelete);
        $this->entityManager->flush();
        $deletedRecordId = $recordToDelete->getId();

        // when
        $this->recordService->delete($recordToDelete);

        // then
        $resultRecord = $this->entityManager->createQueryBuilder()
            ->select('record')
            ->from(Record::class, 'record')
            ->where('record.id = :id')
            ->setParameter(':id', $deletedRecordId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultRecord);
    }

    /*
    /**
     * Test find by id.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    /*public function testFindById(): void
    {
        // Make fake data
        $user = $this->createUser();

        $genre = new Genre();
        $genre->setGenreName('Test Genre');
        $this->entityManager->persist($genre);

        $author = new Author();
        $author->setAlias('Test Author');
        $this->entityManager->persist($author);

        $this->entityManager->flush();

        // given
        $expectedRecord = new Record();
        $expectedRecord->setTitle('Test Record');
        $expectedRecord->setGenre($genre);
        $expectedRecord->setAuthor($author);
        $expectedRecord->setRental($user);
        $expectedRecord->setInStock('1');

        $this->entityManager->persist($expectedRecord);
        $this->entityManager->flush();
        $expectedRecordId = $expectedRecord->getId();

        // when
        $resultRecord = $this->recordService->findOneById($expectedRecordId);

        // then
        $this->assertEquals($expectedRecord, $resultRecord);
    }*/

    /**
     * Test pagination empty list.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testGetPaginatedList(): void
    {
        // Make fake data
        $user = $this->createUser();

        $genre = new Genre();
        $genre->setGenreName('Test Genre');
        $this->entityManager->persist($genre);

        $author = new Author();
        $author->setAlias('Test Author');
        $this->entityManager->persist($author);

        $this->entityManager->flush();

        // given
        $page = 1;
        $dataSetSize = 10;
        $expectedResultSize = 10;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $record = new Record();
            $record->setTitle('Test Record #'.$counter);
            $record->setGenre($genre);
            $record->setAuthor($author);
            $record->setRental($user);
            $record->setInStock('1');
            $this->recordService->save($record);
            ++$counter;
        }

        // when
        $result = $this->recordService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Create fake user.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     * @return User
     */
    private function createUser(): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }
}
