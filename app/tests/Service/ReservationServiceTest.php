<?php

/**
 * Reservation service test.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Entity\Enum\UserRole;
use App\Entity\Genre;
use App\Entity\Record;
use App\Entity\Reservation;
use App\Entity\User;
use App\Interface\ReservationServiceInterface;
use App\Repository\UserRepository;
use App\Service\ReservationService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ReservationServiceTest.
 */
class ReservationServiceTest extends KernelTestCase
{
    /**
     * Reservation repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Reservation service.
     */
    private ?ReservationServiceInterface $reservationService;

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
        $this->reservationService = $container->get(ReservationService::class);
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

        $record = new Record();
        $record->setTitle('Test Record');
        $record->setGenre($genre);
        $record->setAuthor($author);
        $record->setRental($user);
        $record->setInStock('1');

        $this->entityManager->persist($record);
        $this->entityManager->flush();

        // given
        $expectedReservation = new Reservation();
        $expectedReservation->setEmail('Test Email');
        $expectedReservation->setNick('Test Nick');
        $expectedReservation->setContent('Test Content');
        $expectedReservation->setRecord($record);

        // when
        $this->reservationService->save($expectedReservation);

        // then
        $expectedReservationId = $expectedReservation->getId();
        $resultReservation = $this->entityManager->createQueryBuilder()
            ->select('reservation')
            ->from(Reservation::class, 'reservation')
            ->where('reservation.id = :id')
            ->setParameter(':id', $expectedReservationId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedReservation, $resultReservation);
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

        $record = new Record();
        $record->setTitle('Test Record');
        $record->setGenre($genre);
        $record->setAuthor($author);
        $record->setRental($user);
        $record->setInStock('1');

        $this->entityManager->persist($record);
        $this->entityManager->flush();

        // given
        $reservationToDelete = new Reservation();
        $reservationToDelete->setEmail('Test Email');
        $reservationToDelete->setNick('Test Nick');
        $reservationToDelete->setContent('Test Content');
        $reservationToDelete->setRecord($record);

        $this->entityManager->persist($reservationToDelete);
        $this->entityManager->flush();
        $deletedReservationId = $reservationToDelete->getId();

        // when
        $this->reservationService->delete($reservationToDelete);

        // then
        $resultGenre = $this->entityManager->createQueryBuilder()
            ->select('reservation')
            ->from(Reservation::class, 'reservation')
            ->where('reservation.id = :id')
            ->setParameter(':id', $deletedReservationId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultGenre);
    }

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

        $record = new Record();
        $record->setTitle('Test Record');
        $record->setGenre($genre);
        $record->setAuthor($author);
        $record->setRental($user);
        $record->setInStock('1');

        $this->entityManager->persist($record);
        $this->entityManager->flush();

        // given
        $page = 1;
        $dataSetSize = 10;
        $expectedResultSize = 10;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $reservation = new Reservation();
            $reservation->setNick('Test Nick #'.$counter);
            $reservation->setEmail('Test Email');
            $reservation->setContent('Test Content');
            $reservation->setRecord($record);
            $this->reservationService->save($reservation);
            ++$counter;
        }

        // when
        $result = $this->reservationService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Create fake user.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
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
