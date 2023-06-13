<?php
/**
 * Genre fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Genre;

/**
 * Class GenreFixtures.
 */
class GenreFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $genre = new Genre();
            $genre->setGenreName($this->faker->sentence);
            $this->manager->persist($genre);
        }

        $this->manager->flush();
    }
}
