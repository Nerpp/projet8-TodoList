<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

// symfony console make:test
// cmd : php bin/phpunit --testdox

class UserEntityTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testIsTrue()
    {
        $user = new User();

        $datetime = new \DateTime();

        $user->setEmail('test@phpunit.fr')
            ->setDisplayName('Test')
            ->setPassword('aJn:7852Q')
            ->setIsVerified(True)
            // ->setRoles(['ROLES_ADMIN'])
            ->setAskedAt($datetime)
            ;

            $this->assertTrue($user->getEmail() === 'test@phpunit.fr');
            $this->assertTrue($user->getDisplayName() === 'Test');
            $this->assertTrue($user->getPassword() === 'aJn:7852Q');
            $this->assertTrue($user->isVerified() === True);
            // $this->assertTrue($user->getRoles() === ['ROLES_ADMIN']);
            $this->assertTrue($user->getAskedAt() === $datetime);
            
    }

    public function testIsFalse()
    {
        $user = new User();
        $datetime = new \DateTime();

        $user->setEmail('test@phpunit.fr')
            ->setDisplayName('Test')
            ->setPassword('aJn:7852Q')
            ->setIsVerified(True)
            // ->setRoles(['ROLES_ADMIN'])
            ->setAskedAt($datetime)
            ;

            $this->assertFalse($user->getEmail() === 'false');
            $this->assertFalse($user->getDisplayName() === 'false');
            $this->assertFalse($user->getPassword() === 'false');
            $this->assertFalse($user->isVerified() === false);
            // $this->assertTrue($user->getRoles() === ['ROLES_ADMIN']);
            $this->assertFalse($user->getAskedAt() === false);
    }


    public function testIsEmpty()
    {
        $user = new User();
        $this->assertEmpty($user->getEmail() );
            $this->assertEmpty($user->getDisplayName() );
            $this->assertEmpty($user->getPassword() );
            $this->assertEmpty($user->isVerified() );
            // $this->assertTrue($user->getRoles() === ['ROLES_ADMIN']);
            $this->assertEmpty($user->getAskedAt() );
    }
}
