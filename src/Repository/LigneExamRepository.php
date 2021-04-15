<?php

namespace App\Repository;

use App\Entity\LigneExam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneExam|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneExam|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneExam[]    findAll()
 * @method LigneExam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneExamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneExam::class);
    }

    // /**
    //  * @return LigneExam[] Returns an array of LigneExam objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LigneExam
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
