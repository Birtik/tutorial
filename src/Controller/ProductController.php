<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @Route("/product/list", name="app_product_list")
     * @return Response
     */
    public function productList(): Response
    {
        $products = $this->repository->findAllWithCategory();

        return $this->render(
            'product/list.html.twig',
            [
                'products' => $products,
            ]
        );
    }

    /**
     * @Route("/product/{id}", name="app_product_single")
     * @param $id
     * @return Response
     */
    public function productSingle($id): Response
    {
        $product = $this->repository->findWithCategory($id);

        return $this->render(
            'product/single.html.twig',
            [
                'single_product' => $product,
            ]
        );
    }

}
