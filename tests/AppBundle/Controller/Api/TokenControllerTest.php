<?php
//
//namespace Tests\AppBundle\Controller\Api;
//
//use AppBundle\Entity\User;
//use Doctrine\ORM\EntityManagerInterface;
//use Liip\FunctionalTestBundle\Test\WebTestCase;
//use Symfony\Bundle\FrameworkBundle\Client;
//use Tests\Builder\UserBuilder;
//use Tests\Database;
//use Tests\DatabaseHelper;
//
//class TokenControllerTest extends WebTestCase
//{
//    /**
//     * @var Client
//     */
//    private $client;
//
//    /**
//     * @var UserBuilder
//     */
//    private $userBuilder;
//
//    /**
//     * @var DatabaseHelper
//     */
//    private $databaseHelper;
//
//    /**
//     * @var EntityManagerInterface
//     */
//    private $em;
//
//    public function setUp()
//    {
//        $this->markTestSkipped('This behaviour is not implemented');
//
//        $this->client = $this->makeClient();
//        $this->userBuilder = new UserBuilder();
//
//        $this->em = $this->getContainer()->get('doctrine')->getManager();
//
//        $this->databaseHelper = new DatabaseHelper(new Database());
//        $this->databaseHelper->truncateAllTables();
//
//        $user = $this->userBuilder
//            ->withName('admin')
//            ->withPassword('password')
//            ->build();
//
//        $this->em->persist($user);
//        $this->em->flush();
//    }
//
//    /**
//     * @covers \AppBundle\Controller\Api\TokenController
//     */
//    public function testPOSTCreateToken()
//    {
//        $this->client->request('POST','/api/tokens',[],[],[
//            'PHP_AUTH_USER' => 'admin',
//            'PHP_AUTH_PW'   => 'password',
//        ]);
//
//        $response = $this->client->getResponse();
//
//        $data = json_decode($response->getContent(), true);
//
//        $this->assertArrayHasKey('token', $data);
//
//        $this->assertStatusCode(200, $this->client);
//    }
//
//    public function testIsValid()
//    {
//        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
//
//        $user = $em->getRepository(User::class)->findOneBy(['name' => 'admin']);
//
//        $isValid = $this->client->getContainer()->get('security.password_encoder')->isPasswordValid($user, 'password');
//
//        $this->assertTrue($isValid);
//    }
//
//    /**
//     * @covers \AppBundle\Controller\Api\TokenController
//     */
//    public function testIsLogIn()
//    {
//        $token = $this->client->getContainer()
//            ->get('lexik_jwt_authentication.encoder')
//            ->encode(['userId' => 1]);
//
//        $this->client->request('GET', 'admin/api/users',[],[],[
//            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
//            'HTTP_X-Requested-With' => 'XMLHttpRequest',
//        ]);
//
//        $this->assertStatusCode(200, $this->client);
//        $this->assertJson($this->client->getResponse()->getContent());
//
//    }
//
//}
