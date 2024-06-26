<?php

namespace App\Repository;

use App\Entity\OrderContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderContent>
 *
 * @method OrderContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderContent[]    findAll()
 * @method OrderContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderContent::class);
    }

    //    /**
    //     * @return OrderContent[] Returns an array of OrderContent objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OrderContent
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
