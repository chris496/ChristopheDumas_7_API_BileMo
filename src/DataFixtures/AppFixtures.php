<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

        // Création d'un client
        $client = new Client();
        $client->setEmail('admin@bilemo.com');
        $client->setRoles(['ROLE_CLIENT']);
        $client->setName($faker->Name());
        $client->setCreatedAt(new DateTimeImmutable());
        $client->setPassword($this->userPasswordHasher->hashPassword($client, 'password'));
        $manager->persist($client);

        // create client
        for ($i = 0; $i < 5; ++$i) {
            $client = new Client();
            $client->setCreatedAt(new DateTimeImmutable())
                ->setEmail($faker->safeEmail)
                ->setRoles(['ROLE_CLIENT'])
                ->setName($faker->Name());

            // $password = $this->encoder->hashPassword($user, 'password');

            $client->setPassword($this->userPasswordHasher->hashPassword($client, 'password'));
            $manager->persist($client);

            $listClient[] = $client;
        }

        // create user
        for ($i = 0; $i < 20; ++$i) {
            $user = new User();
            $user->setCreatedAt(new DateTimeImmutable())
                ->setEmail($faker->safeEmail)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPassword($this->userPasswordHasher->hashPassword($client, 'password'))
                ->setClient($listClient[array_rand($listClient)]);

            $manager->persist($user);
        }

        // create product
        for ($i = 0; $i < 5; ++$i) {
            $product = new Product();
            $product->setName($faker->randomElement($products))
                ->setDescription('votre nouveau smartphone')
                ->setPrice($faker->numberBetween($min = 150, $max = 499))
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
