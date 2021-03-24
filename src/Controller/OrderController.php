<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BasketRepository;
use App\Service\OrderManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @var OrderManager
     */
    private OrderManager $orderManager;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    
    /**
     * @var BasketRepository
     */
    private BasketRepository $basketRepository;

    /**
     * OrderController constructor.
     * @param OrderManager $orderManager
     * @param EntityManagerInterface $em
     * @param BasketRepository $basketRepository
     */
    public function __construct(OrderManager $orderManager, EntityManagerInterface $em, BasketRepository $basketRepository)
    {
        $this->orderManager = $orderManager;
        $this->em = $em;
        $this->basketRepository = $basketRepository;
    }

    /**
     * @Route("/submit/order", name="app_submit_order")
     * @throws Exception
     */
    public function submit(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $basket = $this->basketRepository->findActiveUserBasket($user);

        if(null === $basket){
            return $this->redirectToRoute('app_order_history');
        }

        try {
            $this->em->beginTransaction();
            $this->orderManager->createOrder($user);
            $this->orderManager->clearUserBasket($user);
            $this->em->flush();
            $this->em->commit();
        } catch (Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->redirectToRoute('app_order_history');
    }
}
