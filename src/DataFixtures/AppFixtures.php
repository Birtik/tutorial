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

    public function slug(string $categoryName): string
    {
        $pattern = ['ą' => 'a', 'ó' => 'o', 'ł' => 'l', 'ż' => 'z', 'ź' => 'z', 'ń' => 'n', ' ' => '-'];
        return strtolower(strtr($categoryName,$pattern));
    }

    public function load(ObjectManager $manager)
    {
        $categories = [
            0 => 'Kawa zborzowa',
            1 => 'Kawa ziarnista',
            2 => 'Kawa bezkofeinowa',
            3 => 'Herbata czarna',
            4 => 'Herbata owocowa',
            5 => 'Akcesoria',
            6 => 'Yerba Mate',
            7 => 'Herbata ziołowa',
            8 => 'Herbata Zielona',
            9 => 'Ekspresy',
        ];

        $arrayCategories = [];

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $categoryName = $categories[$i];
            $categoryCode = $this->slug($categoryName);
            $category = Category::create($categoryName, $categoryCode);
            $manager->persist($category);

            $arrayCategories[] = $category;
        }

        for ($i = 1; $i < 101; $i++) {
            $product = Product::create(
                $arrayCategories[$faker->numberBetween(1, 9)],
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
