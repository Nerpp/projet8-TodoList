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
        $client = $this->user();
        $crawler = $client->request('GET', '/task/todo');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Tâche non terminé');
    }

    public function testIndexEnded(): void
    {
        $client = $this->user();
        $crawler = $client->request('GET', '/task/ended');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200,$client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Tâche terminé');
    }

    public function testTaskShow()
    {
        $client = $this->user();
        $crawler = $client->request('GET', '/task/1');

        if ($client->getResponse()->isRedirection()) {
            $crawler = $client->followRedirect();
         }

        $this->assertEquals(200,$client->getResponse()->getStatusCode());
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
        $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());

    }

    public function testEditTaskFalse()
    {
        $logginUser = $this->user();

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(['isDone'=> false]);
    
        $crawler = $logginUser->request('POST', '/task/'.$task->getId().'/edit');

        // $crawler = $logginUser->request('POST', '/task/1/edit');

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
        $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());
        $this->assertEquals(0,$crawler->filter('div.alert-failed')->count());
    }

    public function testEditTaskTrue()
    {
        $logginUser = $this->user();

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(['isDone'=> true]);
    
        $crawler = $logginUser->request('POST', '/task/'.$task->getId().'/edit');

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
        $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());
        $this->assertEquals(0,$crawler->filter('div.alert-failed')->count());
    }



    public function testDeleteTaskTodo()
    {
        
        $logginUser = $this->user();

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(['isDone'=> false]);
    
        $crawler = $logginUser->request('GET', '/task/'.$task->getId());
        // $crawler = $logginUser->request('POST', '/task/1');

        $buttonCrawlerNode = $crawler->selectButton('Supprimer');

        $form = $buttonCrawlerNode->form();

        $crawler = $logginUser->submit($form);
                
        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
         }
        
         $this->assertResponseIsSuccessful();
         $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('h1', 'Tâche non terminé');
        $this->assertEquals(0,$crawler->filter('div.alert-failed')->count());
    }

    public function testDeleteTaskUndo()
    {
        
        $logginUser = $this->user();

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(['isDone'=>true]);
    
        $crawler = $logginUser->request('GET', '/task/'.$task->getId());

        $buttonCrawlerNode = $crawler->selectButton('Supprimer');
        $form = $buttonCrawlerNode->form();

        $crawler = $logginUser->submit($form);
                
        if ($logginUser->getResponse()->isRedirection()) {
            $crawler = $logginUser->followRedirect();
         }
        
         $this->assertResponseIsSuccessful();
         $this->assertEquals(200,$logginUser->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('h1', 'Tâche terminé');
        $this->assertEquals(0,$crawler->filter('div.alert-failed')->count());
    }
}
