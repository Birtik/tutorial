<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\BasketProductType;
use App\Model\FormBasketProductModel;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use App\Service\BasketProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @Route("/product/{id}", name="app_product_single")
     * @param Request $request
     * @param BasketProductManager $basketProductManager
     * @param FormBasketProductModel $model
     * @param $id
     * @return Response
     */
    public function productSingle(Request $request, BasketProductManager $basketProductManager,FormBasketProductModel $model, $id): Response
    {
        $product = $this->productRepository->findWithCategory($id);

        if(null === $product) {
            throw new NotFoundHttpException();
        }

//        $count = $request->request->getInt('count');
        $form = $this->createForm(BasketProductType::class, $model, ['limit' => $product->getAmount()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $this->getUser();
            $basketProductManager->addProduct($user, $product, $model->getCount());
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
