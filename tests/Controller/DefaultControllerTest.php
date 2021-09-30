<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// php bin/phpunit --filter DefaultControllerTest > tests/functionnalTestsResult/DefaultControllerTest.html

class DefaultControllerTest extends WebTestCase
{
    public function testDefaultisUp(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Hello World');
        echo $client->getResponse()->getContent();
    }
}
