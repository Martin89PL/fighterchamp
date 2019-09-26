<?php

declare(strict_types=1);

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\RulesetFixtures;
use Tests\DataFixtures\PlaceFixtures;
use Tests\DataFixtures\TournamentFixtures;
use Tests\DataFixtures\UserFixtures;
use Tests\DataFixtures\InfoFixtures;

class TournamentSignUpControllerTest extends DefaultControllerTest
{
    protected function setUp()
    {
        $this->loadFixtures([
            UserFixtures::class,
            TournamentFixtures::class,
            PlaceFixtures::class,
            RulesetFixtures::class,
            InfoFixtures::class
        ]);
        self::makeLoggedClient('fighter@example.com', 'password', false);
    }

    public function testUserShouldGoToOpenTournamentPage()
    {
        $this->client->request('GET', '/turnieje/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testShouldUserRegisterToTournamentAndHasDeleteRegistrationButton()
    {
        $crawler = $this->client->request('GET', '/turnieje/1/zapisy');

        $form = $crawler->filter('form[name=sign_up_tournament]')->form();

        $form['sign_up_tournament[formula]']->select('A');
        $form['sign_up_tournament[weight]']->select('46');
        $form['sign_up_tournament[trainingTime]'] = 1;

        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $deleteRegistrationBtn = $crawler->filter('button')->filter('.btn-danger');

        self::assertEquals('Skasuj moje zgłoszenie', trim($deleteRegistrationBtn->text()));
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
