<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ProductController extends AbstractController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/products/search", name="search_products")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function search(Request $request, ProductRepository $productRepository): Response
    {
        $searchText = $request->get('searchText', '');

        return new Response($this->twig->render('product/search_result.html.twig', [
            'searchText' => $searchText,
            'products' => $searchText !== '' ? $productRepository->findBySearchText($searchText) : [],
        ]));
    }

    /**
     * @Route("/products/{id}", name="product")
     * @param Product $product
     * @param ProductRepository $productRepository
     * @param SessionInterface $session
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function item(Product $product, ProductRepository $productRepository, SessionInterface $session): Response
    {
        $productInBasket = false;
        foreach ($session->get('productIdsInBasket', []) as $productId) {
            if ($productId === $product->getId()) {
                $productInBasket = true;

                break;
            }
        }

        $randomProducts = $productRepository->findRecommendedByProduct($product, 4);

        return new Response($this->twig->render('product/item.html.twig', [
            'product' => $product,
            'productInBasket' => $productInBasket,
            'randomProducts' => $randomProducts,
        ]));
    }
}
