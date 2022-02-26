<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Product;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\OrderStatus;
use App\Repository\OrderStatusRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CreateOrderService
{
    /**
     * @var OrderStatusRepository
     */
    private OrderStatusRepository $orderStatusRepository;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(OrderStatusRepository $orderStatusRepository, ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Order $order
     * @param Product[] $productDtos
     */
    public function __invoke(Order $order, array $productDtos)
    {
        $order->setStatus($this->orderStatusRepository->findOneBy(['id' => OrderStatus::ORDERED]));

        foreach ($productDtos as $productDto) {
            if (($product = $this->productRepository->findOneBy(['id' => $productDto->getId()])) === null) {
                continue;
            }

            $orderProduct = (new OrderProduct())
                ->setProduct($product)
                ->setQuantity($productDto->getQuantity())
                ->setCost($productDto->getPrice())
            ;

            $this->entityManager->persist($orderProduct);

            $order->addOrderProduct($orderProduct);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}
