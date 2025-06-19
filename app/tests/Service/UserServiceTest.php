<?php

/**
 * User service test.
 */

namespace App\Tests\Service;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Interface\UserServiceInterface;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserServiceTest.
 */
class UserServiceTest extends KernelTestCase
{
    /**
     * User repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * User service.
     */
    private ?UserServiceInterface $userService;

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
        $this->userService = $container->get(UserService::class);
    }

    /**
     * Test save (email + password).
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testSave(): void
    {
        // given
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $expectedUser = new User();
        $expectedUser->setEmail('user@example.com');
        $expectedUser->setRoles([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $expectedUser->setPassword(
            $passwordHasher->hashPassword(
                $expectedUser,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($expectedUser);

        // when
        $this->userService->save($expectedUser);

        // then
        $expectedUserId = $expectedUser->getId();
        $resultUser = $this->entityManager->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.id = :id')
            ->setParameter(':id', $expectedUserId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedUser, $resultUser);
    }

    /**
     * Test save (email only).
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testSaveEmail(): void
    {
        // given
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $expectedUser = new User();
        $expectedUser->setEmail('user@example.com');
        $expectedUser->setRoles([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $expectedUser->setPassword(
            $passwordHasher->hashPassword(
                $expectedUser,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($expectedUser);

        // when
        $this->userService->saveEmail($expectedUser);

        // then
        $expectedUserId = $expectedUser->getId();
        $resultUser = $this->entityManager->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.id = :id')
            ->setParameter(':id', $expectedUserId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedUser, $resultUser);
    }

    /**
     * Test pagination empty list.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 10;
        $expectedResultSize = 10;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $passwordHasher = static::getContainer()->get('security.password_hasher');
            $user = new User();
            $user->setEmail('user#@example.com'.$counter);
            $user->setRoles([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    'p@55w0rd'
                )
            );
            $userRepository = static::getContainer()->get(UserRepository::class);
            $userRepository->save($user);
            ++$counter;
        }

        // when
        $result = $this->userService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }
}
