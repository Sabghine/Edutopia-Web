<?php

namespace App\Repository;

use App\Entity\Costudyingtype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Costudyingtype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Costudyingtype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Costudyingtype[]    findAll()
 * @method Costudyingtype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CostudyingtypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Costudyingtype::class);
    }

    // /**
    //  * @return Costudyingtype[] Returns an array of Costudyingtype objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Costudyingtype
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
