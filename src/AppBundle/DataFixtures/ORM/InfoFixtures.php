<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Tournament;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use AppBundle\Entity\Info;

class InfoFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1,10) as $i) {
            $tournamentInfo = new Info();
            $tournamentInfo->setType('singUpPayment');
            $tournamentInfo->setDescription('opis turnieju');
            /** @var Tournament $tournament */
            $tournament = $this->getReference(Tournament::class . '_' . $i);
            $tournamentInfo->setTournament($tournament);

            $manager->persist($tournamentInfo);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TournamentFixtures::class
        ];
    }
}
