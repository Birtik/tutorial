<?php

namespace App\Controller;

use App\Form\BasketProductType;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use App\Service\BasketProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/product/list", name="app_product_list", methods={"GET"})
     * @return Response
     */
    public function productList(): Response
    {
        $products = $this->productRepository->findAllWithCategory();

        return $this->render(
            'product/list.html.twig',
            [
                'products' => $products,
            ]
        );
    }

    /**
     * @Route("/product/{id}", name="app_product_single", methods={"GET"})
     * @param Request $request
     * @param BasketProductManager $additionBasketProduct
     * @param $id
     * @return Response
     */
    public function productSingle(Request $request, BasketProductManager $additionBasketProduct, $id): Response
    {
        $product = $this->productRepository->findWithCategory($id);

        $count = $request->get('count');
        $form = $this->createForm(BasketProductType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $additionBasketProduct->addProduct($user, $product, $count);
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
