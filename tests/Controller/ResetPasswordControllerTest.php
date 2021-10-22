<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
        $crawler = $client->request('GET', '/reset-password/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Changer le mot de passe');
    }

    public function testAskMail()
    {
        $client = $this->client();
        $crawler = $client->request('GET', '/reset-password/');

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
}
