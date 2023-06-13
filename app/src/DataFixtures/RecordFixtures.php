<?php
/**
 * Record fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Record;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class TaskFixtures.
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
            $genre = $this->getRandomReference('genres');
            $record->setGenre($genre);
            /** @var Record $author */
            $author = $this->getRandomReference('authors');
            $record->setAuthor($author);

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
     * @psalm-return array{0: GenreFixtures::class}
     * @psalm-return array{0: AuthorFixtures::class}
     */
    public function getDependencies(): array
    {
        return [GenreFixtures::class, AuthorFixtures::class];
    }
}
