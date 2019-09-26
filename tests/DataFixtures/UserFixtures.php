<?php

namespace Tests\DataFixtures;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tests\DataFixtures\Helper\IdGenerator;
use Tests\Helper\DateFactory;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $data) {
            $user = new User();
            $user = IdGenerator::setIdToEntity($manager, $user, $data['id']);
            $user->setHash($data['hash']);
            $user->setEmail($data['email']);
            $user->setName($data['name']);
            $user->setSurname('admin');
            $user->setMale(true);
            $user->setRoles($data['roles']);
            $user->setBirthDay(DateFactory::createRandomDate());
            $user->setPlainPassword($data['plainPassword']);
            $user->setType($data['type']);
            $user->setPesel($data['pesel']);
            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getData()
    {
        return [
            [
                'id' => 1,
                'email' => 'admin@admin.pl',
                'hash' => '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8',
                'name' => 'admin',
                'surname' => 'admin',
                'male' => true,
                'roles' => ['ROLE_ADMIN'],
                'birthDay' => DateFactory::createRandomDate(),
                'plainPassword' => 'password',
                'type' => 1,
                'pesel' => 21121424403
            ],
            [
                'id' => 2,
                'email' => 'fighter@example.com',
                'hash' => '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8',
                'name' => 'fighter',
                'surname' => 'fighter_surname',
                'male' => true,
                'roles' => ['ROLE_USER'],
                'birthDay' => DateFactory::createRandomDate(),
                'plainPassword' => 'password',
                'type' => 1,
                'pesel' => 63102265768
            ]
        ];
    }

    public function getOrder()
    {
        return 1;
    }
}
