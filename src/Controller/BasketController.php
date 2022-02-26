<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Service\CreateOrderService;
use JsonMapper\JsonMapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class BasketController extends AbstractController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/basket", name="basket")
     */
    public function index(): Response
    {
        return new Response($this->twig->render('basket/index.html.twig', [
            'form' => $this->createForm(OrderType::class, new Order())->createView(),
        ]));
    }

    /**
     * @Route("/basket/add-product/{id}", methods={"POST"}, name="add_product_to_basket")
     */
    public function addProduct(SessionInterface $session, Product $product): RedirectResponse
    {
        $session->set('productIdsInBasket', array_merge($session->get('productIdsInBasket', []), [$product->getId()]));

        return $this->redirectToRoute('product', ['id' => $product->getId()]);
    }

    /**
     * @Route("/basket/remove-product/{id}", methods={"POST"}, name="remove_product_from_basket")
     */
    public function removeProduct(Request $request, SessionInterface $session, Product $product): RedirectResponse
    {
        $session->set('productIdsInBasket', array_filter($session->get('productIdsInBasket', []), fn (int $id) => $id !== $product->getId()));

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * @Route("/basket/create-order", name="create_order", methods={"POST"})
     * @param Request $request
     * @param SessionInterface $session
     * @param CreateOrderService $createOrderService
     * @param JsonMapperInterface $jsonMapper
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createOrder(Request $request, SessionInterface $session, CreateOrderService $createOrderService, JsonMapperInterface $jsonMapper): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order)->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new BadRequestHttpException();
        }

        $createOrderService($order, $jsonMapper->mapArrayFromString($request->get('order')['products'], new \App\DTO\Product()));

        $session->remove('productIdsInBasket');

        return new Response($this->twig->render('basket/ordered.html.twig', ['productsInBasket' => []]));
    }
}
