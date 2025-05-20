<?php

/**
 * Genre fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Genre;

/**
 * Class GenreFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class GenreFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'genres', function (int $i) {
            $genre = new Genre();
            $genre->setGenreName($this->faker->unique()->word);

            return $genre;
        });

        $this->manager->flush();
    }
}
