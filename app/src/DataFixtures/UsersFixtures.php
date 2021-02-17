<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $influencer = ['ROLE_INFLUENCER'];
        $marque = ['ROLE_MARQUE'];
        for ($i = 0; $i < 10; $i++) {
            $num = rand(1, 100);
            $ranType = $num > 50 ? $influencer : $marque;
            $user = (new User());
            $hash = $this->encoder->encodePassword($user, "password");

            $user->setLastname($faker->lastName)
                ->setFirstname($faker->firstName)
                ->setPassword($faker->password)
                ->setEmail($faker->safeEmail)
                ->setAge(new \DateTime('11-11-1998'))
                ->setImageUser("http://placeholder.com")
                ->setNombreAbonnes(rand(50, 9000))
                ->setType($ranType)
                ->setUpdatedAt($faker->dateTime())
                ->setLiens(array(
                    "youtube"  => "youtube.com/$faker->lastName",
                    "instagram" => "instagram.com/$faker->lastName",
                ));

            $user->setPassword($hash);

            $manager->persist($user);
        }

        $admin = (new User());
        $hash = $this->encoder->encodePassword($admin, "password");

        $admin->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setPassword($hash)
            ->setEmail('admin@gmail.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setAge(new \DateTime('11-11-1998'))
            ->setImageUser("http://placeholder.com")
            ->setNombreAbonnes(rand(50, 9000))
            ->setType(['ROLE_MARQUE'])
            ->setUpdatedAt($faker->dateTime())
            ->setLiens(array(
                "youtube"  => "youtube.com/$faker->lastName",
                "instagram" => "youtube.com/$faker->lastName",
            ));


        $manager->persist($admin);

        $manager->flush();
    }
}
