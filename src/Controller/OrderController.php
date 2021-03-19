<?php

namespace App\Controller;

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
     * OrderController constructor.
     * @param OrderManager $orderManager
     * @param EntityManagerInterface $em
     */
    public function __construct(OrderManager $orderManager, EntityManagerInterface $em)
    {
        $this->orderManager = $orderManager;
        $this->em = $em;
    }

    /**
     * @Route("/submit/order", name="app_submit_order")
     * @throws Exception
     */
    public function submit(): Response
    {
        $user = $this->getUser();

        try {
            $this->em->beginTransaction();
            $this->orderManager->submitOrder($user);
            $this->orderManager->clearBasket($user);
            $this->em->flush();
            $this->em->commit();
        } catch (Exception $e) {
            $this->em->rollback();
            throw $e;
        }


        return $this->render(
            'order/index.html.twig',
            [

            ]
        );
    }
}
