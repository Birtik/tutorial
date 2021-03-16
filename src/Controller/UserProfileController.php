<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/user/profile", name="app_user_profile")
     * @return Response
     */
    public function userProfile(): Response
    {
        return $this->render(
            'user_profile/profile.html.twig', [
        ]);
    }

    /**
     * @Route("/order/history", name="app_order_history")
     * @return Response
     */
    public function orderHistory(): Response
    {
        return $this->render(
            'user_profile/order_history.html.twig', [
        ]);
    }

    /**
     * @Route("/user/password", name="app_change_password")
     * @return Response
     */
    public function changePassword(): Response
    {
        return $this->render(
            'user_profile/change_password.html.twig', [
        ]);
    }
}
