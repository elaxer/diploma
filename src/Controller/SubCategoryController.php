<?php

namespace App\Controller;

use App\Entity\SubCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class SubCategoryController extends AbstractController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/subcategories/{id}", name="subcategory")
     */
    public function item(SubCategory $subCategory): Response
    {
        return new Response($this->twig->render('sub_category/index.html.twig', [
            'subCategory' => $subCategory,
            'controller_name' => 'SubCategoryController',
        ]));
    }
}
