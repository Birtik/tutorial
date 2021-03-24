<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BasketProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @Route("/basket", name="app_basket", methods={"GET"})
     */
    public function basket(): Response
    {
        /** @var User $user $user */
        $user = $this->getUser();
        $basketProducts = $this->basketProductRepository->findAllBasketProductsForUser($user);

        return $this->render(
            'basket/index.html.twig',
            [
                'items' => $basketProducts,
            ]
        );
    }

    /**
     * @Route("/basket/delete/{id}", name="app_basket_delete", methods={"GET"})
     * @param $id
     * @return Response
     */
    public function basketDelete($id): Response
    {
        $basketProduct = $this->basketProductRepository->find($id);
        if (null === $basketProduct) {
            throw new NotFoundHttpException();
        }
        $this->basketProductRepository->delete($basketProduct);

        return $this->redirectToRoute('app_basket');
    }


}
