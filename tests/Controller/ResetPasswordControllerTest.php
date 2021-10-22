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
 
    public function testSomething(): void
    {
        $client = $this->client();
        $crawler = $client->request('GET', '/reset-password/ask_mail');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
