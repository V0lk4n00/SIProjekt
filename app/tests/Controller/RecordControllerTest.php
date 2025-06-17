<?php

/**
 * Record controller test.
 */

namespace App\Tests\Controller;

// use App\Entity\Enum\UserRole;
// use App\Entity\User;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RecordControllerTest.
 */
class RecordControllerTest extends WebTestCase
{
    /**
     * Records /ebay/records route test.
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ebay/records');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Records /ebay/records/create route test when user isn't logged in.
     */
    public function testCreateRouteRedirectsWhenUnauthenticated(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ebay/records/create');

        $this->assertResponseRedirects('/login');
    }
    /*
     * Records /ebay/records/create route test when user isn't logged in.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    /*public function testCreateRouteAccessibleWhenAuthenticated(): void
    {
        $client = static::createClient();
        $this->createAndLoginUser($client);

        $client->request('GET', '/ebay/records/create');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Password hasher.
     */
    /*protected UserPasswordHasherInterface $passwordHasher;

    /**
     * This is similar to how loadData in UserFixtures works.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @param $client
     */
    /*private function createAndLoginUser($client): void
    {
        $user = new User();
        $user->setEmail('user1@example.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                'user1234'
            )
        );
        $user->setRoles([UserRole::ROLE_USER->value]);

        $em = self::getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $client->loginUser($user);
    }*/
}
