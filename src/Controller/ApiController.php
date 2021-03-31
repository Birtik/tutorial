<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\BasketRepository;
use App\Repository\CategoriesRepository;
use App\Repository\ProductRepository;
use App\Service\BasketProductManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractFOSRestController
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
     * @var BasketRepository
     */
    private BasketRepository $basketRepository;

    /**
     * @var BasketProductManager
     */
    private BasketProductManager $basketProductManager;

    public function __construct(
        ProductRepository $productRepository,
        CategoriesRepository $categoriesRepository,
        BasketRepository $basketRepository,
        BasketProductManager $basketProductManager
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoriesRepository;
        $this->basketRepository = $basketRepository;
        $this->basketProductManager = $basketProductManager;
    }

    /**
     * @Route("/api/product/list", name="api_product_list", methods={"GET"})
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Returns all shop products",
     * )
     * @SWG\Tag(name="Product")
     * @Rest\View(serializerGroups={"list_product", "list_category"})
     */
    public function apiProductList(): Response
    {
        $products = $this->productRepository->findAllWithCategory();

        $view = $this->view();
        $view->setData($products);
        $view->setFormat('json');
        $view->getContext()->setGroups(["list_product", "list_category"]);

        return $this->handleView($view);
    }

    /**
     * @Route("/api/category/list", name="api_category_list", methods={"GET"})
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Returns all shop categories",
     * )
     * @SWG\Tag(name="Category")
     * @Rest\View(serializerGroups={"list_category"})
     */
    public function apiCategoryList(): Response
    {
        $categories = $this->categoryRepository->findAll();

        $view = $this->view();
        $view->setData($categories);
        $view->setFormat('json');
        $view->getContext()->setGroups(["list_category"]);

        return $this->handleView($view);
    }

    /**
     * @Route("/api/basket", name="api_basket", methods={"GET"})
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Return user basket",
     * )
     * @SWG\Tag(name="Basket")
     * @Rest\View(serializerGroups={"basket","list_product"})
     * @Security(name="Bearer")
     * @throws NonUniqueResultException
     */
    public function apiBasket(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $basket = $this->basketRepository->findBasketWithProductsForUser($user);

        $view = $this->view();
        $view->setData($basket);
        $view->setFormat('json');
        $view->getContext()->setGroups(["basket", "list_product"]);

        return $this->handleView($view);
    }

    /**
     * @Route("/api/basket/product/create", name="api_create_basket_product", methods={"POST"})
     * @param Request $request
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Adding product to user basket",
     * )
     * @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Basket product",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="productId", type="int", example=1),
     *              @SWG\Property(property="amount", type="int", example=1),
     *          )
     *      ),
     * @SWG\Tag(name="BasketProduct")
     * @Security(name="Bearer")
     * @throws EntityNotFoundException
     */
    public function apiCreateBasketProduct(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->basketProductManager->addBasketProductFromApi($user, $request);

        $view = $this->view();
        $view->setStatusCode(200);
        $view->setData('Product correctly added to basket');

        return $this->handleView($view);
    }
}