<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// php bin/phpunit --filter TaskControllerTest > tests/functionnalTestsResult/TaskControllerTest.html
class TaskControllerTest extends WebTestCase
{

    private function userAdmin()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $grabUser = $userRepository->findOneByEmail('francis@gmail.com');

        // simulate $testUser being logged in
        return $client->loginUser($grabUser);
    }

    private function userOwner()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $grabUser = $userRepository->findOneByEmail('gerald@gmail.com');

        // simulate $testUser being logged in
        return $client->loginUser($grabUser);
    }

    private function userNotVerified()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $grabUser = $userRepository->findOneByEmail('anonyme@gmail.com');

        // simulate $testUser being logged in
        return $client->loginUser($grabUser);
    }

    private function userVerifiedNotOWner()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $grabUser = $userRepository->findOneByEmail('gerald@gmail.com');

        // simulate $testUser being logged in
        return $client->loginUser($grabUser);
    }

    private function grabOneTodo()
    {
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        return $taskRepository->find('1');
    }

    public function testIndexTodo(): void
    {
        $client = $this->userAdmin();
        $crawler = $client->request('GET', '/task/todo');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Tâche non terminé');
    }

    public function testIndexTodoNotVerified(): void
    {
        $client = $this->userNotVerified();
        $crawler = $client->request('GET', '/task/todo');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        
    }

    public function testIndexEnded(): void
    {
        $client = $this->userAdmin();
        $crawler = $client->request('GET', '/task/ended');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Tâche terminé');
    }

    public function testIndexEndedNotVerified(): void
    {
        $client = $this->userNotVerified();
        $crawler = $client->request('GET', '/task/ended');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testTaskShow()
    {
        $client = $this->userAdmin();
        $crawler = $client->request('GET', '/task/1');

        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testTaskShowNotVerified(): void
    {
        $client = $this->userNotVerified();
        $crawler = $client->request('GET', '/task/1');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testNewTask()
    {
        $logginUser = $this->userAdmin();
        $crawler = $logginUser->request('GET', '/task/new');

        $this->assertEquals(200, $logginUser->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('Enregistrer');

        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['task[title]'] = 'Titre test';
        $form['task[content]'] = 'Content test';
        $form['task[isDone]'] = true;

        $crawler = $logginUser->submit($form);

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
        }

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $logginUser->getResponse()->getStatusCode());
    }

    public function testNewTaskNotVerified(): void
    {
        $client = $this->userNotVerified();
        $crawler = $client->request('GET', '/task/new');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testEditTaskFalseAdmin()
    {
        $logginUser = $this->userAdmin();
        
        $crawler = $logginUser->request('POST', '/task/2/edit');

        $buttonCrawlerNode = $crawler->selectButton('Update');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['task[title]'] = ' Title test';
        $form['task[content]'] = 'Content test';
        $form['task[isDone]'] = false;

        $crawler = $logginUser->submit($form);

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
        }

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $logginUser->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.alert-failed')->count());
    }

    public function testEditTaskOwner()
    {
        $logginUser = $this->userOwner();
        
        $crawler = $logginUser->request('POST', '/task/13/edit');

        $buttonCrawlerNode = $crawler->selectButton('Update');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['task[title]'] = ' Title test';
        $form['task[content]'] = 'Content test';
        $form['task[isDone]'] = false;

        $crawler = $logginUser->submit($form);

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
        }

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $logginUser->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.alert-failed')->count());
    }

    public function testEditTaskTrueAdmin()
    {
        $logginUser = $this->userAdmin();
       
        $crawler = $logginUser->request('POST', '/task/1/edit');

        $buttonCrawlerNode = $crawler->selectButton('Update');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['task[title]'] = ' Title test';
        $form['task[content]'] = 'Content test';
        $form['task[isDone]'] = true;

        $crawler = $logginUser->submit($form);

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
        }

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $logginUser->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('div.alert-failed')->count());
    }

    public function testEditTaskUserNotVerified(): void
    {
        $client = $this->userNotVerified();
        $crawler = $client->request('GET', '/task/1/edit');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testEditTaskUserNotOwner(): void
    {
        $client = $this->userVerifiedNotOWner();
        $crawler = $client->request('GET', '/task/1/edit');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testEditTaskUserNotAdmin(): void
    {
        $client = $this->userVerifiedNotOWner();
        $crawler = $client->request('GET', '/task/1/edit');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }



    public function testDeleteTaskTodoAdmin()
    {

        $logginUser = $this->userAdmin();

        $crawler = $logginUser->request('GET', '/task/2');
   
        $buttonCrawlerNode = $crawler->selectButton('Supprimer');

        $form = $buttonCrawlerNode->form();

        $crawler = $logginUser->submit($form);

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
        }

         $this->assertResponseIsSuccessful();
         $this->assertEquals(200, $logginUser->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('h1', 'Tâche non terminé');
        $this->assertEquals(0, $crawler->filter('div.alert-failed')->count());
    }

    public function testDeleteTaskUndoAdmin()
    {

        $logginUser = $this->userAdmin();

        $crawler = $logginUser->request('GET', '/task/1');

        $buttonCrawlerNode = $crawler->selectButton('Supprimer');
        $form = $buttonCrawlerNode->form();

        $crawler = $logginUser->submit($form);

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
        }

         $this->assertResponseIsSuccessful();
         $this->assertEquals(200, $logginUser->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('h1', 'Tâche terminé');
        $this->assertEquals(0, $crawler->filter('div.alert-failed')->count());
    }

    public function testDeleteTaskUndoOwner()
    {

        $logginUser = $this->userOwner();

        $crawler = $logginUser->request('GET', '/task/13');

        $buttonCrawlerNode = $crawler->selectButton('Supprimer');
        $form = $buttonCrawlerNode->form();

        $crawler = $logginUser->submit($form);

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
        }

         $this->assertResponseIsSuccessful();
         $this->assertEquals(200, $logginUser->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('h1', 'Tâche terminé');
        $this->assertEquals(0, $crawler->filter('div.alert-failed')->count());
    }

    public function testDeleteTaskUserNotVerified(): void
    {
        $client = $this->userNotVerified();
        $crawler = $client->request('POST', '/task/delete/1');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskUserNotOwner(): void
    {
        $client = $this->userVerifiedNotOWner();
        $crawler = $client->request('POST', '/task/delete/1');
       
        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
        }
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

}
