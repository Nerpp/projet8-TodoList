<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// php bin/phpunit --filter SecurityControllerTest > tests/functionnalTestsResult/SecurityControllerTest.html

class SecurityControllerTest extends WebTestCase
{
    
    public function testLogginIsUp(): void
    {
        $client = static::createClient();
        // $crawler = $client->request('GET', '/login');
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        
        echo $client->getResponse()->getContent();
    }

    public function testLogginPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertSelectorTextContains('h1', 'Connection');

    }

    public function testLoggin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Connection')->form();
        $form['username'] = 'francis@gmail.com';
        $form['password'] = '12345678';
        
        $crawler = $client->submit($form);
        $client->followRedirect();
        echo $client->getResponse()->getContent();
    }
}
