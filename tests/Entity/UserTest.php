<?php

namespace App\Tests\Entity;

require('vendor/autoload.php');

use App\Entity\User;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserIsValid()
    {
        $user = (new User())
            ->setFirstname('Big')
            ->setLastname('House')
            ->setEmail('bigHouse@myges.fr')
            ->setPassword('password123')
            ->setAge(new \DateTime('11-11-1998'));

        $this->assertTrue($user->isValid());
    }

    public function testUserNotAge()
    {
        $user = (new User())
            ->setFirstname('Big')
            ->setLastname('House')
            ->setEmail('bigHouse@myges.fr')
            ->setPassword('password123');

        $this->assertFalse($user->isValid());
    }

    public function testUserNotEmail()
    {
        $user = (new User())
            ->setFirstname('Big')
            ->setLastname('House')
            ->setPassword('password123')
            ->setAge(new \DateTime('11-11-1998'));

        $this->assertFalse($user->isValid());
    }

    public function testUserNotFirstname()
    {
        $user = (new User())
            ->setLastname('House')
            ->setEmail('bigHouse@myges.fr')
            ->setPassword('password123')
            ->setAge(new \DateTime('11-11-1998'));

        $this->assertFalse($user->isValid());
    }

    public function testUserNotLastname()
    {
        $user = (new User())
            ->setFirstname('Big')
            ->setEmail('bigHouse@myges.fr')
            ->setPassword('password123')
            ->setAge(new \DateTime('11-11-1998'));

        $this->assertFalse($user->isValid());
    }

    public function testUserNotValidPasswordLengthMin()
    {
        $user = (new User())
            ->setFirstname('Big')
            ->setLastname('House')
            ->setEmail('bigHouse@myges.fr')
            ->setPassword('12')
            ->setAge(new \DateTime('11-11-1998'));

        $this->assertFalse($user->isValid());
    }

    public function testUserNotValidPasswordLengthMax()
    {
        $long = str_repeat("tropLong", 10);
        $user = (new User())
            ->setFirstname('Big')
            ->setLastname('House')
            ->setEmail('bigHouse@myges.fr')
            ->setPassword($long)
            ->setAge(new \DateTime('11-11-1998'));

        $this->assertFalse($user->isValid());
    }

    public function testUserNotValidEmail()
    {
        $user = (new User())
            ->setFirstname('Big')
            ->setLastname('House')
            ->setEmail('bigHouse')
            ->setPassword('password123')
            ->setAge(new \DateTime('11-11-1998'));

        $this->assertFalse($user->isValid());
    }
}
