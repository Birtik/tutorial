<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/", name="main", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $products = $this->productRepository->findAllWithCategory();
        $categories = $this->categoryRepository->findAll();

        return $this->render(
            'main/index.html.twig',
            [
                'products' => $products,
                'categories' => $categories,
            ]
        );
    }
}
