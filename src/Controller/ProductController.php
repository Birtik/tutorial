<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\BasketProduct;
use App\Entity\User;
use App\Entity\Product;
use App\Form\BasketProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/product/list", name="app_product_list")
     * @return Response
     */
    public function productList(): Response
    {
        $products = $this->em->getRepository(Product::class)->findAllWithCategory();

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
        $product = $this->em->getRepository(Product::class)->findWithCategory($id);

        $basketProduct = new BasketProduct();
        $form = $this->createForm(BasketProductType::class, $basketProduct);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->get('security.token_storage')->getToken()->getUser();
            if($user instanceof User)
            {
                $basket = $this->em->getRepository(Basket::class)->findActiveUserBasket($user->getUsername());

                if($basket===null)
                {
                    $basket = new Basket();
                    $basket->setUser($user);
                    $this->em->persist($basket);
                }

                $basketProduct->setAmount($request->get('count'));
                $basketProduct->setProduct($product);
                $basketProduct->setBasket($basket);
                $basketProduct->setCreatedAt((new \DateTime()));
                $basketProduct->setUpdatedAt((new \DateTime()));

                $this->em->persist($basketProduct);
                $this->em->flush();

                return $this->redirectToRoute('app_basket');
            }
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
