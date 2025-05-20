<?php

/**
 * Author fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Author;

/**
 * Class AuthorFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class AuthorFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'authors', function (int $i) {
            $author = new Author();
            $author->setAlias($this->faker->unique()->word);
            $author->setName($this->faker->unique()->word);
            $author->setSurname($this->faker->unique()->word);

            return $author;
        });

        $this->manager->flush();
    }
}
