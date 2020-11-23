<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(EntityManagerInterface $em, ProductRepository $repo):Response
    {
        $products = $repo->findBy([], [], 3);


        return $this->render('home/index.html.twig',
        [
            'products' => $products
        ]
        );
    }
}
