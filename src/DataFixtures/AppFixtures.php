<?php

namespace App\DataFixtures;

use App\Entity\Agreement;
use App\Entity\Category;
use App\Entity\Opinion;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $categories = [];
        $faker = Faker\Factory::create();

        for ($i = 1; $i < 11; $i++) {
            $category = Category::create($faker->name);
            $manager->persist($category);

            $categories[] = $category;
        }

        for ($i = 1; $i < 101; $i++) {
            $product = Product::create(
                $categories[$faker->numberBetween(0,2)],
                $faker->name,
                $faker->sentence(),
                $faker->numberBetween(1, 100),
                '1',
                $faker->numberBetween(1,50)
            );
            $manager->persist($product);

            for ($j = 1; $j < mt_rand(2,10); $j++) {
                $opinion = Opinion::create($product, $faker->sentence(), $faker->name, $faker->numberBetween(1, 10));
                $manager->persist($opinion);
            }
        }

        $agreement1 = Agreement::create('Czy zgadzasz się na to i tamto?',true);
        $manager->persist($agreement1);
        $manager->flush();

        $agreement2 = Agreement::create('Czy zgadzasz się na to ale bez tamtego?',true);
        $manager->persist($agreement2);
        $manager->flush();

        $agreement3 = Agreement::create('Czy masz na imię Pszemek?',false);
        $manager->persist($agreement3);
        $manager->flush();



        $manager->flush();
    }
}
