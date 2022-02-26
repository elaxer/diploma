<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    /**
     * @var OrderStatusRepository
     */
    private OrderStatusRepository $orderStatusRepository;

    public function __construct(ManagerRegistry $registry, OrderStatusRepository $orderStatusRepository)
    {
        $this->orderStatusRepository = $orderStatusRepository;

        parent::__construct($registry, Order::class);
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getSoldCount(): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o)')
            ->where('o.status = :sold_status')
            ->setParameter('sold_status', $this->orderStatusRepository->findOneBy(['id' => OrderStatus::SOLD]))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
