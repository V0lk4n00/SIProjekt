<?php
/**
 * Task fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Author;

/**
 * Class AuthorFixtures.
 */
class AuthorFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $task = new Author();
            $task->setName($this->faker->sentence);
            $task->setSurname($this->faker->sentence);
            $task->setAlias($this->faker->sentence);
            $this->manager->persist($task);
        }

        $this->manager->flush();
    }
}
