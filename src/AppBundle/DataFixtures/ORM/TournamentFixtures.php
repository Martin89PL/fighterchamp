<?php

declare(strict_types=1);

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TournamentFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1,10) as $i) {
            $tournament = new Tournament();
            $tournament->setName('Turniej ' . $i);
            $tournament->setStart(new \DateTime());
            $tournament->setEnd(new \DateTime());
            $tournament->setSignUpTill(new \DateTime());
            $tournament->setCapacity(rand(10, 100));
            /** @var Place $place */
            $place = $this->getReference(Place::class . '_' . $i);
            $tournament->setPlace($place);
            $manager->persist($tournament);

            $this->addReference(Tournament::class . '_' . $i, $tournament);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PlaceFixtures::class,
        ];
    }
}
