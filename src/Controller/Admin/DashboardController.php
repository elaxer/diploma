<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\SocialNetwork;
use App\Entity\SubCategory;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractDashboardController
{
    private TranslatorInterface $t;
    private OrderProductRepository $orderProductRepository;
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;

    public function __construct(
        TranslatorInterface $t,
        OrderRepository $orderRepository,
        OrderProductRepository $orderProductRepository,
        ProductRepository $productRepository
    ) {
        $this->t = $t;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $lastOrderedProducts = $this->orderProductRepository->findBy([], ['id' => 'DESC'], 8);
        $productsCount = $this->productRepository->getTotalCount();
        $orderedSoldCount = $this->orderRepository->getSoldCount();
        $orderedCount = $this->orderRepository->getTotalCount();
        $orderedProductsCount = $this->orderProductRepository->getTotalCount();
        $orderedProductsTotalCost = $this->orderProductRepository->getTotalCost();

        return $this->render('admin/dashboard/index.html.twig', [
            'dashboard_controller_filepath' => (new \ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new \ReflectionClass(static::class))->getShortName(),

            'productsCount' => $productsCount,
            'orderedCount' => $orderedCount,
            'orderedSoldCount' => $orderedSoldCount,
            'orderedProductsCount' => $orderedProductsCount,
            'orderedProductsTotalCost' => $orderedProductsTotalCost,
            'lastOrderedProducts' => $lastOrderedProducts,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->t->trans('Панель администратора'))
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute($this->t->trans('Главная страница'), 'fa fa-home', 'home');
        yield MenuItem::linkToCrud($this->t->trans('Заказы'), 'fas fa-shipping-fast', Order::class);
        yield MenuItem::linkToCrud($this->t->trans('Марки'), 'fas fa-apple-alt', Brand::class);
        yield MenuItem::linkToCrud($this->t->trans('Категории'), 'fas fa-list-ul', Category::class);
        yield MenuItem::linkToCrud($this->t->trans('Подкатегории'), 'fas fa-list', SubCategory::class);
        yield MenuItem::linkToCrud($this->t->trans('Товары'), 'fas fa-mobile-alt', Product::class);
        yield MenuItem::linkToCrud($this->t->trans('Социальные сети'), 'fab fa-facebook', SocialNetwork::class);
    }
}
