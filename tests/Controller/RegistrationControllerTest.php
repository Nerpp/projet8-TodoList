<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// php bin/phpunit --filter RegistrationControllerTest > tests/functionnalTestsResult/RegistrationControllerTest.html

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistrationIsUp(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Création Utilisateur');
    }

    public function testRegistration()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

          // select the button
        $buttonCrawlerNode = $crawler->selectButton('Enregistrement');

         // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        $form['registration_form[displayName]'] = 'testController2';
        $form['registration_form[email]'] = 'testController@gmail.com2';
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

    // public function testMailIsSentAndContentIsOk()
    // {
    //     $client = static::createClient();

    //     // enables the profiler for the next request (it does nothing if the profiler is not available)
    //     $client->enableProfiler();

    //     $crawler = $client->request('POST', '/register');

    //     $mailCollector = $client->getProfile()->getCollector('swiftmailer');

    //     // checks that an email was sent
    //     $this->assertSame(1, $mailCollector->getMessageCount());

    //     $collectedMessages = $mailCollector->getMessages();
    //     $message = $collectedMessages[0];

    //     // Asserting email data
    //     $this->assertInstanceOf('Swift_Message', $message);
    //     $this->assertSame('Hello Email', $message->getSubject());
    //     $this->assertSame('send@example.com', key($message->getFrom()));
    //     $this->assertSame('recipient@example.com', key($message->getTo()));
    //     $this->assertSame(
    //         'You should see me from the profiler!',
    //         $message->getBody()
    //     );
    // }
}
