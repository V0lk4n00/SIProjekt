<?php
/**
 * Record fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Record;

/**
 * Class RecordFixtures.
 */
class RecordFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $record = new Record();
            $record->setIdArtist($this->faker->randomNumber());
            $record->setTitle($this->faker->sentence);
            $record->setIdGenre($this->faker->randomNumber());
            $this->manager->persist($record);
        }

        $this->manager->flush();
    }
}
