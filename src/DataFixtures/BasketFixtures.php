<?php declare(strict_types=1);


namespace App\DataFixtures;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class BasketFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $category = Category::create("Koszyk",'Koszyk');
        $manager->persist($category);

        $product = Product::create(
            $category,
            "Koszyk Item",
            "Koszyk desc",
            1,
            '1',
            5
        );
        $manager->persist($product);

        $user = new User();
        $user->setEmail('test12345@wp.pl');
        $user->setFirstName('Bartosz');
        $user->setLastName('Widera');
        $user->setEnabled(true);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'shop'
            )
        );
        $manager->persist($user);

        $basket = new Basket();
        $basket->setUser($user);
        $basket->setCreatedAt(new \DateTime());

        $basketItem1 = new BasketProduct();
        $basketItem1->setBasket($basket);
        $basketItem1->setUpdatedAt((new \DateTime()));
        $basketItem1->setCreatedAt((new \DateTime()));
        $basketItem1->setProduct($product);
        $basketItem1->setAmount(1);

        $manager->persist($basket);
        $manager->persist($basketItem1);

        $manager->flush();
    }
}