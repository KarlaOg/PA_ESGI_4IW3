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

        //ADMIN
        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, "password");
        $admin->setEmail("admin@admin.com")
            ->setFirstname("admin")
            ->setLastname("admin")
            ->setPassword($hash)
            ->setAge($faker->dateTime())
            ->setIsAdmin(true)
            ->setRoles(['ROLE_INFLUENCEUR', 'ROLE_ADMIN']);
        $manager->persist($admin);

        $influencer = new Influencer();
        $username = $faker->userName();
        $exp = "/[0-9]/";
        $name = preg_replace($exp, "", $username);
        $resName = str_replace(".", " ", $name);
        $influencer->setUser($admin)
            ->setDescription($faker->realText())
            ->setUsername($username)
            ->setSiret(75152319200027)
            ->setName($resName)
            ->setType(["Gamer", "Instagramer", "Blogueur"])
            ->setSocialNetwork([
                'Website' => 'https://' . $username . '.com',
                'Instagram' => 'https://instagram/' .  $username . '.com',
                'Tiktok' => 'https://tiktok/' .  $username . '.com',
                'Facebook' => 'https://facebook/' .  $username . '.com',
                'Youtube' => 'https://youtube/' .  $username . 'com',
                'Twitter' => 'https://twitter/' .  $username . '.com',
                'Twitch' => 'https://twitch/' .  $username . '.com',
            ])
            ->setUpdatedAt($faker->dateTime());
        $influcers[] = $influencer;
        $manager->persist($influencer);


        //INFLUENCER USER
        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail("influencer$u@gmail.com")
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPassword($hash)
                ->setAge($faker->dateTime())
                ->setIsAdmin(false)
                ->setRoles(['ROLE_INFLUENCEUR']);
            $users[] = $user;

            $manager->persist($user);

            $influencer = new Influencer();
            $username = $faker->userName();
            $exp = "/[0-9]/";
            $name = preg_replace($exp, "", $username);
            $resName = str_replace(".", " ", $name);
            $influencer->setUser($faker->unique()->randomElement($users))
                ->setDescription($faker->realText())
                ->setUsername($username)
                ->setSiret(75152319200027)
                ->setName($resName)
                ->setType(["Gamer", "Instagramer", "Blogueur"])
                ->setSocialNetwork([
                    'Website' => 'https://' . $username . '.com',
                    'Instagram' => 'https://instagram/' .  $username . '.com',
                    'Tiktok' => 'https://tiktok/' .  $username . '.com',
                    'Facebook' => 'https://facebook/' .  $username . '.com',
                    'Youtube' => 'https://youtube/' .  $username . 'com',
                    'Twitter' => 'https://twitter/' .  $username . '.com',
                    'Twitch' => 'https://twitch/' .  $username . '.com',
                ])
                ->setUpdatedAt($faker->dateTime());
            $influcers[] = $influencer;

            $manager->persist($influencer);
        }


        //BRAND USER
        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail("brand$u@gmail.com")
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPassword($hash)
                ->setAge($faker->dateTime())
                ->setIsAdmin(false)
                ->setRoles(['ROLE_MARQUE']);
            $users[] = $user;

            $manager->persist($user);

            $brand = new Brand();
            $username = $faker->company();
            $name = str_replace(" ", "-", $username);
            $resName = str_replace("-", " ", $name);
            $brand->setUser($faker->unique()->randomElement($users))
                ->setDescription($faker->realText())
                ->setUsername($name)
                ->setSiret(75152319200027)
                ->setName($resName)
                ->setField([
                    "Agroalimentaire",
                    "Bois - Papier - Carton - Imprimerie",
                    "Édition - Communication - Multimédia",
                    "Industrie pharmaceutique",
                    "Transports - Logistique"
                ])
                ->setSocialNetwork([
                    'Website' => 'https://' . $name . '.com',
                    'Instagram' => 'https://instagram/' .  $name . '.com',
                    'Tiktok' => 'https://tiktok/' .  $name . '.com',
                    'Facebook' => 'https://facebook/' .  $name . '.com',
                    'Youtube' => 'https://youtube/' .  $name . 'com',
                    'Twitter' => 'https://twitter/' .  $name . '.com',
                    'Twitch' => 'https://twitch/' .  $name . '.com',
                ])

                ->setUpdatedAt($faker->dateTime());
            $brands[] = $brand;

            $manager->persist($brand);
        }

        //CREATION OFFER
        for ($u = 0; $u < 5; $u++) {
            $offer = new Offer();
            $offer->setBrandId($faker->randomElement($brands))
                ->setDescription($faker->text($maxNbChars = 200))
                ->setName($faker->jobTitle())
                ->setDateStart($faker->dateTimeBetween('+1 days', '+10 days'))
                ->setDateEnd($faker->dateTimeBetween('+11 days', '+2 years'))
                ->setField(["Gaming", "Streaming", "Lifestyle", "Exploration"])

                ->setDateCreation($faker->dateTime());
            $offers[] = $offer;

            $manager->persist($offer);
        }

        //CREATION  APPLICATION OFFER
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
