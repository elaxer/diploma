<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Возвращает самые продаваемые товары
     *
     * @param int $max
     * @return Product[]
     */
    public function findBestSelling(int $max): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.orderedProducts', 'op')
            ->groupBy('p.id')
            ->orderBy('COUNT(op.id)', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Возвращает рекомендуемые товары по переданному товару
     *
     * @param Product $product
     * @param int $count
     * @return Product[]
     */
    public function findRecommendedByProduct(Product $product, int $count): array
    {
        $sameCategoryProducts = $this->createQueryBuilder('p')
            ->join('p.subCategory', 's')
            ->join('s.category', 'c')
            ->andWhere('c = :category')
            ->andWhere('p <> :p1')
            ->setParameter('category', $product->getSubCategory()->getCategory())
            ->setParameter('p1', $product)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;

        if (count($sameCategoryProducts) === $count) {
            return $sameCategoryProducts;
        }

        return array_merge(
            $sameCategoryProducts,
            $this->createQueryBuilder('p')
                ->setParameter('p1', $product)
                ->setParameter('foundProducts', $sameCategoryProducts)
                ->andWhere('p <> :p1')
                ->andWhere('p NOT IN (:foundProducts)')
                ->setMaxResults($count - count($sameCategoryProducts))
                ->getQuery()
                ->getResult()
        );
    }

    /**
     * @param string $searchText
     * @return Product[]
     */
    public function findBySearchText(string $searchText): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.subCategory', 's')
            ->join('s.category', 'c')
            ->join('p.brand', 'b')
            ->orWhere('p.name LIKE :searchText')
            ->orWhere('p.model LIKE :searchText')
            ->orWhere('p.description LIKE :searchText')
            ->orWhere('s.name LIKE :searchText')
            ->orWhere('c.name LIKE :searchText')
            ->orWhere('b.name LIKE :searchText')
            ->setParameter('searchText', '%' . $searchText . '%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalCount(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
