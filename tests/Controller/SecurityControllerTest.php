<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;


// php bin/phpunit --filter SecurityControllerTest > tests/functionnalTestsResult/SecurityControllerTest.html

class SecurityControllerTest extends WebTestCase
{

    private function client()
    {
        return static::createClient();
    }
  
    public function testLogginIsUp(): void
    {
    
        $client = $this->client();
        $client->request('GET', '/login');
        
        $this->assertResponseIsSuccessful();
        
        echo $client->getResponse()->getContent();
    }

    public function testLogginPage()
    {
        
        $crawler = $this->client()->request('GET', '/login');
        $this->assertSelectorTextContains('h1', 'Connection');

    }

    public function testLoggin()
    {
       
        $client = $this->client();
        $crawler = $client->request('GET', '/login');

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Se connecter');

        $form = $buttonCrawlerNode->form();
        // set values on a form object
        $form['username'] = 'francis@gmail.com';
        $form['password'] = '12345678';
        
        $crawler = $client->submit($form);

        // autre alternative
        // $form = $crawler->selectButton('Se connecter')->form([
        //     'username' => 'francis@gmail.com',
        //     'password' => '12345678'
        // ]);

        if ($client->getResponse()->isRedirection()) {
           $crawler = $client->followRedirect();
        }
       
        $this->assertEquals(200,$client->getResponse()->getStatusCode());

        // si la connexion fonctionne il ne devrait pas trouver d'alert-danger premiere variable le nombre qu'il doit trouver, seconde variable la fonction qui va compter
        $this->assertEquals(0,$crawler->filter('div.alert-danger')->count());

    }

    public function testLogginAlsoConnected()
    {
        $client = $this->client();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        $grabUser = $userRepository->findOneByEmail('francis@gmail.com');

        $client = $client->loginUser($grabUser);

        $crawler = $client->request('GET', '/login');

        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
         }

         $this->assertResponseIsSuccessful();

         $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
    }
  
}