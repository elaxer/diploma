<?php

namespace App\EventSubscriber;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SocialNetworkRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private SessionInterface $session;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private SubCategoryRepository $subCategoryRepository;
    /**
     * @var SocialNetworkRepository
     */
    private SocialNetworkRepository $socialNetworkRepository;

    public function __construct(
        Environment $twig,
        SessionInterface $session,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        SubCategoryRepository $subCategoryRepository,
        SocialNetworkRepository $socialNetworkRepository
    ) {
        $this->twig = $twig;
        $this->session = $session;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->subCategoryRepository = $subCategoryRepository;
        $this->socialNetworkRepository = $socialNetworkRepository;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $productsInBasket = [];
        foreach ($this->session->get('productIdsInBasket', []) as $productId) {
            if (($product = $this->productRepository->findOneBy(['id' => $productId])) !== null) {
                $productsInBasket[] = $product;
            }
        }

        $this->twig->addGlobal('productsInBasket', $productsInBasket);
        $this->twig->addGlobal('categories', $this->categoryRepository->findAll());
        $this->twig->addGlobal('searchText', '');
        $this->twig->addGlobal('popularSubCategories', $this->subCategoryRepository->findMostPopular(8));
        $this->twig->addGlobal('socialNetworks', $this->socialNetworkRepository->findAll());
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
