<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ResetPasswordRequestRepository;



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

    // public function testNullToken()
    // {
        

    //     $client = $this->client();
       
    //     $crawler = $client->request('GET', '/reset-password/reset/'.null);

    //     $this->fail('No reset password token found in the URL or in the session.');  
    //     // $this->expectException(createNotFoundException::class);
    //     // $client->catchExceptions (false);
         
    // }

    public function testInvalidToken()
    {
        $client = $this->client();
       
        $crawler = $client->request('GET', '/reset-password/reset/123456789');
        
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
         }

         $this->assertEquals(0,$crawler->filter('div.alert-success')->count());


        //  $this->assertResponseIsSuccessful();
        //  $this->assertEquals(200,$client->getResponse()->getStatusCode());
         
    }

   

}
