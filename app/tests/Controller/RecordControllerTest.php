<?php

/**
 * Record controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\Enum\UserRole;
use App\Entity\Genre;
use App\Entity\Record;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RecordControllerTest.
 */
class RecordControllerTest extends WebTestCase
{
    /**
     * Default test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/ebay/records';

    /**
     * Create test route.
     *
     * @const string
     */
    public const TEST_ROUTE_CREATE = '/ebay/records/create';

    /**
     * Test client.
     */
    private KernelBrowser $httpClient;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route for anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for admin user.
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE_CREATE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for anonymous user.
     */
    public function testCreateRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE_CREATE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Show route test.
     *
     * @throws OptimisticLockException
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws ContainerExceptionInterface
     */
    public function testShowRoute(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($user);

        $recordId = $this->createRecordOwnedBy($user);

        $this->httpClient->request('GET', '/ebay/records/'.$recordId->getId());

        $this->assertResponseIsSuccessful();
    }

    /**
     * Edit route test.
     *
     * @throws OptimisticLockException
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws ContainerExceptionInterface
     */
    public function testEditRoute(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($user);

        $recordId = $this->createRecordOwnedBy($user)->getId();

        $this->httpClient->request('GET', "/ebay/records/$recordId/edit");

        $this->assertResponseIsSuccessful();
    }

    /**
     * Edit quantity route test.
     *
     * @throws OptimisticLockException
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws ContainerExceptionInterface
     */
    public function testEditQuantityRoute(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($user);

        $recordId = $this->createRecordOwnedBy($user)->getId();

        $this->httpClient->request('GET', "/ebay/records/$recordId/edit/quantity");

        $this->assertResponseIsSuccessful();
    }

    /**
     * Delete route test.
     *
     * @throws OptimisticLockException
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws ContainerExceptionInterface
     */
    public function testDeleteRoute(): void
    {
        $user = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->httpClient->loginUser($user);

        $recordId = $this->createRecordOwnedBy($user)->getId();

        $this->httpClient->request('GET', "/ebay/records/$recordId/delete");

        $this->assertResponseIsSuccessful();
    }

    /**
     * Create fake user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    private function createUser(array $roles): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles($roles);
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

    /**
     * Create fake record.
     *
     * @param User $user
     * @return Record
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createRecordOwnedBy(User $user): Record
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $genre = new Genre();
        $genre->setGenreName('Test Genre');
        $entityManager->persist($genre);

        $author = new Author();
        $author->setAlias('Test Author');
        $entityManager->persist($author);

        $record = new Record();
        $record->setTitle('Test Record');
        $record->setAuthor($author);
        $record->setGenre($genre);
        $record->setRental($user);
        $record->setInStock(1);

        $entityManager->persist($record);
        $entityManager->flush();

        return $record;
    }
}
