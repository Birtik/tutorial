<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Opinion;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $category1 = Category::create(1, 'Kawy świata');
        $category2 = Category::create(2, 'Kawy niezwykłe');
        $category3 = Category::create(3, 'Różne');

        $product1 = Product::create($category1, 1, 'Kawa testowa 1', 'Wyjątkowa o bogatym aromacie',10,'1');
        $product2 = Product::create($category2, 2, 'Kawa testowa 2', 'Wyjątkowa o średnim aromacie',100,'2');
        $product3 = Product::create($category3, 3, 'Kawa testowa 3', 'Wyjątkowa o małym aromacie',50,'3');

        $opinion1 = Opinion::create($product1,1,"Super, polecam!",'Kuf34PL',7);
        $opinion2 = Opinion::create($product2,2,"Piękny aromat!",'KasiaZpoloneza',10);
        $opinion3 = Opinion::create($product3,3,"Co to ma być! Okropne.",'JanuszPolaczek67',1);
        $opinion4 = Opinion::create($product3,4,"Moja ulubiona.",'chytraBabazRadomia',4);

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);

        $manager->persist($product1);
        $manager->persist($product2);
        $manager->persist($product3);

        $manager->persist($opinion1);
        $manager->persist($opinion2);
        $manager->persist($opinion3);
        $manager->persist($opinion4);

        $manager->flush();
    }
}
