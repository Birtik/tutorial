<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/user/profile", name="app_user_profile")
     * @return Response
     */
    public function userProfile(): Response
    {
        return $this->render(
            'user_profile/profile.html.twig',
            [
            ]
        );
    }

    /**
     * @Route("/order/history", name="app_order_history")
     * @return Response
     */
    public function orderHistory(): Response
    {
        $user = $this->getUser();
        $orders = $this->orderRepository->findAllOrdersForUser($user->getUsername());

        return $this->render(
            'user_profile/order_history.html.twig',
            [
                'orders' => $orders,
            ]
        );
    }

    /**
     * @Route("/user/password", name="app_change_password")
     * @return Response
     */
    public function changePassword(): Response
    {
        return $this->render(
            'user_profile/change_password.html.twig',
            [
            ]
        );
    }
}
