<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/product/list", name="product-list")
     */
    public function listAction(): Response
    {
        $products = $this->repository->findAllWithCategory();

        return $this->render(
            'product/list.html.twig',
            [
                'controller_name' => 'ProductController',
                'products' => $products,
            ]
        );
    }

    /**
     * @Route("/product/{id}", name="product-single")
     */
    public function singleAction(Product $product): Response
    {

        return $this->render(
            'product/single.html.twig',
            [
                'single_product' => $product,
            ]
        );
    }

}
