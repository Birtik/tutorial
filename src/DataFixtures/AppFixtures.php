<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Opinion;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $categories = [];
        $faker = Faker\Factory::create();
        $categoryDefinitions = [
            ['name' => 'Kawa Zbożowa', 'code' => 'kawa-zbozowa'],
            ['name' => 'Kawa Ziarnista', 'code' => 'kawa-ziarnista'],
            ['name' => 'Kawa Bezkofeinowa', 'code' => 'kawa-bezkofeinowa'],
            ['name' => 'Herbata Czarna', 'code' => 'herbata-czarna'],
            ['name' => 'Herbata Owocowa', 'code' => 'herbata-owocowa'],
            ['name' => 'Akcesoria', 'code' => 'akcesoria'],
            ['name' => 'Yerba Mate', 'code' => 'yerba-mate'],
            ['name' => 'Herbata Ziołowa', 'code' => 'herbata-ziolowa'],
            ['name' => 'Herbata Zielona', 'code' => 'herbata-zielona'],
            ['name' => 'Ekspresy', 'code' => 'ekspresy'],
        ];

        for ($i = 0; $i < 10; $i++) {
            $definition = $categoryDefinitions[$i];
            $category = Category::create($definition['name'], $definition['code']);
            $manager->persist($category);

            $categories[] = $category;
        }

        for ($i = 1; $i < 101; $i++) {
            $product = Product::create(
                $categories[$faker->numberBetween(1, 9)],
                $faker->name,
                $faker->sentence(4),
                $faker->numberBetween(1, 100),
                '1',
                $faker->numberBetween(1, 50)
            );
            $manager->persist($product);

            for ($j = 1; $j < random_int(2, 10); $j++) {
                $opinion = Opinion::create($product, $faker->sentence(), $faker->name, $faker->numberBetween(1, 10));
                $manager->persist($opinion);
            }
        }
        $manager->flush();
    }
}
