<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\BasketProductType;
use App\Model\FormBasketProductModel;
use App\Repository\CategoriesRepository;
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

    /**
     * @var CategoriesRepository
     */
    private CategoriesRepository $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoriesRepository $categoriesRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoriesRepository;
    }

    /**
     * @Route("/product/category/{category}", name="app_product_list", methods={"GET"})
     * @param string $category
     * @return Response
     */
    public function productList(string $category): Response
    {
        $products = $this->productRepository->findAllByCategory($category);
        $categories = $this->categoryRepository->findAll();

        return $this->render(
            'product/list.html.twig',
            [
                'products' => $products,
                'categories' => $categories,
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
    public function productSingle(
        Request $request,
        BasketProductManager $basketProductManager,
        FormBasketProductModel $model,
        $id
    ): Response {
        $product = $this->productRepository->findWithCategory($id);
        $categories = $this->categoryRepository->findAll();

        if (null === $product) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(BasketProductType::class, $model, ['limit' => $product->getAmount()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $this->getUser();
            $category = $product->getCategory();
            $originalCategoryName = str_replace(' ', '-', $category->getName());
            $basketProductManager->addBasketProduct($user, $product, $model->getAmount());

            $this->addFlash('success','Product correctly added to basket');

            return $this->redirectToRoute('app_product_list', ['category' => $originalCategoryName]);
        }

        return $this->render(
            'product/single.html.twig',
            [
                'form' => $form->createView(),
                'single_product' => $product,
                'categories' => $categories,
            ]
        );
    }

}
