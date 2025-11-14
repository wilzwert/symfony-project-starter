<?php

namespace App\DataFixtures;

use App\Entity\SampleEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sample = new SampleEntity(1, 'Sample test entity');
        $manager->persist($sample);

        $manager->flush();
    }
}
