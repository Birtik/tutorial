<?php

namespace App\Controller;

use App\Form\SearchProductType;
use App\Model\SearchProductModel;
use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var CategoriesRepository
     */
    private CategoriesRepository $categoryRepository;

    /**
     * MainController constructor.
     * @param ProductRepository $productRepository
     * @param CategoriesRepository $categoriesRepository
     */
    public function __construct(ProductRepository $productRepository, CategoriesRepository $categoriesRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoriesRepository;
    }

    /**
     * @Route("/", name="main", methods={"GET","POST"})
     * @param Request $request
     * @param SearchProductModel $productModel
     * @return Response
     */
    public function index(Request $request, SearchProductModel $productModel): Response
    {
        $products = $this->productRepository->findAllWithCategory();
        $categories = $this->categoryRepository->findAll();

        $form = $this->createForm(SearchProductType::class, $productModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $productName = $productModel->getProductName();
            $products = $this->productRepository->findAllByProductNameWithCategory($productName);
        }

        return $this->render(
            'main/index.html.twig',
            [
                'form' => $form->createView(),
                'products' => $products,
                'categories' => $categories,
            ]
        );
    }
}
