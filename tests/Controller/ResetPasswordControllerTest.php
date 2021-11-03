<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ResetPasswordRequestRepository;
use SebastianBergmann\CodeCoverage\Driver\Selector;

// php bin/phpunit --filter ResetPasswordControllerTest
class ResetPasswordControllerTest extends WebTestCase
{
    private function client()
    {
        return static::createClient();
    }

    public function testResetPasswordControllerIsUp(): void
    {
        $client = $this->client();
        $crawler = $client->request('GET', '/reset-password');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Changer le mot de passe');
    }

    public function testAskLink()
    {
        $client = $this->client();
        $crawler = $client->request('GET', '/reset-password');

        $buttonCrawlerNode = $crawler->selectButton('Demander le lien');

        // retrieve the Form object for the form belonging to this button
       $form = $buttonCrawlerNode->form();

       $form['reset_password_request_form[email]'] = 'erica@gmail.com';
       $crawler = $client->submit($form);

       if ($client->getResponse()->isRedirection()) {
        $crawler = $client->followRedirect();
     }

     $this->assertEquals(200,$client->getResponse()->getStatusCode());

    }

    public function testAskLinkNull()
    {
        $client = $this->client();
        $crawler = $client->request('GET', '/reset-password');

        $buttonCrawlerNode = $crawler->selectButton('Demander le lien');

        // retrieve the Form object for the form belonging to this button
       $form = $buttonCrawlerNode->form();

       $form['reset_password_request_form[email]'] = 'null@gmail.com';
       $crawler = $client->submit($form);

       if ($client->getResponse()->isRedirection()) {
        $crawler = $client->followRedirect();
     }

     $this->assertEquals(200,$client->getResponse()->getStatusCode());

    }

    public function testNullToken()
    {
        $client = $this->client();
       
        $crawler = $client->request('GET', '/reset-password/reset/'.null);
        
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
         }

         $this->assertResponseIsSuccessful();
        //  $this->assertEquals(200,$client->getResponse()->getStatusCode());
         
    }

    public function testInvalidToken()
    {
        $client = $this->client();
       
        $crawler = $client->request('GET', '/reset-password/reset/123456789');
        
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
         }

         $this->assertResponseIsSuccessful();
        //  $this->assertEquals(200,$client->getResponse()->getStatusCode());
         
    }

    public function testValidToken()
    {
        $client = $this->client();
        $tokenRepository = static::getContainer()->get(ResetPasswordRequestRepository::class);
        $grabToken = $tokenRepository->findOneBy(['user' => 5]);

        // $grabToken = $tokenRepository->findBy(['user' => 5]);


        dump($grabToken->getSelector());

        // $crawler = $client->request('GET', '/reset-password/reset/'.$grabToken->gethashedToken());


        // $buttonCrawlerNode = $crawler->selectButton('Reset password');

         // retrieve the Form object for the form belonging to this button
        //  $form = $buttonCrawlerNode->form();

        //  // set values on a form object
        //  $form['change_password_form[plainPassword][first]'] = '1Gh?lmN9852QA@';
        //  $form['change_password_form[plainPassword][second]'] = '1Gh?lmN9852QA@';

        //  $crawler = $client->submit($form);
        
        // if ($client->getResponse()->isRedirection()) {
        //     $crawler = $client->followRedirect();
        //  }

        //  $this->assertResponseIsSuccessful();
        //  $this->assertEquals(200,$client->getResponse()->getStatusCode());
         
    }


}
