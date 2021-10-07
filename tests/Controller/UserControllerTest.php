<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// php bin/phpunit --filter UserControllerTest > tests/functionnalTestsResult/UserControllerTest.html
class UserControllerTest extends WebTestCase
{
    private function user()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $grabUser = $userRepository->findOneByEmail('francis@gmail.com');

        // simulate $testUser being logged in
        return $client->loginUser($grabUser);   
    }

    public function testUserIndex(): void
    {
        $crawler = $this->user()->request('GET', '/user/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des Utilisateurs');
    }

    public function testUserShow(): void
    {
        $logginUser = $this->user();
        $crawler = $logginUser->request('GET', '/user/show/5');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Visualiser l\'utilisateur');
    }

    public function testUserDelete() : void
    {
        $logginUser = $this->user();
        $crawler = $logginUser->request('POST', '/user/delete/5');

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
         }

        $this->assertResponseIsSuccessful();

        $this->assertEquals(1,$crawler->filter('div.alert-success')->count());
    }

    public function testUserEdit()
    {
        $logginUser = $this->user();
       
        $crawler = $logginUser->request('POST', '/user/3/edit');

        $buttonCrawlerNode = $crawler->selectButton('Mettre à jour');

         // retrieve the Form object for the form belonging to this button
         $form = $buttonCrawlerNode->form();

         // set values on a form object
         $form['user[displayName]'] = 'Test';
         $form['user[email]'] = 'test@gmail.com';
         $form['user[roles]'] = 'ROLE_USER';

         $crawler = $logginUser->submit($form);

         if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
         }

         $this->assertResponseIsSuccessful();
        //  $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());

         $this->assertEquals(1,$crawler->filter('div.alert-success')->count());
        
    }

    public function testUserNew()
    {
        $logginUser = $this->user();

        $crawler = $logginUser->request('POST', '/user/new');

        $buttonCrawlerNode = $crawler->selectButton('Enregistrer');

        $form = $buttonCrawlerNode->form();

         // set values on a form object
         $form['user[displayName]'] = 'Test';
         $form['user[email]'] = 'test@gmail.com';
         $form['user[roles]'] = 'ROLE_USER';
         $form['user[password][first]'] = '1HjLm;8Qet';
         $form['user[password][second]'] = '1HjLm;8Qet';

         $crawler = $logginUser->submit($form);
        
         if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
         }

        //  $this->assertResponseIsSuccessful();
        $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());

        // $this->assertEquals(1,$crawler->filter('div.alert-success')->count());
    }
}