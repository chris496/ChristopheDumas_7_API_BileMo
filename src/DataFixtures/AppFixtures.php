<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;



//attention description (lorem) + price + passwordhash



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $products = ['Iphone 13', 'Samsung S22', 'Oppo find x5', 'Google pixel 6', 'Xiaomi Mi 11', 'Honor Magic 4', 'Sony Xperia 1'];

        // create user
        for ($i = 0; $i < 20; $i++) {
            $user = new User;
            $user->setCreatedAt(new DateTimeImmutable())
                ->setEmail($faker->safeEmail)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPassword('password');

            $manager->persist($user);
        }

         // create product
         for ($i = 0; $i < 5; $i++) {
            $product = new Product;
            $product->setName($faker->randomElement($products))
                ->setDescription('test')
                ->setPrice(10)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
