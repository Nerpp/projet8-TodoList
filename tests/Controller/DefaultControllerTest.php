<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

// php bin/phpunit --filter DefaultControllerTest > tests/functionnalTestsResult/DefaultControllerTest.html

class DefaultControllerTest extends WebTestCase
{
    public function testDefaultisUp(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('francis@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
  
        echo $client->getResponse()->getContent();
    }
}
