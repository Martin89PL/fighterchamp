<?php

declare(strict_types=1);

namespace Tests\DataFixtures;

use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Tests\DataFixtures\Helper\IdGenerator;

class TournamentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $data) {
            $tournament = new Tournament();
            IdGenerator::setIdToEntity($manager, $tournament, $data['id']);
            $tournament->setName($data['name']);
            $tournament->setStart($data['start']);
            $tournament->setEnd($data['end']);
            $tournament->setSignUpTill($data['signUpTill']);
            $tournament->setCapacity($data['capacity']);
            /** @var Place $place */
            $place = $this->getReference(Place::class . '_' . $data['id']);
            $tournament->setPlace($place);
            $manager->persist($tournament);

            $this->addReference(Tournament::class . '_' . $data['id'], $tournament);
        }
        $manager->flush();
    }

    private function getData()
    {
        return [
            [
                'id' => 1,
                'name' => 'Turniej 1',
                'start' => new \DateTime(),
                'end' => (new \DateTime())->modify('+ 1day'),
                'signUpTill' => (new \DateTime())->modify('+ 1day'),
                'capacity' => 20,
            ]
        ];
    }
}

