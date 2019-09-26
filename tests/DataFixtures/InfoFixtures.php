<?php

declare(strict_types=1);

namespace Tests\DataFixtures;

use AppBundle\Entity\Info;
use AppBundle\Entity\Tournament;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Tests\DataFixtures\Helper\IdGenerator;

class InfoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1,1) as $i) {
            $tournamentInfo = new Info();
            IdGenerator::setIdToEntity($manager, $tournamentInfo, $i);
            $tournamentInfo->setType('singUpPayment');
            $tournamentInfo->setDescription('opis turnieju');
            /** @var Tournament $tournament */
            $tournament = $this->getReference(Tournament::class . '_' . $i);
            $tournamentInfo->setTournament($tournament);
            $manager->persist($tournamentInfo);
        }
        $manager->flush();
    }
}
