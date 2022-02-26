<?php

namespace App\Repository;

use App\Entity\SubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubCategory[]    findAll()
 * @method SubCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubCategory::class);
    }

    public function findMostPopular(int $max): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.products', 'p')
            ->leftJoin('p.orderedProducts', 'op')
            ->groupBy('s.id')
            ->orderBy('COUNT(op.id)', 'DESC')
            ->addOrderBy('COUNT(p.id)', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }
}
