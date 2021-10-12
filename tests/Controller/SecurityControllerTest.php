<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;


// php bin/phpunit --filter SecurityControllerTest > tests/functionnalTestsResult/SecurityControllerTest.html

class SecurityControllerTest extends WebTestCase
{
    
    public function testLogginIsUp(): void
    {
        $client = static::createClient();
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

        // $form = $crawler->selectButton('Se connecter')->form([
        //     'username' => 'francis@gmail.com',
        //     'password' => '12345678'
        // ]);

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Se connecter');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['username'] = 'francis@gmail.com';
        $form['password'] = '12345678';
        
        $crawler = $client->submit($form);

        if ($client->getResponse()->isRedirection()) {
           $crawler = $client->followRedirect();
        }
       
        $this->assertEquals(200,$client->getResponse()->getStatusCode());

        // si la connexion fonctionne il ne devrait pas trouver d'alert-danger premiere variable le nombre qu'il doit trouver, seconde variable la fonction qui va compter
        $this->assertEquals(0,$crawler->filter('div.alert-danger')->count());

    }

  
}