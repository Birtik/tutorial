<?php

namespace App\Controller;

use App\Entity\BasketProduct;
use App\Form\BasketProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function productSingle(Request $request, $id): Response
    {
        $product = $this->repository->findWithCategory($id);

        $basketProduct = new BasketProduct();
        $form = $this->createForm(BasketProductType::class, $basketProduct);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $test = $form->getData();

            //ToDo
        }

        return $this->render(
            'product/single.html.twig',
            [
                'form' => $form->createView(),
                'single_product' => $product,
            ]
        );
    }

}
