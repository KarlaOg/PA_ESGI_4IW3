<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Offer;
use App\Entity\Influencer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = [];
        $influcers = [];
        $brands = [];
        $applications = [];

        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, "password");
        $admin->setEmail("admin@admin.com")
            ->setFirstname("Admin")
            ->setLastname("Admin")
            ->setPassword($hash)
            ->setAge($faker->dateTime())
            ->setRoles(['ROLE_ADMIN', 'ROLE_INFLUENCEUR']);
        $manager->persist($admin);
        $influencer = new Influencer();
        $influencer->setUserId($admin)
            ->setDescription($faker->realText())
            ->setUsername($faker->userName())
            ->setSiret($faker->numberBetween(10, 2000))
            ->setName($faker->name())
            ->setSocialNetwork([
                'Website' => 'https://admin.com',
                'Instagram' => 'https://instagram.com/admin',
                'Tiktok' => 'https://tiktok/admin',
                'Facebook' => 'https://facebook/admin',
                'Youtube' => 'https://youtube.com/admin',
                'Twitter' => 'https://twitter/admin',
                'Twitch' => 'https://twitch/admin'
            ])
            ->setUpdatedAt($faker->dateTime());
        $influcers[] = $influencer;
        $manager->persist($influencer);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail("influencer$u@gmail.com")
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPassword($hash)
                ->setAge($faker->dateTime())
                ->setRoles(['ROLE_INFLUENCEUR']);
            $users[] = $user;

            $manager->persist($user);

            $influencer = new Influencer();
            $influencer->setUserId($faker->unique()->randomElement($users))
                ->setDescription($faker->realText())
                ->setUsername($faker->userName())
                ->setSiret($faker->numberBetween(10, 2000))
                ->setName($faker->name())
                ->setType(["Gamer", "Instagramer", "Blogueur"])
                ->setSocialNetwork([
                    'Website' => 'https://' . $u . '.com',
                    'Instagram' => 'https://instagram.com/' . $u,
                    'Tiktok' => 'https://tiktok/' . $u,
                    'Facebook' => 'https://facebook/' . $u,
                    'Youtube' => 'https://youtube.com/' . $u . 'com',
                    'Twitter' => 'https://twitter/' . $u,
                    'Twitch' => 'https://twitch/' . $u
                ])
                ->setUpdatedAt($faker->dateTime());
            $influcers[] = $influencer;

            $manager->persist($influencer);
        }

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail("brand$u@gmail.com")
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPassword($hash)
                ->setAge($faker->dateTime())
                ->setRoles(['ROLE_MARQUE']);
            $users[] = $user;

            $manager->persist($user);

            $brand = new Brand();
            $brand->setUserId($faker->unique()->randomElement($users))
                ->setDescription($faker->realText())
                ->setUsername($faker->userName())
                ->setSiret($faker->numberBetween(10, 2000))
                ->setName($faker->name())
                ->setField([
                    "Agroalimentaire",
                    "Bois - Papier - Carton - Imprimerie",
                    "Édition - Communication - Multimédia",
                    "Industrie pharmaceutique",
                    "Transports - Logistique"
                ])
                ->setSocialNetwork([
                    'Website' => 'https://' . $u . '.com',
                    'Instagram' => 'https://instagram.com/' . $u,
                    'Tiktok' => 'https://tiktok/' . $u,
                    'Facebook' => 'https://facebook/' . $u,
                    'Youtube' => 'https://youtube.com/' . $u . 'com',
                    'Twitter' => 'https://twitter/' . $u,
                    'Twitch' => 'https://twitch/' . $u
                ])

                ->setUpdatedAt($faker->dateTime());
            $brands[] = $brand;

            $manager->persist($brand);
        }
        for ($u = 0; $u < 5; $u++) {
            $offer = new Offer();
            $offer->setBrandId($faker->randomElement($brands))
                ->setDescription($faker->realText())
                ->setName($faker->title())
                ->setDateEnd($faker->dateTimeThisMonth())
                ->setDateStart($faker->dateTimeThisYear())
                ->setField(["Gaming", "Streaming", "Lifestyle", "Exploration"])

                ->setDateCreation($faker->dateTime());
            $offers[] = $offer;

            $manager->persist($offer);
        }

        for ($u = 0; $u < 5; $u++) {
            $application = new Application();
            $offer->addApplication($application);
            $application->setOffer($faker->randomElement($offers))
                ->addInfluencerId($faker->randomElement($influcers))
                ->setStatus("pending");
            $applications[] = $application;

            $manager->persist($application);
        }

        $manager->flush();
    }
}
