<?php

namespace App\Controller;

use App\Repository\BasketProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    /**
     * @var BasketProductRepository
     */
    private BasketProductRepository $basketProductRepository;

    public function __construct(BasketProductRepository $basketProductRepository)
    {
        $this->basketProductRepository = $basketProductRepository;
    }
    /**
     * @Route("/basket", name="app_basket")
     */
    public function basket(): Response
    {
        $username = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        $basketProducts = $this->basketProductRepository->findAllProductsForUser($username);

        return $this->render('basket/index.html.twig', [
            'items' => $basketProducts
        ]);
    }
}
