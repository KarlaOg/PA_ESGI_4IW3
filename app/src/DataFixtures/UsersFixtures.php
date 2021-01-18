<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 2; $i++) {
            $user = (new User())
                ->setLastname($faker->lastName)
                ->setFirstname($faker->firstName)
                ->setPassword($faker->password)
                ->setEmail($faker->safeEmail)
                ->setRoles(['ROLE_USER'])
                ->setAge(new \DateTime('11-11-1998'));

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'test'
            ));

            $manager->persist($user);
        }

        $user = (new User())
            ->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setPassword($faker->password)
            ->setEmail('user@admin')
            ->setRoles(['ROLE_USER'])
            ->setAge(new \DateTime('11-11-1998'));

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'test'
        ));

        $manager->persist($user);
        $manager->flush();
    }
}
