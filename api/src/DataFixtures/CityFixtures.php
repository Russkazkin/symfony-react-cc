<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/cities.sql');
        $manager->getConnection()->exec($sql);
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 20;
    }
}
