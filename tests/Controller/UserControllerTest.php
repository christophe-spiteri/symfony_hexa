<?php

namespace App\Tests\Controller;

use Domain\UserDomain\UserDto;
use Infrastructure\Symfony\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private string $path = '/admin/user/';
    private $apiUser;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        //$this->createKernel(['KERNEL_CLASS'=>'src/Infrastructure/Symfony']);
        /*      $this->repository = (static::getContainer()->get('doctrine'))->getRepository(User::class);
           foreach ($this->repository->findAll() as $object) {
               $this->repository->remove($object, true);
           }
    */
        $this->apiUser = (static::getContainer()->get("Domain\UserDomain\Api\UserApi"));
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->apiUser->getListeUser());
        //$this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'user[username]' => 'Testing' . (string)time(),
            'user[Roles]'    => ['ROLE_ADMIN'],
            'user[password]' => 'Testing',
        ]);

        self::assertResponseRedirects('/admin/user/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->apiUser->getListeUser()));
    }

    public function testShow(): void
    {
        //$this->markTestIncomplete();
        $fixture = new UserDto();
        $fixture->setUsername('My Title' . (string)time());
        $fixture->setRoles(['ROLE_USER']);
        $fixture->setPassword('My Title');

        $this->apiUser->addUser($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $aleatoire = (string)time();
        $fixture   = new UserDto();
        $fixture->setUsername('Modif-' . $aleatoire);
        $fixture->setRoles(['ROLE_USER']);
        $fixture->setPassword('My Title');
        $user=$this->apiUser->addUser($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $user->getId()));
        //dd(sprintf('%s%s/edit', $this->path, $user->getId()));
        $this->client->submitForm('Update', [
            'user[username]' => $aleatoire . ' New',
            'user[Roles]'    => ['ROLE_ADMIN'],
           'user[password]' => 'azerty',
        ]);

        self::assertResponseRedirects('/admin/user/');

        $fixture = $this->apiUser->findUserById($fixture->getId());
        self::assertSame($aleatoire . ' New', $fixture->getUsername());
        self::assertSame("ROLE_ADMIN,ROLE_USER", implode(',', $fixture->getRoles()));
        // self::assertSame('', $fixture->getPassword());
    }

    public function testRemove(): void
    {
        //$this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->apiUser->getListeUser());

        $fixture = new UserDto();
        $fixture->setUsername('My Title del');
        $fixture->setRoles(['ROLE_USER']);
        $fixture->setPassword('My Title');

        $this->apiUser->addUser($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->apiUser->getListeUser()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->apiUser->getListeUser()));
        self::assertResponseRedirects('/admin/user/');
    }
}
