<?php

namespace Tests\DataFixtures;

use AppBundle\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PlaceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1,10) as $i) {
            $place = new Place();
            $place->setCapacity(10);
            $place->setCity('Testowo');
            $place->setName('Testowa');
            $place->setStreet('Test 123');
            $manager->persist($place);
            $this->addReference(Place::class . '_' . $i, $place);
        }
        $manager->flush();
    }
}