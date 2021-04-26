<?php

namespace App\Repository;

use App\Entity\WorkDone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkDone|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkDone|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkDone[]    findAll()
 * @method WorkDone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkDoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkDone::class);
    }

    // /**
    //  * @return WorkDone[] Returns an array of WorkDone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkDone
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function countStatus($value,$idActivity)
    {
        return (int)$this->createQueryBuilder('a')
            ->andWhere('a.status = :val')
            ->andWhere('a.idActivity = :id')
            ->setParameter('val', $value)
            ->setParameter('id',$idActivity)
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

    }
}
