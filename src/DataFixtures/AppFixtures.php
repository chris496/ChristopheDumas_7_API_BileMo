<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Client;
use DateTimeImmutable;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//attention description (lorem) + price + passwordhash



class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $products = ['Iphone 13', 'Samsung S22', 'Oppo find x5', 'Google pixel 6', 'Xiaomi Mi 11', 'Honor Magic 4', 'Sony Xperia 1'];

        $client = new Client();
        $client->setEmail("user@bilemo.com");
        $client->setRoles(["ROLE_USER"]);
        $client->setName($faker->Name());
        $client->setCreatedAt(new DateTimeImmutable());
        $client->setPassword($this->userPasswordHasher->hashPassword($client, "password"));
        $manager->persist($client);

        // Création d'un user admin
        $client = new Client();
        $client->setEmail("admin@bilemo.com");
        $client->setRoles(["ROLE_ADMIN"]);
        $client->setName($faker->Name());
        $client->setCreatedAt(new DateTimeImmutable());
        $client->setPassword($this->userPasswordHasher->hashPassword($client, "password"));
        $manager->persist($client);

        // create client
        for ($i = 0; $i < 5; $i++) {
            $client = new Client();
            $client->setCreatedAt(new DateTimeImmutable())
                ->setEmail($faker->safeEmail)
                ->setName($faker->Name());

            //$password = $this->encoder->hashPassword($user, 'password');

            $client->setPassword('password');
            $manager->persist($client);

            $listClient[] = $client;
        }

        // create user
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setCreatedAt(new DateTimeImmutable())
                ->setEmail($faker->safeEmail)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPassword('password')
                ->setClient($listClient[array_rand($listClient)]);

            $manager->persist($user);
        }

        // create product
        for ($i = 0; $i < 5; $i++) {
            $product = new Product();
            $product->setName($faker->randomElement($products))
                ->setDescription('test')
                ->setPrice(10)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
