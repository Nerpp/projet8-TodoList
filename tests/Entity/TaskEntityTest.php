<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskEntityTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testisTrue()
    {
        $task = new Task();
        $datetime = new \DateTime();

        $task ->setTitle('Titre test')
            ->setContent('Content test')
            ->setCreatedAt($datetime)
            ->setIsDone(true)
            ;

            $this->assertTrue($task->getTitle() === 'Titre test');
            $this->assertTrue($task->getContent() === 'Content test');
            $this->assertTrue($task->getCreatedAt() === $datetime);
            $this->assertTrue($task->getIsDone() === true);
              
    }

    public function testIsFalse()
    {
        $task = new Task();
        $datetime = new \DateTime();

        $task ->setTitle('Titre test')
            ->setContent('Content test')
            ->setCreatedAt($datetime)
            ->setIsDone(true)
            ;

            $this->assertFalse($task->getTitle() === 'false');
            $this->assertFalse($task->getContent() === 'false');
            $this->assertFalse($task->getCreatedAt() === 'false');
            $this->assertFalse($task->getIsDone() === false);
    }

    public function testIsEmpty()
    {
        $task = new Task();
        $datetime = new \DateTime();

        $task ->setTitle('Titre test')
            ->setContent('Content test')
            ->setCreatedAt($datetime)
            ->setIsDone(true)
            ;

            $this->assertEmpty($task->getTitle() );
            $this->assertEmpty($task->getContent() );
            $this->assertEmpty($task->getCreatedAt() );
            $this->assertEmpty($task->getIsDone() );
    }
}
