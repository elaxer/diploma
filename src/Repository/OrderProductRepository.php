<?php

namespace App\Repository;

use App\Entity\OrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderProduct[]    findAll()
 * @method OrderProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }

    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('op')
            ->select('SUM(op.quantity)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getTotalCost(): int
    {
        return $this->createQueryBuilder('op')
            ->select('SUM(op.cost * op.quantity)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
