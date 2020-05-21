<?php

namespace App\Repository;

use App\Entity\IsMonth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IsMonth|null find($id, $lockMode = null, $lockVersion = null)
 * @method IsMonth|null findOneBy(array $criteria, array $orderBy = null)
 * @method IsMonth[]    findAll()
 * @method IsMonth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IsMonthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IsMonth::class);
    }

    // /**
    //  * @return IsMonth[] Returns an array of IsMonth objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IsMonth
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
