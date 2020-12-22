<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Annonce;
use Faker;


class AnnoncesFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $annonce = new Annonce;
            $annonce->setTitle($faker->sentence(2))
                ->setDescription($faker->sentence(10))
                ->setType($faker->word(5))
                ->setCategorie($faker->word(mt_rand(5, 6)))
                ->setVille($faker->city)
                ->setContact($faker->lastName)
                ->setPrice($faker->numberBetween(10, 25000))
                ->setCreatedAt(new \DateTime());
            $manager->persist($annonce);
        }
        $manager->flush();
        // $product = new Product();
        // 


    }
}
