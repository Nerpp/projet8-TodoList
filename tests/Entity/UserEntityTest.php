<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{

    public function testUserUnityEntity()
    {
        $user = new User();

        $datetime = new \DateTime();

        $user->setEmail('test@phpunit.fr')
            ->setDisplayName('Test')
            ->setPassword('aJn:7852Q')
            ->setIsVerified(true)
            ->setRoles(['ROLE_ADMIN'])
            ->setAskedAt($datetime)
            ;

            $this->assertTrue($user->getEmail() === 'test@phpunit.fr');
            $this->assertTrue($user->getDisplayName() === 'Test');
            $this->assertTrue($user->getPassword() === 'aJn:7852Q');
            $this->assertTrue($user->isVerified() === true);
            $this->assertEquals(['ROLE_ADMIN','ROLE_USER'], $user->getRoles());
            $this->assertTrue($user->getAskedAt() === $datetime);
    }
}
