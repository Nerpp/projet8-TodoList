<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

// php bin/phpunit --filter RegistrationControllerTest > tests/functionnalTestsResult/RegistrationControllerTest.html

class RegistrationControllerTest extends WebTestCase
{
    private function client()
    {
        return static::createClient();
    }

    public function testRegistrationIsUp(): void
    {
        $client = $this->client();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Création Utilisateur');
    }

    public function testRegistration()
    {
        $client = $this->client();

        $crawler = $client->request('GET', '/register');

          // select the button
        $buttonCrawlerNode = $crawler->selectButton('Enregistrement');

         // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        $form['registration_form[displayName]'] = 'testControllerRegistration';
        $form['registration_form[email]'] = 'testControllerRegistration@gmail.com';
        $form['registration_form[roles]'] = 'ROLE_ADMIN';
        $form['registration_form[password][first]'] = 'hJmP@158aqgetq';
        $form['registration_form[password][second]'] = 'hJmP@158aqgetq';

        $crawler = $client->submit($form);

        $this->assertEquals(0,$crawler->filter('div.alert-danger')->count());

        if ($client->getResponse()->isRedirection()) {
           $crawler = $client->followRedirect();
        }

        $this->assertEquals(200,$client->getResponse()->getStatusCode());

    }

    public function testVerifyUserEmail()
    {
        $client = $this->client();
        $userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        $grabUser = $userRepository->findOneByEmail('testControllerRegistration@gmail.com');

        $crawler = $client->request('GET', "/verify/email/".$grabUser->getTokenValidation());

        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
         }
         
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
    }

}
