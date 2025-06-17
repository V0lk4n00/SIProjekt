<?php

/**
 * User service test.
 */

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UserServiceTest.
 */
class UserServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private UserService $userService;

    /**
     * Setup function.
     */
    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $paginator = $this->createMock(PaginatorInterface::class);

        $this->userService = new UserService(
            $this->userRepository,
            $paginator
        );
    }

    /**
     * Tests the save method (email+password).
     */
    public function testSave(): void
    {
        $user = $this->createMock(User::class);

        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($user);

        $this->userService->save($user);
    }

    /**
     * Tests the email saving method (email-only).
     */
    public function testSaveEmail(): void
    {
        $user = $this->createMock(User::class);

        $this->userRepository
            ->expects($this->once())
            ->method('saveEmail')
            ->with($user);

        $this->userService->saveEmail($user);
    }
}
