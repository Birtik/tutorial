<?php

namespace App\Controller;

use App\Entity\Category;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
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
     * @Route("/", name="main")
     */
    public function index(): Response
    {

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);

    }
}
