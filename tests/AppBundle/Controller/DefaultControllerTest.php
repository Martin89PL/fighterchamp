<?php

declare(strict_types=1);

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class DefaultControllerTest extends WebTestCase
{
    /** @var Client */
    protected $client;

    /**
     * @param string $username
     * @param string $password
     * @param bool $loginFormMethod
     */
    public function makeLoggedClient($username = 'admin@admin.pl', $password = 'password')
    {
        $this->client = $this->makeClient([
            'username' => $username,
            'password' => $password
        ]);
    }
}
