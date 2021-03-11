<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Opinion;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/product/list", name="product-list")
     */
    public function listAction(): Response
    {
        $products = $this->em->getRepository(Product::class)->findAllWithCategory();


        return $this->render(
            'product/list.html.twig',
            [
                'controller_name' => 'ProductController',
                'products' => $products,
            ]
        );
    }

    /**
     * @Route("/product/{productId}", name="product-single")
     */
    public function singleAction($productId): Response
    {

        $product = $this->em->getRepository(Product::class)->findWithCategory($productId);

        return $this->render(
            'product/single.html.twig',
            [
                'single_product' => $product,
            ]
        );
    }

}
