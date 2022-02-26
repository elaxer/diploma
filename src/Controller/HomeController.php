<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository): Response
    {
         return new Response($this->twig->render('home/index.html.twig', [
            'popularProducts' =>  $productRepository->findBestSelling(12),
            'newProducts' => $productRepository->findBy([], ['createdAt' => 'DESC'], 12),
        ]));
    }
}
