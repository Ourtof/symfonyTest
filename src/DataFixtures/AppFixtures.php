<?php

namespace App\DataFixtures;


use Faker\Factory;
use Faker\Generator;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 50; $i++) { 
           
            $ingredient = new Ingredient;
            $ingredient
            ->setName($this->faker->word())
            ->setPrice(mt_rand(1, 200));
            
            //stocke l'objet et prépare la requete SQL avant de l'envoyer
            $manager->persist($ingredient);
        }

          //envoyer à la BDD
        $manager->flush();
    }
}
