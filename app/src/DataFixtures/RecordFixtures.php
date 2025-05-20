<?php

/**
 * Record fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Genre;
use App\Entity\Record;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Generator;

/**
 * Class RecordFixtures.
 */
class RecordFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'records', function (int $i) {
            $record = new Record();
            $record->setTitle($this->faker->sentence);
            /** @var Record $genre */
            $genre = $this->getRandomReference('genres', Genre::class);
            /* @var Genre $genre */
            $record->setGenre($genre);
            /** @var Record $author */
            $author = $this->getRandomReference('authors', Author::class);
            /* @var Author $author */
            $record->setAuthor($author);
            /** @var User $rental */
            $rental = $this->getRandomReference('admins', User::class);
            $record->setRental($rental);
            $record->setInStock(1);

            return $record;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: GenreFixtures::class, 1: AuthorFixtures, 2:UserFixtures}
     */
    public function getDependencies(): array
    {
        return [GenreFixtures::class, AuthorFixtures::class, UserFixtures::class];
    }
}
