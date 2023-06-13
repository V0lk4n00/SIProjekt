<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setUsername($this->faker->sentence);
            $user->setEmail($this->faker->sentence);
            $user->setPassword($this->faker->sentence);
            $this->manager->persist($user);
        }

        $this->manager->flush();
    }
}
