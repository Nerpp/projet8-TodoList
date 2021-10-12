<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// php bin/phpunit --filter TaskControllerTest > tests/functionnalTestsResult/TaskControllerTest.html
class TaskControllerTest extends WebTestCase
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

    private function grabOneTodo()
    {
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        // $findTask = $taskRepository->findAll();
        // return $taskRepository->findOneByid(rand(0,count($findTask)));
        return $taskRepository->find('1');
    }

    public function testIndexTodo(): void
    {
        $crawler = $this->user()->request('GET', '/task/todo');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tâche non terminé');
    }

    public function testIndexEnded(): void
    {
        $crawler = $this->user()->request('GET', '/task/ended');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tâche terminé');
    }

    public function testTaskShow()
    {
        
        $crawler = $this->user()->request('GET', '/task/'.$this->grabOneTodo()->getId());

        $this->assertResponseIsSuccessful();
        
    }

    public function testNewTask()
    {
        $logginUser = $this->user();
        $crawler = $logginUser->request('GET', '/task/new');

        $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());

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

    }

    public function testEditTask()
    {
        $logginUser = $this->user();
        // $todoGrabbed = $this->grabOneTodo();
        $crawler = $logginUser->request('POST', '/task/1/edit');

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

    }

    public function testDeleteTask()
    {
        
        $logginUser = $this->user();
        $todoGrabbed = $this->grabOneTodo();
        // $crawler = $logginUser->request('POST', '/task/'.$todoGrabbed->getId());
         
        $crawler = $logginUser->request('POST', '/task/1');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
         }
        
         $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());

         if (!$todoGrabbed->getIsDone()) {
            $this->assertSelectorTextContains('h1', 'Tâche non terminé');
         }else {
            $this->assertSelectorTextContains('h1', 'Tâche terminé');
         }
         
         $this->assertEquals(1,$crawler->filter('div.alert-success')->count());
    }
}
