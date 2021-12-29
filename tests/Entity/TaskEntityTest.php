<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskEntityTest extends TestCase
{
    public function testtaskUnityEntity()
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
}
